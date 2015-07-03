<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Race implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'race.controller:index');
        $factory->post('/', 'race.controller:insert')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['race.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $seriesId = $request->get('seriesId');
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/admin/series/' . $seriesId . '/races');
                    }
                });

        $factory->post('/{raceId}/publish', 'race.controller:publish');
        $factory->post('/{raceId}/unpublish', 'race.controller:unPublish');

        return $factory;
    }
}
