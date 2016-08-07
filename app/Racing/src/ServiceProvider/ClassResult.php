<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ClassResult implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['classresult.dal'] = $app->share(function () use ($app) {
            return new \Racing\Dal\ClassResult($app['db'], $app['resultcompetitor.dal'], $app['result.dal']);
        });

        $app['classresult.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\ClassResultController(
                $app['twig'],
                $app,
                $app['results.class'],
                $app['lookup.result'],
                $app['results.csv'],
                $app['import.results.csv']
            );
        });

        $app['classresult.validator'] = $app->share(function () use ($app) {
            return new \RacingUi\Validator\ClassResult($app['validator']);
        });
    }

    public function boot(Application $app)
    {
    }
}
