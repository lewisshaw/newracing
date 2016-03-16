<?php
namespace Racing\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Url implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['previous_url'] = function () use ($app) {
            $request = $app['request'];
            $referer = $request->headers->get('referer');
            $origin  = $request->headers->get('origin');
            return str_replace($origin, '', $referer);
        };
    }

    public function boot(Application $app)
    {
    }
}
