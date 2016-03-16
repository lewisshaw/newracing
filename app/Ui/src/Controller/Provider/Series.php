<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use RacingUi\Middleware\Validator;

class Series implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'series.controller:index');
        $factory->post('/', 'series.controller:insert')
                ->before(Validator::getCallback($app['series.validator'], $app));

        $factory->get('/{seriesId}/edit', 'series.controller:edit')
                ->assert('seriesId', '\d+');
        $factory->post('/{seriesId}/update', 'series.controller:update')
                ->assert('seriesId', '\d+')
                ->before(Validator::getCallback($app['series.validator'], $app));

        $factory->post('/{seriesId}/publish', 'series.controller:publish')
                ->assert('seriesId', '\d+');
        $factory->post('/{seriesId}/unpublish', 'series.controller:unPublish')
                ->assert('seriesId', '\d+');

        $factory->post('/files', 'seriesfile.controller:upload');

        return $factory;
    }
}
