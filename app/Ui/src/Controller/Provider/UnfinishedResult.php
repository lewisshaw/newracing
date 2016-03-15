<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UnfinishedResult implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->post('/', 'unfinishedresult.controller:insert')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['unfinishedresult.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $raceId = $request->get('raceId');
                        $redirectUrl = $request->get('redirect_url');
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/' . $redirectUrl);
                    }
                });

        $factory->post('/{resultId}/delete', 'unfinishedresult.controller:delete');

        return $factory;
    }
}
