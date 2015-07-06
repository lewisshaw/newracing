<?php
namespace RacingUi\Controller\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Competitor implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/', 'competitor.controller:index');
        $factory->post('/', 'competitor.controller:insert')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['competitor.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/admin/competitors');
                    }
                });
        $factory->get('/{competitorId}/edit', 'competitor.controller:edit')
                ->assert('competitorId', '\d+');
        $factory->post('/{competitorId}/update', 'competitor.controller:update')
                ->assert('competitorId', '\d+')
                ->before(function (Request $request) use ($app) {
                    $validator = $app['competitor.validator'];
                    $errors = $validator->validate($request->request->all());
                    if(count($errors))
                    {
                        $competitorId = $request->get('competitorId');
                        $app['session']->set('errors', $errors);
                        return new RedirectResponse('/admin/competitors/' . $competitorId . '/edit');
                    }
                });

        return $factory;
    }
}
