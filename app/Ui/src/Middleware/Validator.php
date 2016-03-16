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
            $errors = $validator->validate($request);
            if(count($errors))
            {
                $app['session']->set('errors', $errors);
                return new RedirectResponse($app['previous_url']);
            }
        };
    }
}
