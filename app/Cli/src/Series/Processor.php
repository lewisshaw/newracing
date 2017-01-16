<?php
namespace RacingCli\Series;

use Racing\Results\SeriesResultProviderInterface;

class Processor
{
    private $updateDal;
    private $seriesResultDal;
    private $seriesProvider;

    public function __construct(SeriesResultProviderInterface $seriesProvider)
    {
        //$this->updateDal       = $updateDal;
        //$this->seriesResultDal = $seriesResultDal;
        $this->seriesProvider = $seriesProvider;
    }

    public function process()
    {
        $seriesToGenerate = $this->updateDal->getSeriesToGenerate();

        foreach ($seriesToGenerate as $seriesId) {
            $this->generateSeries($seriesId);
        }
    }

    private function generateSeries(int $seriesId)
    {
        $races = $this->getRaceResults($seriesId);
        var_dump($races);
        exit;
        foreach ($races as $raceId => $results) {
            $helmId = 1;
            foreach ($results as $result) {
                $this->seriesResultDal->addResult(
                    $seriesId,
                    $raceId,
                    $helmId,
                    $result['sailNumber'],
                    $result['position']
                );
            }
        }
    }

    private function getRaceResults(int $seriesId)
    {
        return $this->seriesResults->getBySeries($seriesId);
    }
}
