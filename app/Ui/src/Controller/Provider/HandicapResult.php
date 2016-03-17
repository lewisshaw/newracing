<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use RacingUi\Middleware\Validator;

class HandicapResult implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'handicapresult.controller:index');
        $factory->get('/json', 'handicapresult.controller:indexJson');
        $factory->post('/', 'handicapresult.controller:insert')
                ->before(Validator::getCallback($app['handicapresult.validator'], $app));
        $factory->get('/csv', 'handicapresult.controller:csv');

        $factory->post('/{resultId}/delete', 'handicapresult.controller:delete');

        $factory->get('/preview', 'user.handicap.controller:index');

        return $factory;
    }
}
