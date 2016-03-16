<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use RacingUi\Middleware\Validator;

class UnfinishedResult implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->post('/', 'unfinishedresult.controller:insert')
                ->before(Validator::getCallback($app['unfinishedresult.validator'], $app));

        $factory->post('/{resultId}/delete', 'unfinishedresult.controller:delete');

        return $factory;
    }
}
