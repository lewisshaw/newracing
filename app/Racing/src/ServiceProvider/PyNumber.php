<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class PyNumber implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['pynumber.dal'] = $app->share(function () use ($app) {
            return new \Racing\Dal\PyNumber($app['db']);
        });

        $app['pynumber.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\PyNumberController(
                $app['twig'], $app,
                $app['pynumber.dal'],
                $app['boatclass.dal']
            );
        });

        $app['pynumber.validator'] = $app->share(function () use ($app) {
            return new \RacingUi\Validator\PyNumber($app['validator']);
        });
    }

    public function boot(Application $app)
    {
    }
}
