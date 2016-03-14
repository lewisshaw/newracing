<?php
namespace Racing\Races;

use Racing\Dal\Series;
use Racing\Dal\Race;

class RacesBySeries
{
    private $seriesDal;
    private $racesDal;
    public function __construct(Series $seriesDal, Race $racesDal)
    {
        $this->seriesDal = $seriesDal;
        $this->racesDal  = $racesDal;
    }

    public function getSeriesRacesByDate($date)
    {
        $currentSeries = $this->seriesDal->getByDate($date);
        return $this->addRacesToSeries($currentSeries);
    }

    public function getSeriesRacesBeforeDate($date)
    {
        $series = $this->seriesDal->getBeforeDate($date);
        return $this->addRacesToSeries($series);
    }

    private function addRacesToSeries($series)
    {
        foreach ($series as &$seriesRow) {
            $seriesRow['races'] = $this->racesDal->getPublishedBySeries($seriesRow['seriesId']);
        }
        unset($seriesRow);
        return $series;
    }
}
