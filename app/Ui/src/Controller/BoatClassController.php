<?php
namespace RacingUi\Controller;

use Racing\Dal\BoatClass;
use RacingUi\Session\SessionAlertsTrait;
use Racing\BoatClass\BoatClassWithPy;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use League\Csv\Reader;

class BoatClassController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;
    private $boatClass;

    public function __construct(
       $templater,
       Application $app,
       BoatClass $dal,
       BoatClassWithPy $boatClass
    ) {

        $this->templater = $templater;
        $this->app       = $app;
        $this->dal       = $dal;
        $this->boatClass = $boatClass;
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
        $persons = $request->get('persons');

        if (!$this->dal->insert($name, $persons))
        {
            $this->app['session']->set('errors', ['Boat Class could not be added, please retry']);
            return $this->app->redirect('/admin/boatclasses');
        }
        $this->app['session']->set('message', 'Boat Class has been added');
        return $this->app->redirect('/admin/boatclasses');
    }

    public function edit(Request $request, $boatClassId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $boatClass = $this->dal->get($boatClassId);

        if (!$boatClass) {
            $this->app->abort(404);
        }

        return $this->templater->render('boatclasses/edit.twig', [
            'title'     => 'Racing | Boat Classes | Edit',
            'errors'    => $errors,
            'message'   => $message,
            'boatClass' => $boatClass,
        ]);
    }

    public function update(Request $request, $boatClassId)
    {
        $name = $request->get('boat_class_name');

        if (!$this->dal->update($boatClassId, $name))
        {
            $this->app['session']->set('errors', ['Boat Class could not be updated, please retry']);
            return $this->app->redirect('/admin/boatclasses');
        }
        $this->app['session']->set('message', 'Boat Class has been updated');
        return $this->app->redirect('/admin/boatclasses');
    }

    public function upload(Request $request)
    {
        $upload = $request->files->get('py-list-file');
        if (!$upload) {
            $this->app['session']->set(
                'errors',
                [
                    0 => [
                        'Message' => 'Please select a file'
                    ]
                ]
            );
            return $this->app->redirect('/admin/races/' . $raceId . '/results/handicap');
        }
        $savedFile = $upload->move(__DIR__ . '/../../../../uploads/py/', $upload->getClientOriginalName());
        $reader = Reader::createFromPath($savedFile->getPathName());
        $rows = $reader->fetch();
        foreach ($rows as $row) {
            if (empty($row[0]) || empty($row[4])) {
                continue;
            }
            $this->boatClass->addOrUpdate(ucwords(strtolower($row[0])), $row[4], $row[1]);
        }
        $this->app['session']->set('message', 'File processed - Please check py numbers manually to ensure they are correct');
        return $this->app->redirect('/admin/boatclasses');
    }
}
