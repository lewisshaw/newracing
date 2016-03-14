<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ClassResult implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'classresult.controller:index');
        $factory->post('/', 'classresult.controller:insert')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['classresult.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $raceId = $request->get('raceId');
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/admin/races/' . $raceId . '/results/class');
                    }
                });

        return $factory;
    }
}
