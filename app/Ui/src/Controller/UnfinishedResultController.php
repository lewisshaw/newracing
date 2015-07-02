<?php
namespace RacingUi\Controller;

use Racing\Dal\UnfinishedResult;
use Racing\Dal\BoatClass;
use Racing\Dal\Competitor;
use Racing\Dal\Race;
use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class UnfinishedResultController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;
    private $dal;
    private $boatClassDal;
    private $competitorsDal;
    private $raceDal;

    public function __construct(
       $templater,
       Application $app,
       UnfinishedResult $dal,
       BoatClass $boatClassDal,
       Competitor $competitorsDal,
       Race $raceDal
    ) {

        $this->templater      = $templater;
        $this->app            = $app;
        $this->dal            = $dal;
        $this->boatClassDal   = $boatClassDal;
        $this->competitorsDal = $competitorsDal;
        $this->raceDal        = $raceDal;
    }

    public function insert(Request $request, $raceId)
    {
        $sailNumber     = $request->get('sail_number');
        $boatClassId    = $request->get('boat_class_id');
        $unfinishedType = $request->get('unfinished_type');
        $helm           = $request->get('result_helm');
        $crew           = $request->get('result_crew');
        $redirectUrl    = $request->get('redirect_url');

        $competitors = [
            'helm' => $helm,
        ];

        if(!empty($crew))
        {
            $competitors['crew'] = $crew;
        }

        if (!$this->dal->insert($raceId, $sailNumber, $boatClassId, $unfinishedType, $competitors))
        {
            $this->app['session']->set('errors', ['Result could not be added, please retry']);
            return $this->app->redirect('/' . $redirectUrl);
        }
        $this->app['session']->set('message', 'Result has been added');
        return $this->app->redirect('/' . $redirectUrl);
    }
}
