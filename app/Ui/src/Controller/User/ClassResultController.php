<?php
namespace RacingUi\Controller\User;

use Silex\Application;
use RacingUi\Session\SessionAlertsTrait;
use Racing\Dal\HandicapResult;
use Racing\Dal\UnfinishedResult;
use Racing\Dal\Race;
use Racing\Results\ClassResult;

class ClassResultController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $classResult;

    public function __construct(
       $templater,
       Application $app,
       ClassResult $classResult
    ) {

        $this->templater   = $templater;
        $this->app         = $app;
        $this->classResult = $classResult;
    }

    public function index($raceId)
    {
        syslog(LOG_INFO, json_encode(['type' => 'ClassRaceView', 'raceId' => $raceId]));
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $raceResults = $this->classResult->getSortedResults($raceId);
        $results = $raceResults->getResults();
        $race = $raceResults->getRace();

        return $this->templater->render('user/classresults.twig', [
            'title' => 'Racing | Handicap Results',
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
            'race' => $race,
        ]);
    }

}
