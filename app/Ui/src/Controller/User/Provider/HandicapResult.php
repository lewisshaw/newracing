<?php
namespace RacingUi\Controller\User\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class HandicapResult implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'user.handicap.controller:index');

        return $factory;
    }
}
