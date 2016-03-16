<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use RacingUi\Middleware\Validator;

class BoatClass implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'boatclass.controller:index');
        $factory->post('/', 'boatclass.controller:insert')
                ->before(Validator::getCallback($app['boatclass.validator'], $app));

        $factory->get('/{boatClassId}/edit', 'boatclass.controller:edit')
                ->assert('boatClassId', '\d+');
        $factory->post('/{boatClassId}/update', 'boatclass.controller:update')
                ->assert('boatClassId', '\d+')
                ->before(Validator::getCallback($app['boatclass.validator'], $app));

        $factory->post('/upload', 'boatclass.controller:upload');

        return $factory;
    }
}
