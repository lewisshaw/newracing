<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Api implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['api.raceimport.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\Api\RaceImport($app);
        });
    }

    public function boot(Application $app)
    {
    }
}
