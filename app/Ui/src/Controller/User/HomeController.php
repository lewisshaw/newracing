<?php
namespace RacingUi\Controller\User;

use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Racing\Races\RacesBySeries;

class HomeController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $racesBySeries;

    public function __construct($templater, Application $app, RacesBySeries $racesBySeries)
    {
        $this->templater = $templater;
        $this->app = $app;
        $this->racesBySeries = $racesBySeries;
    }

    public function index($seriesId = null)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $series = $this->racesBySeries->getSeriesRacesByDate(date('Y-m-d'));
        $olderSeries = $this->racesBySeries->getSeriesRacesBeforeDate(date('Y-m-d'));

        return $this->templater->render('user/home.twig', [
            'title' => 'Racing | Home',
            'errors' => $errors,
            'message' => $message,
            'series' => $series,
            'olderSeries' => $olderSeries,
            'selectedSeries' =>$seriesId,
        ]);
    }
}
