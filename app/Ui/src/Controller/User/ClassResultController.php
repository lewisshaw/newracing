<?php
namespace RacingUi\Controller\User;

use Silex\Application;
use RacingUi\Session\SessionAlertsTrait;
use Racing\Dal\HandicapResult;
use Racing\Dal\UnfinishedResult;
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
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $results = $this->classResult->getSortedResults($raceId);

        return $this->templater->render('user/classresults.twig', [
            'title' => 'Racing | Handicap Results',
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
        ]);
    }

}
