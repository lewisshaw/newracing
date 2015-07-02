<?php
namespace RacingUi\Controller;

use Racing\Dal\PyNumber;
use Racing\Dal\BoatClass;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PyNumberController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;
    private $boatClassDal;

    public function __construct(
       $templater,
       Application $app,
       PyNumber $dal,
       BoatClass $boatClassDal
    ) {

        $this->templater    = $templater;
        $this->app          = $app;
        $this->dal          = $dal;
        $this->boatClassDal = $boatClassDal;
    }

    public function index(Request $request, $boatClassId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $pyNumbers      = $this->dal->getInactiveByBoatClassId($boatClassId);
        $activePyNumber = $this->dal->getActiveByBoatClassId($boatClassId);
        $boatClass      = $this->boatClassDal->get($boatClassId);

        return $this->templater->render('pynumbers/index.twig', [
            'title' => 'Racing | PY Numbers',
            'isClassesActive' => true,
            'errors' => $errors,
            'message' => $message,
            'legacyPyNumbers' => $pyNumbers,
            'activePyNumber'  => $activePyNumber,
            'boatClass'       => $boatClass,
        ]);
    }

    public function insert(Request $request, $boatClassId)
    {
        $pyNumber = $request->get('py_number');

        if (!$this->dal->insert($boatClassId, $pyNumber))
        {
            $this->app['session']->set('errors', ['PY Number could not be added, please retry']);
            return $this->app->redirect('/admin/boatclasses');
        }
        $this->app['session']->set('message', 'PY Number has been added');
        return $this->app->redirect('/admin/boatclasses/' . $boatClassId . '/pynumbers');
    }
}
