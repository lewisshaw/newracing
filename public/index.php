<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config  = require_once __DIR__ . '/../config/app.php';
$servies = require_once __DIR__ . '/../config/services.php';
$routes = require_once __DIR__ . '/../config/routes.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application($config);

foreach ($servies as $service => $config) {
    $app->register(new $service(), $config);
}

$app->get('/admin', function(Request $request) use ($app) {
    return $app->redirect('admin/series');
});

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.twig', [
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'title'         => 'Racing | Login',
    ]);
});

foreach ($routes as $route => $provider) {
    $app->mount($route, $provider);
}

$app->run();
