<?php
namespace RacingUi\Controller\Api\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use RacingUi\Middleware\Validator;

class RaceImport implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];
        $factory->post('/', 'api.raceimport.controller:import'); //TODO Add validator
        return $factory;
    }
}
