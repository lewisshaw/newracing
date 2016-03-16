<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Result implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['resultcompetitor.dal'] = $app->share(function () use ($app) {
            $competitorType = new \Racing\Dal\CompetitorType($app['db']);
            return new \Racing\Dal\ResultCompetitor($app['db'], $competitorType);
        });

        $app['unfinishedresult.dal'] = $app->share(function () use ($app) {
            $unfinishedResultType = new \Racing\Dal\UnfinishedResultType($app['db']);
            return new \Racing\Dal\UnfinishedResult($app['db'], $app['resultcompetitor.dal'], $unfinishedResultType, $app['result.dal']);
        });

        $app['unfinishedresult.controller'] = $app->share(function () use ($app) {
            return new \RacingUi\Controller\UnfinishedResultController(
                $app['twig'],
                $app,
                $app['unfinishedresult.dal'],
                $app['boatclass.dal'],
                $app['competitor.dal'],
                $app['race.dal']
            );
        });

        $app['result.dal'] = $app->share(function () use ($app) {
            return new \Racing\Dal\Result($app['db']);
        });

        $app['unfinishedresult.validator'] = $app->share(function () use ($app) {
            return new \RacingUi\Validator\UnfinishedResult($app['validator']);
        });

        $app['results.handicap'] = $app->share(function () use ($app) {
            return new \Racing\Results\Handicap($app['handicapresult.dal'], $app['unfinishedresult.dal'], $app['race.dal']);
        });

        $app['results.class'] = $app->share(function () use ($app) {
            return new \Racing\Results\ClassResult($app['classresult.dal'], $app['unfinishedresult.dal'], $app['race.dal']);
        });

        $app['lookup.result'] = $app->share(function () use ($app) {
            return new \Racing\Lookup\Result($app['competitor.dal'], $app['boatclass.dal']);
        });

        $app['results.csv'] = $app->share(function () use ($app) {
            return new \Racing\Results\Csv();
        });
    }

    public function boot(Application $app)
    {
    }
}
