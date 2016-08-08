<?php
namespace RacingUi\Controller;

use Racing\Results\ClassResult;
use Racing\Dal\BoatClass;
use Racing\Dal\Competitor;
use Racing\Dal\Race;
use Racing\Dal\UnfinishedResult;
use Racing\Results\Csv;
use Racing\Import\Csv\Processor;
use Racing\Lookup\Result;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use League\Csv\Reader;
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
    private $csvProcessor;

    public function __construct(
       $templater,
       Application $app,
       ClassResult $classResult,
       Result $resultLookup,
       Csv $resultsCsv,
       Processor $csvProcessor
    ) {

        $this->templater    = $templater;
        $this->app          = $app;
        $this->classResult  = $classResult;
        $this->resultLookup = $resultLookup;
        $this->resultsCsv   = $resultsCsv;
        $this->csvProcessor = $csvProcessor;
    }

    public function index(Request $request, $raceId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $raceResults = $this->classResult->getRawResults($raceId);
        $results = $raceResults->getResults();
        $race = $raceResults->getRace();
        $sortedRaceResults = $this->classResult->getSortedResults($raceId);
        $sortedResults = $sortedRaceResults->getResults();
        $boatClasses = $this->resultLookup->getBoatClasses();
        $competitors = $this->resultLookup->getCompetitors();
        $tab = '';
        if (null !== $request->query->get('unfinished')) {
            $tab = 'unfinished';
        }

        if (null !== $request->query->get('csv')) {
            $tab = 'csv';
        }

        return $this->templater->render('classresults/index.twig', [
            'title' => 'Racing | Class Results',
            'isRacesActive' => true,
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
            'sortedResults' => $sortedResults,
            'boatClasses' => $boatClasses,
            'competitors' => $competitors,
            'race'        => $race,
            'tab'         => $tab,
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

    public function upload(Request $request, $raceId)
    {
        $upload = $request->files->get('results-file');
        if (!$upload) {
            $this->app['session']->set(
                'errors',
                [
                    0 => [
                        'Message' => 'Please select a file'
                    ]
                ]
            );
            return $this->app->redirect($this->app['previous_url']);
        }
        $savedFile = $upload->move(__DIR__ . '/../../../../uploads/class-results/', $upload->getClientOriginalName());
        $reader = Reader::createFromPath($savedFile->getPathName());
        $result = $this->csvProcessor->processClass($reader, $raceId);
        if (!$result->isSuccessful()) {
            $errors = [];
            $errors[]['Message'] = 'The file could not be processed for the following reasons';
            foreach ($result->getErrors() as $error) {
                $errors[]['Message'] = $error;
            }
            $this->app['session']->set('errors', $errors);
            return $this->app->redirect('/admin/races/' . $raceId . '/results/class');
        }
        $this->app['session']->set('message', 'File processed - Please check results manually to ensure they are correct');
        return $this->app->redirect('/admin/races/' . $raceId . '/results/class');
    }
}
