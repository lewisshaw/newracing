<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config  = require_once __DIR__ . '/../config/app.php';
$servies = require_once __DIR__ . '/../config/services.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application($config);

foreach ($servies as $service => $config) {
    $app->register(new $service(), $config);
}

$app['races.racesbyseries'] = $app->share(function () use ($app) {
    return new Racing\Races\RacesBySeries($app['series.dal'], $app['race.dal']);
});

$app['home.controller'] = $app->share(function () use ($app) {
    return new RacingUi\Controller\User\HomeController($app['twig'], $app, $app['races.racesbyseries']);
});

$app['results.handicap'] = $app->share(function () use ($app) {
    return new Racing\Results\Handicap($app['handicapresult.dal'], $app['unfinishedresult.dal']);
});

$app['results.class'] = $app->share(function () use ($app) {
    return new Racing\Results\ClassResult($app['classresult.dal'], $app['unfinishedresult.dal']);
});

$app['user.handicap.controller'] = $app->share(function () use ($app) {
    return new RacingUi\Controller\User\HandicapResultController($app['twig'], $app, $app['results.handicap']);
});

$app['lookup.result'] = $app->share(function () use ($app) {
    return new Racing\Lookup\Result($app['competitor.dal'], $app['boatclass.dal']);
});

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

$app->mount('admin/competitors', new RacingUi\Controller\Provider\Competitor());
$app->mount('admin/series', new RacingUi\Controller\Provider\Series());
$app->mount('admin/boatclasses', new RacingUi\Controller\Provider\BoatClass());
$app->mount('admin/boatclasses/{boatClassId}/pynumbers', new RacingUi\Controller\Provider\PyNumber());
$app->mount('admin/series/{seriesId}/races', new RacingUi\Controller\Provider\Race());
$app->mount('admin/races/{raceId}/results/handicap', new RacingUi\Controller\Provider\HandicapResult());
$app->mount('admin/races/{raceId}/results/class', new RacingUi\Controller\Provider\ClassResult());
$app->mount('admin/races/{raceId}/unfinishedresults', new RacingUi\Controller\Provider\UnfinishedResult());
$app->mount('/', new RacingUi\Controller\User\Provider\Home());
$app->mount('/races/{raceId}/results/handicap', new RacingUi\Controller\User\Provider\HandicapResult());

$app->run();
