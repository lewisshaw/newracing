<?php
namespace RacingUi\Controller\User\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class CompetitorResults implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/{competitorId}', 'competitor.results.controller:index');

        return $factory;
    }
}
