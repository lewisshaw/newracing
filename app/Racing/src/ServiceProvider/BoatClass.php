<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class BoatClass implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['boatclass.dal'] = $app->share(function () use ($app) {
            return new \Racing\Dal\BoatClass($app['db']);
        });

        $app['boatclass.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\BoatClassController($app['twig'], $app, $app['boatclass.dal'], $app['boatclass.boatclasswithpy']);
        });

        $app['boatclass.validator'] = $app->share(function () use ($app) {
            return new \RacingUi\Validator\BoatClass($app['validator']);
        });

        $app['boatclass.boatclasswithpy'] = $app->share(function () use ($app) {
            return new \Racing\BoatClass\BoatClassWithPy($app['boatclass.dal'], $app['pynumber.dal']);
        });
    }

    public function boot(Application $app)
    {
    }
}
