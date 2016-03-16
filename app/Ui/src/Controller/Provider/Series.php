<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Series implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'series.controller:index');
        $factory->post('/', 'series.controller:insert')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['series.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/admin/series');
                    }
                });

        $factory->get('/{seriesId}/edit', 'series.controller:edit')
                ->assert('seriesId', '\d+');
        $factory->post('/{seriesId}/update', 'series.controller:update')
                ->assert('seriesId', '\d+')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['series.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $seriesId = $request->get('seriesId');
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/admin/series/' . $seriesId . '/edit');
                    }
                });

        $factory->post('/{seriesId}/publish', 'series.controller:publish')
                ->assert('seriesId', '\d+');
        $factory->post('/{seriesId}/unpublish', 'series.controller:unPublish')
                ->assert('seriesId', '\d+');

        $factory->post('/files', 'seriesfile.controller:upload');

        return $factory;
    }
}
