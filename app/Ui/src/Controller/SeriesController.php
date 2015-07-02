<?php
namespace RacingUi\Controller;

use Racing\Dal\Series;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class SeriesController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;

    public function __construct(
       $templater,
       Application $app,
       Series $dal
    ) {

        $this->templater = $templater;
        $this->app       = $app;
        $this->dal       = $dal;
    }

    public function index(Request $request)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $series = $this->dal->getAll();

        return $this->templater->render('series/index.twig', [
            'title' => 'Racing | Series',
            'isSeriesActive' => true,
            'errors' => $errors,
            'message' => $message,
            'series' => $series,
        ]);
    }

    public function insert(Request $request)
    {
        $name       = $request->get('series_name');
        $startDate  = $request->get('start_date');
        $endDate    = $request->get('end_date');
        if (!$this->dal->insert($name, $startDate, $endDate))
        {
            $this->app['session']->set('errors', ['Series could not be added, please retry']);
            return $this->app->redirect('/admin/series');
        }
        $this->app['session']->set('message', 'Series has been added');
        return $this->app->redirect('/admin/series');
    }
}
