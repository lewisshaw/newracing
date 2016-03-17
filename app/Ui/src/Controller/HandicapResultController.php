<?php
namespace RacingUi\Controller;

use Racing\Dal\Race;
use Racing\Lookup\Result;
use Racing\Results\Handicap;
use RacingUi\Session\SessionAlertsTrait;
use Racing\Results\Csv;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HandicapResultController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $resultLookup;
    private $handicapResult;
    private $resultsCsv;

    public function __construct(
       $templater,
       Application $app,
       Result $resultLookup,
       Handicap $handicapResult,
       Csv $resultsCsv
    ) {

        $this->templater        = $templater;
        $this->app              = $app;
        $this->handicapResult   = $handicapResult;
        $this->resultLookup     = $resultLookup;
        $this->resultsCsv       = $resultsCsv;
    }

    public function index(Request $request, $raceId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $raceResults = $this->handicapResult->getRawResults($raceId);
        $results     = $raceResults->getResults();
        $race        = $raceResults->getRace();
        $sortedRaceResults = $this->handicapResult->getSortedResults($raceId);
        $sortedResults = $sortedRaceResults->getResults();
        $boatClasses = $this->resultLookup->getBoatClasses();
        $competitors = $this->resultLookup->getCompetitors();


        return $this->templater->render('handicapresults/index.twig', [
            'title' => 'Racing | Handicap Results',
            'isRacesActive' => true,
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
            'sortedResults' => $sortedResults,
            'boatClasses' => $boatClasses,
            'competitors' => $competitors,
            'race'        => $race,
        ]);
    }

    public function csv(Request $request, $raceId)
    {
        $raceResults = $this->handicapResult->getSortedResults($raceId);
        $results     = $raceResults->getResults();
        $race        = $raceResults->getRace();

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
        $pyNumberId  = $request->get('py_number_id');
        $minutes     = $request->get('result_minutes');
        $seconds     = $request->get('result_seconds');
        $laps        = $request->get('result_laps');
        $helm        = $request->get('result_helm');
        $crew        = $request->get('result_crew');

        if (!$this->handicapResult->add($raceId, $sailNumber, $pyNumberId, $minutes, $seconds, $laps, $helm, $crew))
        {
            $this->app['session']->set('errors', ['Result could not be added, please retry']);
            return $this->app->redirect('/admin/races/' . $raceId . '/results/handicap');
        }
        $this->app['session']->set('message', 'Result has been added');
        return $this->app->redirect('/admin/races/' . $raceId . '/results/handicap');
    }

    public function delete($raceId, $resultId)
    {
        $this->handicapResult->delete($resultId);
        $this->app['session']->set('message', 'Result has been deleted');
        return $this->app->redirect('/admin/races/' . $raceId . '/results/handicap');
    }
}
