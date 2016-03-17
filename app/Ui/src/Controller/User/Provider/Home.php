<?php
namespace RacingUi\Controller\User\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Home implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'home.controller:index');
        $factory->get('/{seriesId}', 'home.controller:index');

        return $factory;
    }
}
