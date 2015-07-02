<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Competitor implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['competitor.dal'] = $app->share(function () use ($app) {
            return new \Racing\Dal\Competitor($app['db']);
        });

        $app['competitor.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\CompetitorController($app['twig'], $app, $app['competitor.dal']);
        });

        $app['competitor.validator'] = $app->share(function () use ($app) {
            return new \RacingUi\Validator\Competitor($app['validator']);
        });
    }

    public function boot(Application $app)
    {
    }
}
