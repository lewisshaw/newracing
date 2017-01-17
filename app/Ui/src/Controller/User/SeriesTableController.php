<?php
namespace RacingUi\Controller\User;

use Racing\Dal\SeriesResult;
use Racing\Dal\Race as RaceDal;
use Silex\Application;

class SeriesTableController
{
    private $templater;
    private $seriesResultDal;
    private $raceDal;

    public function __construct($templater, SeriesResult $seriesResultDal, RaceDal $raceDal)
    {
        $this->templater = $templater;
        $this->seriesResultDal = $seriesResultDal;
        $this->raceDal = $raceDal;
    }

    public function index(int $seriesId)
    {
        $competitorResults = $this->seriesResultDal->getResults($seriesId);
        $races = $this->raceDal->getAllBySeries($seriesId);

        $formattedTable = [];
        foreach ($races as $race) {
            foreach ($competitorResults as $competitorId => $results) {
                if (!isset($results[$race['raceId']])) {
                    $formattedTable[$competitorId][$race['raceId']]['position'] = count($competitorResults) + 1;
                } else {
                    $formattedTable[$competitorId][$race['raceId']]['position'] = $results[$race['raceId']]['position'];
                }
            }
        }

        echo '<pre>';
        var_dump($formattedTable);
        echo '</pre>';
        exit;
    }
}
