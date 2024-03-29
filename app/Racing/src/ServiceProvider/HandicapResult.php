<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class HandicapResult implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['handicapresult.dal'] = $app->share(function () use ($app) {
            return new \Racing\Dal\HandicapResult($app['db'], $app['resultcompetitor.dal'], $app['result.dal']);
        });

        $app['handicapresult.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\HandicapResultController(
                $app['twig'],
                $app,
                $app['lookup.result'],
                $app['results.handicap'],
                $app['results.csv'],
                $app['import.results.csv']
            );
        });

        $app['handicapresult.validator'] = $app->share(function () use ($app) {
            return new \RacingUi\Validator\HandicapResult($app['validator']);
        });
    }

    public function boot(Application $app)
    {
    }
}
