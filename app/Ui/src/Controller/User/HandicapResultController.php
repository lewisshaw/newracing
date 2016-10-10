<?php
namespace RacingUi\Controller\User;

use Silex\Application;
use RacingUi\Session\SessionAlertsTrait;
use Racing\Dal\HandicapResult;
use Racing\Dal\UnfinishedResult;
use Racing\Dal\Race;
use Racing\Results\Handicap;

class HandicapResultController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $handicapCalc;

    public function __construct(
       $templater,
       Application $app,
       Handicap $handicapResult
    ) {

        $this->templater      = $templater;
        $this->app            = $app;
        $this->handicapResult = $handicapResult;
    }

    public function index($raceId)
    {
        syslog(LOG_INFO, json_encode(['type' => 'HandicapRaceView', 'raceId' => $raceId]));
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $raceResults = $this->handicapResult->getSortedResults($raceId);
        $results = $raceResults->getResults();
        $race = $raceResults->getRace();

        return $this->templater->render('user/handicapresult.twig', [
            'title' => 'Racing | Handicap Results',
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
            'race' => $race,
        ]);
    }

}
