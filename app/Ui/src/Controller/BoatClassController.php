<?php
namespace RacingUi\Controller;

use Racing\Dal\BoatClass;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class BoatClassController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;

    public function __construct(
       $templater,
       Application $app,
       BoatClass $dal
    ) {

        $this->templater = $templater;
        $this->app       = $app;
        $this->dal       = $dal;
    }

    public function index(Request $request)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $boatClasses = $this->dal->getAll();

        return $this->templater->render('boatclasses/index.twig', [
            'title' => 'Racing | Boat Classes',
            'isClassesActive' => true,
            'errors' => $errors,
            'message' => $message,
            'boatClasses' => $boatClasses,
        ]);
    }

    public function insert(Request $request)
    {
        $name = $request->get('boat_class_name');

        if (!$this->dal->insert($name))
        {
            $this->app['session']->set('errors', ['Boat Class could not be added, please retry']);
            return $this->app->redirect('/admin/boatclasses');
        }
        $this->app['session']->set('message', 'Boat Class has been added');
        return $this->app->redirect('/admin/boatclasses');
    }
}
