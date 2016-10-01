<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class User implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['home.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\User\HomeController($app['twig'], $app, $app['races.racesbyseries']);
        });

        $app['user.handicap.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\User\HandicapResultController($app['twig'], $app, $app['results.handicap']);
        });

        $app['user.class.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\User\ClassResultController($app['twig'], $app, $app['results.class']);
        });

        $app['competitor.results.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\User\CompetitorResultsController($app['twig'], $app['results.class']);
        });
    }

    public function boot(Application $app)
    {
    }
}
