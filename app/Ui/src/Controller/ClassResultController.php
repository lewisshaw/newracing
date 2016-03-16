<?php
namespace RacingUi\Controller;

use Racing\Results\ClassResult;
use Racing\Dal\BoatClass;
use Racing\Dal\Competitor;
use Racing\Dal\Race;
use Racing\Dal\UnfinishedResult;
use Racing\Results\Csv;
use Racing\Lookup\Result;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClassResultController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $classResult;
    private $resultLookup;
    private $resutsCsv;

    public function __construct(
       $templater,
       Application $app,
       ClassResult $classResult,
       Result $resultLookup,
       Csv $resultsCsv
    ) {

        $this->templater    = $templater;
        $this->app          = $app;
        $this->classResult  = $classResult;
        $this->resultLookup = $resultLookup;
        $this->resultsCsv   = $resultsCsv;
    }

    public function index(Request $request, $raceId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $raceResults = $this->classResult->getRawResults($raceId);
        $results = $raceResults->getResults();
        $race = $raceResults->getRace();
        $boatClasses = $this->resultLookup->getBoatClasses();
        $competitors = $this->resultLookup->getCompetitors();

        return $this->templater->render('classresults/index.twig', [
            'title' => 'Racing | Class Results',
            'isRacesActive' => true,
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
            'boatClasses' => $boatClasses,
            'competitors' => $competitors,
            'race'        => $race,
        ]);
    }

    public function csv(Request $request, $raceId)
    {
        $raceResults = $this->classResult->getSortedResults($raceId);
        $results = $raceResults->getResults();
        $race    = $raceResults->getRace();;

        $filename = 'sailwave-results-' . date('d-m-Y-i:j:s');
        return new Response(
            $this->resultsCsv->getCsvString($results, $race),
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment;filename=$filename.csv",
            ]
        );
    }

    public function insert(Request $request, $raceId)
    {
        $sailNumber  = $request->get('sail_number');
        $boatClassId = $request->get('boat_class_id');
        $position    = $request->get('result_position');
        $helm        = $request->get('result_helm');
        $crew        = $request->get('result_crew');

        if (!$this->classResult->add($raceId, $sailNumber, $boatClassId, $position, $helm, $crew))
        {
            $this->app['session']->set('errors', ['Result could not be added, please retry']);
            return $this->app->redirect('/admin/races/' . $raceId . '/results/class');
        }
        $this->app['session']->set('message', 'Result has been added');
        return $this->app->redirect('/admin/races/' . $raceId . '/results/class');
    }

    public function delete($raceId, $resultId)
    {
        $this->classResult->delete($resultId);
        $this->app['session']->set('message', 'Result has been deleted');
        return $this->app->redirect('/admin/races/' . $raceId . '/results/class');
    }
}
