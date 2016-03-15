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
    private $raceDal;

    public function __construct(
       $templater,
       Application $app,
       ClassResult $classResult,
       Race $raceDal
    ) {

        $this->templater   = $templater;
        $this->app         = $app;
        $this->classResult = $classResult;
        $this->raceDal     = $raceDal;
    }

    public function index($raceId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $results = $this->classResult->getSortedResults($raceId);
        $race = $this->raceDal->get($raceId);

        return $this->templater->render('user/classresults.twig', [
            'title' => 'Racing | Handicap Results',
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
            'race' => $race,
        ]);
    }

}
