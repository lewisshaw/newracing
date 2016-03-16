<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Series implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['series.dal'] = $app->share(function () use ($app) {
            return new \Racing\Dal\Series($app['db']);
        });

        $app['series.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\SeriesController($app['twig'], $app, $app['series.dal']);
        });

        $app['series.validator'] = $app->share(function () use ($app) {
            return new \RacingUi\Validator\Series($app['validator']);
        });

        $app['seriesfile.dal'] = $app->share(function () use ($app) {
            return new \Racing\Dal\SeriesFile($app['db']);
        });

        $app['seriesfile.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\SeriesFileController($app, $app['seriesfile.dal']);
        });

        $app['seriesfile.validator'] = $app->share(function () use ($app) {
            return new \RacingUi\Validator\SeriesFile($app['validator']);
        });
    }

    public function boot(Application $app)
    {
    }
}
