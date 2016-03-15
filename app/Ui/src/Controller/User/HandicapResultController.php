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
    private $raceDal;

    public function __construct(
       $templater,
       Application $app,
       Handicap $handicapResult,
       Race $raceDal
    ) {

        $this->templater      = $templater;
        $this->app            = $app;
        $this->handicapResult = $handicapResult;
        $this->raceDal        = $raceDal;
    }

    public function index($raceId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $results = $this->handicapResult->getSortedResults($raceId);
        $race = $this->raceDal->get($raceId);

        return $this->templater->render('user/handicapresult.twig', [
            'title' => 'Racing | Handicap Results',
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
            'race' => $race,
        ]);
    }

}
