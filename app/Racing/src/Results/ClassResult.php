<?php
namespace Racing\Results;

use Racing\Dal\ClassResult as ClassResultDal;
use Racing\Dal\UnfinishedResult;

class ClassResult
{
    private $classResultDal;
    private $unfinishedResultDal;

    public function __construct(ClassResultDal $classResultDal, UnfinishedResult $unfinishedResultDal)
    {
        $this->classResultDal = $classResultDal;
        $this->unfinishedResultDal = $unfinishedResultDal;
    }

    public function getRawResults($raceId)
    {
        return array_merge($this->classResultDal->getByRace($raceId), $this->unfinishedResultDal->getByRace($raceId));
    }

    public function getSortedResults($raceId)
    {
        $results = $this->classResultDal->getByRace($raceId);
        usort($results, function ($a, $b) {
            return $a['position'] - $b['position'];
        });

        $unfinishedResults = $this->unfinishedResultDal->getByRace($raceId);
        $unfinishedPostion = count($results) + couunt($unfinishedResults) + 1;
        foreach ($unfinishedResults as &$result) {
            $result['position'] = $unfinishedPosition;
        }
        unset($result);

        return array_merge($results, $unfinishedResults);
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
}
