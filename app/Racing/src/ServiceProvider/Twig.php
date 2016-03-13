<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Twig implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
            $token = $app['security.token_storage']->getToken();
            if (null !== $token) {
                $user = $token->getUser();
                $twig->addGlobal('user', ['name' => (string) $user]);
            }

            return $twig;
        }));
    }

    public function boot(Application $app)
    {
    }
}
