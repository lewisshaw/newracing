<?php
namespace RacingCli\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ConsoleServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['console'] = $app->share(function () use ($app) {
            return new \Symfony\Component\Console\Application('Racing', '1.0');
        });
    }

    public function boot(Application $app)
    {
    }
}
