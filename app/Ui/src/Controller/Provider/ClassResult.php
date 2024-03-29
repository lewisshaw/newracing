<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use RacingUi\Middleware\Validator;

class ClassResult implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'classresult.controller:index');
        $factory->post('/', 'classresult.controller:insert')
                ->before(Validator::getCallback($app['classresult.validator'], $app));
        $factory->get('/csv', 'classresult.controller:csv');

        $factory->post('/{resultId}/delete', 'classresult.controller:delete');

        $factory->get('/preview', 'user.class.controller:index');

        $factory->post('/import/csv', 'classresult.controller:upload');

        return $factory;
    }
}
