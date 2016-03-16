<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use RacingUi\Middleware\Validator;

class PyNumber implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'pynumber.controller:index');
        $factory->post('/', 'pynumber.controller:insert')
                ->before(Validator::getCallback($app['pynumber.validator'], $app));

        return $factory;
    }
}
