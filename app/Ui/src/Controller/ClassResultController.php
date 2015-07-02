<?php
namespace RacingUi\Controller;

use Racing\Dal\ClassResult;
use Racing\Dal\BoatClass;
use Racing\Dal\Competitor;
use Racing\Dal\Race;
use Racing\Dal\UnfinishedResult;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ClassResultController
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
       ClassResult $dal,
       BoatClass $boatClassDal,
       Competitor $competitorsDal,
       Race $raceDal,
       UnfinishedResult $unfinishedResult
    ) {

        $this->templater      = $templater;
        $this->app            = $app;
        $this->dal            = $dal;
        $this->boatClassDal   = $boatClassDal;
        $this->competitorsDal = $competitorsDal;
        $this->raceDal        = $raceDal;
        $this->unfinishedResult = $unfinishedResult;
    }

    public function index(Request $request, $raceId)
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();

        $results = $this->dal->getByRace($raceId);
        $boatClasses = $this->boatClassDal->getAll();
        $competitors = $this->competitorsDal->getAll();
        $race        = $this->raceDal->get($raceId);
        $unfinishedResults = $this->unfinishedResult->getByRace($raceId);

        //check here if race is the wrong type!!!!!

        return $this->templater->render('classresults/index.twig', [
            'title' => 'Racing | Class Results',
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
        $sailNumber  = $request->get('sail_number');
        $boatClassId = $request->get('boat_class_id');
        $position    = $request->get('result_position');
        $helm        = $request->get('result_helm');
        $crew        = $request->get('result_crew');

        $competitors = [
            'helm' => $helm,
        ];

        if(!empty($crew))
        {
            $competitors['crew'] = $crew;
        }

        if (!$this->dal->insert($raceId, $sailNumber, $boatClassId, $position, $competitors))
        {
            $this->app['session']->set('errors', ['Result could not be added, please retry']);
            return $this->app->redirect('/admin/races/' . $raceId . '/classresults');
        }
        $this->app['session']->set('message', 'Result has been added');
        return $this->app->redirect('/admin/races/' . $raceId . '/classresults');
    }
}
