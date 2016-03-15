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
    private $resultLookup;
    private $raceDal;
    private $resutsCsv;

    public function __construct(
       $templater,
       Application $app,
       ClassResult $classResult,
       Result $resultLookup,
       Race $raceDal,
       Csv $resultsCsv
    ) {

        $this->templater    = $templater;
        $this->app          = $app;
        $this->classResult  = $classResult;
        $this->resultLookup = $resultLookup;
        $this->raceDal      = $raceDal;
        $this->resultsCsv   = $resultsCsv;
    }

    public function index(Request $request, $raceId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $results = $this->classResult->getRawResults($raceId);
        $boatClasses = $this->resultLookup->getBoatClasses();
        $competitors = $this->resultLookup->getCompetitors();
        $race        = $this->raceDal->get($raceId);

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
        $results = $this->classResult->getSortedResults($raceId);
        $race    = $this->raceDal->get($raceId);

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
}
