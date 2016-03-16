<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use RacingUi\Middleware\Validator;

class Race implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'race.controller:index');
        $factory->post('/', 'race.controller:insert')
                ->before(Validator::getCallback($app['race.validator'], $app));

        $factory->post('/{raceId}/publish', 'race.controller:publish')
                ->assert('raceId', '\d+');
        $factory->post('/{raceId}/unpublish', 'race.controller:unPublish')
                ->assert('raceId', '\d+');

        return $factory;
    }
}
