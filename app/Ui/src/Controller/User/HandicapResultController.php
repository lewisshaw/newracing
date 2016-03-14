<?php
namespace RacingUi\Controller\User;

use Silex\Application;
use RacingUi\Session\SessionAlertsTrait;
use Racing\Dal\HandicapResult;
use Racing\Dal\UnfinishedResult;
use Racing\Results\Handicap;

class HandicapResultController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;
    private $unfinishedResult;
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
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $results = $this->handicapResult->getSortedResults($raceId);

        return $this->templater->render('user/handicapresult.twig', [
            'title' => 'Racing | Handicap Results',
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
        ]);
    }

}
