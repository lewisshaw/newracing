<?php
namespace Racing\Results;

use Racing\Dal\HandicapResult;
use Racing\Dal\UnfinishedResult;
use Racing\Dal\Race;

class Handicap
{
    private $handicapResultDal;
    private $unfinishedResultDal;
    private $raceDal;

    public function __construct(HandicapResult $handicapResultDal, UnfinishedResult $unfinishedResultDal, Race $raceDal)
    {
        $this->handicapResultDal = $handicapResultDal;
        $this->unfinishedResultDal = $unfinishedResultDal;
        $this->raceDal = $raceDal;
    }

    public function getRawResults($raceId)
    {
        return array_merge($this->handicapResultDal->getByRace($raceId), $this->unfinishedResultDal->getByRace($raceId));
    }

    public function getSortedResults($raceId)
    {
        $results = $this->handicapResultDal->getByRace($raceId);
        $race = $this->raceDal->get($raceId);
        foreach ($results as &$result) {
            if (!$result['crew'] && $result['boatClassPersons'] > 1) {
                $result['pyNumber'] -= 20;
            }
            $timeAfterLaps = round(bcmul(bcdiv($result['time'], $result['laps'], 5), $race['laps'], 5));
            $result['correctedTime'] = round(bcdiv(bcmul($timeAfterLaps, 1000, 5), $result['pyNumber'], 5));
        }
        unset($result);
        usort($results, function ($a, $b) {
            return $a['correctedTime'] - $b['correctedTime'];
        });

        foreach ($results as $key => &$result) {
            $result['position'] = $key + 1;
        }
        unset($result);

        $unfinishedResults = $this->unfinishedResultDal->getByRace($raceId);
        $unfinishedPosition = count($results) + count($unfinishedResults)  + 1;
        foreach ($unfinishedResults as &$result) {
            $result['position'] = $unfinishedPosition;
        }

        return array_merge($results, $unfinishedResults);
    }

    public function add($raceId, $sailNumber, $pyNumberId, $minutes, $seconds, $laps, $helm, $crew)
    {
        $competitors = [
            'helm' => $helm,
        ];

        if(!empty($crew))
        {
            $competitors['crew'] = $crew;
        }

        $time = $minutes * 60 + $seconds;

        if (!$this->handicapResultDal->insert($raceId, $sailNumber, $pyNumberId, $time, $laps, $competitors))
        {
            return false;
        }

        return true;
    }

    public function delete($resultId)
    {
        $this->handicapResultDal->delete($resultId);
    }
}
