<?php
namespace Racing\Import\Csv;

use League\Csv\Reader;
use Racing\Dal\Competitor;
use Racing\Dal\PyNumber;
use Racing\Dal\BoatClass;
use Racing\Results\ClassResult;
use Racing\Results\Handicap;
use Racing\Dal\UnfinishedResult;
use Doctrine\DBAL\Driver\Connection;

class Processor
{
    private $pyNumberDal;
    private $competitorDal;
    private $boatClassDal;
    private $handicapResult;
    private $classResult;
    private $unfinishedResult;
    private $dbConn;

    public function __construct(
        PyNumber $pyNumberDal,
        Competitor $competitorDal,
        BoatClass $boatClassDal,
        Handicap $handicapResult,
        ClassResult $classResult,
        UnfinishedResult $unfinishedResult,
        Connection $dbConn
        ) {
        $this->pyNumberDal      = $pyNumberDal;
        $this->competitorDal    = $competitorDal;
        $this->boatClassDal     = $boatClassDal;
        $this->handicapResult   = $handicapResult;
        $this->classResult      = $classResult;
        $this->unfinishedResult = $unfinishedResult;
        $this->dbConn           = $dbConn;
    }

    public function processHandicap(Reader $reader, $raceId)
    {
        $keys = ['sailNumber', 'class', 'minutes', 'seconds', 'laps', 'helm', 'crew', 'pyNumber', 'numberOfCrew'];
        $this->dbConn->beginTransaction();
        try {
            $rows = $this->getRows($reader, $keys);
            $errors = [];
            $count = 0;
            foreach ($rows as $row) {
                $count++;
                try {
                    Validator::validateHandicapRow($row);
                } catch (\Exception $e) {
                    $errors[] = 'Error on line ' . $count . ' of csv:';
                    $errors[] = $e->getMessage();
                }
                $pyNumber = $this->getPyNumber($row['class'], $row['pyNumber'], $row['numberOfCrew']);
                $helm = $this->getCompetitorId($row['helm']);
                $crew = $this->getCompetitorId($row['crew']);
                if (empty(trim($row['minutes'])) || empty(trim($row['seconds']))) {
                    $competitors = [
                        'helm' => $helm,
                        'crew' => $crew,
                    ];
                    $this->unfinishedResult->insert(
                        $raceId,
                        trim($row['sailNumber']),
                        $this->getBoatClass($row['class'], $row['numberOfCrew']),
                        'DNF',
                        $competitors
                    );
                    continue;
                }

                $this->handicapResult->add(
                    $raceId,
                    trim($row['sailNumber']),
                    $pyNumber,
                    trim($row['minutes']),
                    trim($row['seconds']),
                    trim($row['laps']),
                    $helm,
                    $crew
                );
            }
        } catch (\Exception $e) {
            $errors[] = "An unknown error occured during processing, please retry";
            $this->dbConn->rollBack();
            return new Result(false, $errors);
        }
        if (count($errors) > 0) {
            $this->dbConn->rollBack();
            return new Result(false, $errors);
        }
        $this->dbConn->commit();
        return new Result(true);
    }

    public function processClass(Reader $reader, $raceId)
    {
        $keys = ['sailNumber', 'class', 'position', 'helm', 'crew', 'pyNumber', 'numberOfCrew'];
        $this->dbConn->beginTransaction();
        try {
            $rows = $this->getRows($reader, $keys);
            $count = 0;
            foreach ($rows as $row) {
                $count++;
                try {
                    Validator::validateClassRow($row);
                } catch (\Exception $e) {
                    $errors[] = 'Error on line ' . $count . ' of csv:';
                    $errors[] = $e->getMessage();
                }
                $helm = $this->getCompetitorId($row['helm']);
                $crew = $this->getCompetitorId($row['crew']);
                $boatClass = $this->getBoatClass($row['class'], $row['numberOfCrew']);
                if (empty(trim($row['position']))) {
                    $competitors = [
                        'helm' => $helm,
                        'crew' => $crew,
                    ];
                    $this->unfinishedResult->insert(
                        $raceId,
                        trim($row['sailNumber']),
                        $boatClass,
                        'DNF',
                        $competitors
                    );
                    continue;
                }

                $this->classResult->add($raceId, trim($row['sailNumber']), $boatClass, trim($row['position']), $helm, $crew);
            }
        } catch (\Exception $e) {
            $errors[] = "An unknown error occured during processing, please retry";
            $this->dbConn->rollBack();
            return new Result(false, $errors);
        }
        if (count($errors) > 0) {
            $this->dbConn->rollBack();
            return new Result(false, $errors);
        }
        $this->dbConn->commit();
        return new Result(true);
    }

    private function getRows(Reader $reader, $keys)
    {
        return $reader->fetchAssoc($keys);
    }

    private function getBoatClass($className, $numberOfCrew)
    {
        $boatClass = $this->boatClassDal->getByName(trim($className));
        if (!empty($boatClass)) {
            return $boatClass['boatClassId'];
        }

        return $this->boatClassDal->insert(trim($className), trim($numberOfCrew));
    }

    private function getPyNumber($className, $pyNumber, $numberOfCrew)
    {
        $boatClass = $this->boatClassDal->getByName(trim($className));
        if (!empty($boatClass)) {
            $boatClassId  = $boatClass['boatClassId'];
            $pyNumberInfo = $this->pyNumberDal->getActiveByBoatClassId($boatClassId);
            return $pyNumberInfo['pyNumberId'];
        }

        $boatClassId = $this->boatClassDal->insert(trim($className), trim($numberOfCrew));
        return $this->pyNumberDal->insert($boatClassId, $pyNumber);
    }

    private function getCompetitorId($fullName)
    {
        $nameParts = explode(' ', trim($fullName));
        if (empty($nameParts)) {
            return;
        }
        $firstName = trim($nameParts[0]);
        $lastName  = trim($nameParts[1]);
        $id = $this->competitorDal->getByName($firstName, $lastName);
        if (!$id) {
            $id = $this->competitorDal->insert($firstName, $lastName);
        }

        return $id;
    }
}
