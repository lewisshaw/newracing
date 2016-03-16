<?php
namespace Racing\Results;

use Racing\Dal\ClassResult as ClassResultDal;
use Racing\Dal\UnfinishedResult;
use Racing\Dal\Race;
use Racing\Results\RaceResult;

class ClassResult
{
    private $classResultDal;
    private $unfinishedResultDal;
    private $raceDal;

    public function __construct(ClassResultDal $classResultDal, UnfinishedResult $unfinishedResultDal, Race $raceDal)
    {
        $this->classResultDal = $classResultDal;
        $this->unfinishedResultDal = $unfinishedResultDal;
        $this->raceDal = $raceDal;
    }

    public function getRawResults($raceId)
    {
        $race = $this->raceDal->get($raceId);
        return new RaceResult($race, array_merge($this->classResultDal->getByRace($raceId), $this->unfinishedResultDal->getByRace($raceId)));
    }

    public function getSortedResults($raceId)
    {
        $results = $this->classResultDal->getByRace($raceId);
        $race = $this->raceDal->get($raceId);
        usort($results, function ($a, $b) {
            return $a['position'] - $b['position'];
        });

        $unfinishedResults = $this->unfinishedResultDal->getByRace($raceId);
        $unfinishedPosition = count($results) + count($unfinishedResults) + 1;
        foreach ($unfinishedResults as &$result) {
            $result['position'] = $unfinishedPosition;
        }
        unset($result);

        return new RaceResult($race, array_merge($results, $unfinishedResults));
    }

    public function add($raceId, $sailNumber, $boatClassId, $position, $helm, $crew)
    {
        $competitors = [
            'helm' => $helm,
        ];

        if(!empty($crew))
        {
            $competitors['crew'] = $crew;
        }

        if (!$this->classResultDal->insert($raceId, $sailNumber, $boatClassId, $position, $competitors))
        {
            return false;
        }
        return true;
    }

    public function delete($resultId)
    {
        $this->classResultDal->delete($resultId);
    }
}
