<?php
namespace RacingUi\Controller\User;

use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Racing\Dal\Series;
use Racing\Dal\Race;

class HomeController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;
    private $racesDal;

    public function __construct($templater, Application $app, Series $dal, Race $racesDal)
    {
        $this->templater = $templater;
        $this->app = $app;
        $this->dal = $dal;
        $this->racesDal = $racesDal;
    }

    public function index()
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $currentSeries = $this->dal->getByDate(date('Y-m-d'));
        foreach ($currentSeries as &$series) {
            $series['races'] = $this->racesDal->getBySeries($series['seriesId']);
        }
        unset($series);

        return $this->templater->render('user/home.twig', [
            'title' => 'Racing | Home',
            'errors' => $errors,
            'message' => $message,
            'series' => $currentSeries,
        ]);
    }
}
