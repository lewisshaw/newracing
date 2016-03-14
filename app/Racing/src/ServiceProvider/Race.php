<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Race implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['race.dal'] = $app->share(function () use ($app) {
            $raceType = new \Racing\Dal\RaceType($app['db']);
            return new \Racing\Dal\Race($app['db'], $raceType);
        });

        $app['race.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\RaceController($app['twig'], $app, $app['race.dal']);
        });

        $app['race.validator'] = $app->share(function () use ($app) {
            return new \RacingUi\Validator\Race($app['validator']);
        });

        $app['races.racesbyseries'] = $app->share(function () use ($app) {
            return new \Racing\Races\RacesBySeries($app['series.dal'], $app['race.dal']);
        });
    }

    public function boot(Application $app)
    {
    }
}
