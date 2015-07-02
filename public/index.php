<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config  = require_once __DIR__ . '/../config/app.php';
$servies = require_once __DIR__ . '/../config/services.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application($config);

foreach ($servies as $service => $config) {
    $app->register(new $service(), $config);
}

$app->get('/', function(Request $request) use ($app) {
    return 'Hi, this page is coming soon';
});

$app->get('/admin', function(Request $request) use ($app) {
    return $app->redirect('admin/series');
});

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html', [
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'title'         => 'Racing | Login',
    ]);
});

$app->mount('admin/competitors', new RacingUi\Controller\Provider\Competitor());
$app->mount('admin/series', new RacingUi\Controller\Provider\Series());
$app->mount('admin/boatclasses', new RacingUi\Controller\Provider\BoatClass());
$app->mount('admin/boatclasses/{boatClassId}/pynumbers', new RacingUi\Controller\Provider\PyNumber());
$app->mount('admin/series/{seriesId}/races', new RacingUi\Controller\Provider\Race());
$app->mount('admin/races/{raceId}/results', new RacingUi\Controller\Provider\HandicapResult());
$app->mount('admin/races/{raceId}/classresults', new RacingUi\Controller\Provider\ClassResult());
$app->mount('admin/races/{raceId}/unfinishedresults', new RacingUi\Controller\Provider\UnfinishedResult());


$app->run();
