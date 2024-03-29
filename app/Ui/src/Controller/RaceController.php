<?php
namespace RacingUi\Controller;

use Racing\Dal\Race;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class RaceController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;

    public function __construct(
       $templater,
       Application $app,
       Race $dal
    ) {

        $this->templater = $templater;
        $this->app       = $app;
        $this->dal       = $dal;
    }

    public function index(Request $request, $seriesId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $races = $this->dal->getAllbySeries($seriesId);

        return $this->templater->render('races/index.twig', [
            'title' => 'Racing | Races',
            'isRacesActive' => true,
            'errors' => $errors,
            'message' => $message,
            'races' => $races,
        ]);
    }

    public function insert(Request $request, $seriesId)
    {
        $raceType  = $request->get('race_type');
        $raceName  = $request->get('race_name');
        $laps      = $request->get('race_laps');
        $date      = $request->get('race_date');

        if (!$this->dal->insert($raceType, $seriesId, $raceName, $laps, $date))
        {
            $this->app['session']->set('errors', ['Race could not be added, please retry']);
            return $this->app->redirect('/admin/series/' . $seriesId . '/races');
        }
        $this->app['session']->set('message', 'Race has been added');
        return $this->app->redirect('/admin/series/' . $seriesId . '/races');
    }

    public function publish($seriesId, $raceId)
    {
        if (!$this->dal->setPublished($raceId, 1))
        {
            $this->app['session']->set('errors', ['Race could not be published, please retry']);
            return $this->app->redirect('/admin/series/' . $seriesId . '/races');
        }
        $this->app['session']->set('message', 'Race has been published');
        return $this->app->redirect('/admin/series/' . $seriesId . '/races');
    }

    public function unPublish($seriesId, $raceId)
    {
        if (!$this->dal->setPublished($raceId, 0))
        {
            $this->app['session']->set('errors', ['Race could not be un-published, please retry']);
            return $this->app->redirect('/admin/series/' . $seriesId . '/races');
        }
        $this->app['session']->set('message', 'Race has been un-published');
        return $this->app->redirect('/admin/series/' . $seriesId . '/races');
    }
}
