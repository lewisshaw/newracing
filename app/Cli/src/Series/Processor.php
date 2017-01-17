<?php
namespace RacingCli\Series;

use Racing\Results\SeriesResultProviderInterface;
use Racing\Dal\SeriesResult;

class Processor
{
    private $updateDal;
    private $seriesResultDal;
    private $seriesProvider;

    public function __construct(
        SeriesResultProviderInterface $seriesProvider,
        SeriesResult $seriesResultDal
    ) {
        //$this->updateDal       = $updateDal;
        $this->seriesResultDal = $seriesResultDal;
        $this->seriesProvider = $seriesProvider;
    }

    public function process()
    {
        //$seriesToGenerate = $this->updateDal->getSeriesToGenerate();

        $seriesToGenerate = [9];
        foreach ($seriesToGenerate as $seriesId) {
            $this->generateSeries($seriesId);
        }
    }

    private function generateSeries(int $seriesId)
    {
        $races = $this->getRaceResults($seriesId);
        $this->seriesResultDal->deleteResults($seriesId);
        foreach ($races as $raceResults) {
            $race = $raceResults->getRace();
            $results = $raceResults->getResults();
            foreach ($results as $result) {
                $this->seriesResultDal->addResult(
                    $seriesId,
                    $race['raceId'],
                    $result['helm']['competitorId'],
                    $result['sailNumber'],
                    $result['position']
                );
            }
        }
    }

    private function getRaceResults(int $seriesId)
    {
        return $this->seriesProvider->getBySeries($seriesId);
    }
}
