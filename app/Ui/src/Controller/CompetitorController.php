<?php
namespace RacingUi\Controller;

use Racing\Dal\Competitor;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class CompetitorController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;

    public function __construct(
       $templater,
       Application $app,
       Competitor $dal
    ) {

        $this->templater = $templater;
        $this->app       = $app;
        $this->dal       = $dal;
    }

    public function index(Request $request)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $competitors = $this->dal->getAll();

        return $this->templater->render('competitors/index.twig', [
            'title' => 'Racing | Competitors',
            'isCompetitorsActive' => true,
            'errors' => $errors,
            'message' => $message,
            'competitors' => $competitors,
        ]);
    }

    public function insert(Request $request)
    {
        $firstName = $request->get('first_name');
        $lastName  = $request->get('last_name');
        if (!$this->dal->insert($firstName, $lastName))
        {
            $this->app['session']->set('errors', ['Competitor could not be added, please retry']);
            return $this->app->redirect('/admin/competitors');
        }
        $this->app['session']->set('message', 'Competitor has been added');
        return $this->app->redirect('/admin/competitors');
    }

    public function edit(Request $request, $competitorId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $competitor = $this->dal->get($competitorId);

        if (!$competitor) {
            $this->app->abort(404);
        }

        return $this->templater->render('competitors/edit.twig', [
            'title'      => 'Racing | Competitor | Edit',
            'errors'     => $errors,
            'message'    => $message,
            'competitor' => $competitor,
        ]);
    }

    public function update(Request $request, $competitorId)
    {
        $firstName = $request->get('first_name');
        $lastName  = $request->get('last_name');

        if (!$this->dal->update($competitorId, $firstName, $lastName))
        {
            $this->app['session']->set('errors', ['Competitor could not be updated, please retry']);
            return $this->app->redirect('/admin/competitors');
        }
        $this->app['session']->set('message', 'Competitor has been updated');
        return $this->app->redirect('/admin/competitors');
    }
}
