<?php
namespace RacingUi\Controller;

use Racing\Dal\Race;
use Racing\Lookup\Result;
use Racing\Results\Handicap;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use League\Csv\Writer;

class HandicapResultController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;
    private $resultLookup;
    private $raceDal;
    private $handicapResult;

    public function __construct(
       $templater,
       Application $app,
       Result $resultLookup,
       Race $raceDal,
       Handicap $handicapResult
    ) {

        $this->templater        = $templater;
        $this->app              = $app;
        $this->raceDal          = $raceDal;
        $this->handicapResult   = $handicapResult;
        $this->resultLookup     = $resultLookup;
    }

    public function index(Request $request, $raceId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $results = $this->handicapResult->getRawResults($raceId);
        $boatClasses = $this->resultLookup->getBoatClasses();
        $competitors = $this->resultLookup->getCompetitors();
        $race        = $this->raceDal->get($raceId);

        return $this->templater->render('handicapresults/index.twig', [
            'title' => 'Racing | Handicap Results',
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
        $results = $this->handicapResult->getSortedResults($raceId);
        $race    = $this->raceDal->get($raceId);
        $file    = new \SplFileObject('php://temp', 'w');
        $writer = Writer::createFromFileObject($file);
        $writer->insertOne(['SailNo', 'Class', 'HelmName', 'RaceNo', 'Place', 'Code']);
        foreach ($results as $result) {
            $row = [$result['sailNumber'], $result['boatClassName'], $result['helm'], date('d/m/Y', strtotime($race['date'])), $result['position']];
            if (isset($result['unfinishedResultHandle'])) {
                $row[] = $result['unfinishedResultHandle'];
            }
            else {
                $row[] = '';
            }

            $writer->insertOne($row);
        }
        $filename = 'sailwave-results-' . date('d-m-Y-i:j:s');
        return new Response(
            $writer->__toString(),
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
}
