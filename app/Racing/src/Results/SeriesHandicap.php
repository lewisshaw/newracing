<?php
namespace Racing\Results;

use Racing\Dal\Race as RaceDal;

class SeriesHandicap implements SeriesResultProviderInterface
{
    private $handicapResults;
    private $raceDal;

    public function __construct(Handicap $handicapResults, RaceDal $raceDal)
    {
        $this->handicapResults = $handicapResults;
        $this->raceDal = $raceDal;
    }

    public function getBySeries(int $seriesId)
    {
        $races = $this->raceDal->getAllBySeries($seriesId);
        $results = [];
        foreach ($races as $race) {
           $results[$race['raceId']] = $this->handicapResults->getSortedResults($race['raceId']); 
        }
        var_dump($results);
        exit;
    }
}
