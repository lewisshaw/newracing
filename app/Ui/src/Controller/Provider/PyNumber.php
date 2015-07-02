<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PyNumber implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'pynumber.controller:index');
        $factory->post('/', 'pynumber.controller:insert')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['pynumber.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $app['session']->set('errors', $errors);
                        $boatClassId = $request->get('boatClassId');
                        return new RedirectResponse('/admin/boatclasses/' . $boatClassId . '/pynumbers');
                    }
                });

        return $factory;
    }
}
