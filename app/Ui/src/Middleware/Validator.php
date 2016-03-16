<?php
namespace RacingUi\Middleware;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use RacingUi\Validator\ValidatorInterface;

class Validator
{
    public static function getCallback(ValidatorInterface $validator, Application $app) {
        return function (Request $request) use ($app, $validator) {
            $errors = $validator->validate($request->request->all());
            $redirectUrl = Validator::getRefererRoute($request);
            if(count($errors))
            {
                $app['session']->set('errors', $errors);
                return new RedirectResponse($redirectUrl);
            }
        };
    }

    public static function getRefererRoute(Request $request)
    {
        $referer = $request->headers->get('referer');
        $origin  = $request->headers->get('origin');
        $redirectUrl = str_replace($origin, '', $referer);

        return $redirectUrl;
    }
}
