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
            $params = $app['request']->query->all();
            //echo $origin . ' : ' . $referer;
            $urlParts = explode("?", $referer);
            $referer = $urlParts[0];
            $query  = empty($params) ? '' : '?' . http_build_query($params);
            return str_replace($origin, '', $referer) . $query;
        };
    }

    public function boot(Application $app)
    {
    }
}
