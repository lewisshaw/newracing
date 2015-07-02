<?php
namespace RacingUi\Controller;

use Racing\Dal\HandicapResult;
use Racing\Dal\BoatClass;
use Racing\Dal\Competitor;
use Racing\Dal\Race;
use Racing\Dal\UnfinishedResult;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class HandicapResultController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;
    private $boatClassDal;
    private $competitorsDal;
    private $raceDal;
    private $unfinishedResult;

    public function __construct(
       $templater,
       Application $app,
       HandicapResult $dal,
       BoatClass $boatClassDal,
       Competitor $competitorsDal,
       Race $raceDal,
       UnfinishedResult $unfinishedResult
    ) {

        $this->templater        = $templater;
        $this->app              = $app;
        $this->dal              = $dal;
        $this->boatClassDal     = $boatClassDal;
        $this->competitorsDal   = $competitorsDal;
        $this->raceDal          = $raceDal;
        $this->unfinishedResult = $unfinishedResult;
    }

    public function index(Request $request, $raceId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $results = $this->dal->getByRace($raceId);
        $boatClasses = $this->boatClassDal->getAllWithPy();
        $competitors = $this->competitorsDal->getAll();
        $race        = $this->raceDal->get($raceId);
        $unfinishedResults = $this->unfinishedResult->getByRace($raceId);

        return $this->templater->render('handicapresults/index.twig', [
            'title' => 'Racing | Handicap Results',
            'isRacesActive' => true,
            'errors' => $errors,
            'message' => $message,
            'results' => $results,
            'unfinishedResults' => $unfinishedResults,
            'boatClasses' => $boatClasses,
            'competitors' => $competitors,
            'race'        => $race,
        ]);
    }

    public function insert(Request $request, $raceId)
    {
        //ADD AN UNFISHED RESULT HERE, OR ADD COMMON ENDPOINT FOR CLASS AND HANDICAP TO ADD UNFINISHED RESULT
        $sailNumber  = $request->get('sail_number');
        $pyNumberId  = $request->get('py_number_id');
        $minutes     = $request->get('result_minutes');
        $seconds     = $request->get('result_seconds');
        $laps        = $request->get('result_laps');
        $helm        = $request->get('result_helm');
        $crew        = $request->get('result_crew');

        $competitors = [
            'helm' => $helm,
        ];

        if(!empty($crew))
        {
            $competitors['crew'] = $crew;
        }

        $time = $minutes * 60 + $seconds;

        // TODO use filter for mins to secs /secs to mins
        if (!$this->dal->insert($raceId, $sailNumber, $pyNumberId, $time, $laps, $competitors))
        {
            $this->app['session']->set('errors', ['Result could not be added, please retry']);
            return $this->app->redirect('/admin/races/' . $raceId . '/results');
        }
        $this->app['session']->set('message', 'Result has been added');
        return $this->app->redirect('/admin/races/' . $raceId . '/results');
    }
}
