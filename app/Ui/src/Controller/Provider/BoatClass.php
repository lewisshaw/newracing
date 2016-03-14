<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class BoatClass implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'boatclass.controller:index');
        $factory->post('/', 'boatclass.controller:insert')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['boatclass.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/admin/boatclasses');
                    }
                });

        $factory->get('/{boatClassId}/edit', 'boatclass.controller:edit')
                ->assert('boatClassId', '\d+');
        $factory->post('/{boatClassId}/update', 'boatclass.controller:update')
                ->assert('boatClassId', '\d+')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['boatclass.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $boatClassId = $request->get('boatClassId');
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/admin/boatclasses/' . $boatClassId . '/edit');
                    }
                });

        $factory->post('/upload', 'boatclass.controller:upload');

        return $factory;
    }
}
