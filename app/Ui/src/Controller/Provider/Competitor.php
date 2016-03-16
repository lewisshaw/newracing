<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use RacingUi\Middleware\Validator;

class Competitor implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'competitor.controller:index');
        $factory->post('/', 'competitor.controller:insert')
                ->before(Validator::getCallback($app['competitor.validator'], $app));
        $factory->get('/{competitorId}/edit', 'competitor.controller:edit')
                ->assert('competitorId', '\d+');
        $factory->post('/{competitorId}/update', 'competitor.controller:update')
                ->assert('competitorId', '\d+')
                ->before(Validator::getCallback($app['competitor.validator'], $app));

        return $factory;
    }
}
