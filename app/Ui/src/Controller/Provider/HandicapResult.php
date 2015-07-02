<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class HandicapResult implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'handicapresult.controller:index');
        $factory->post('/', 'handicapresult.controller:insert')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['handicapresult.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $raceId = $request->get('raceId');
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/admin/races/' . $raceId . '/results');
                    }
                });

        return $factory;
    }
}
