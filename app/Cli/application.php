<?php

require_once __DIR__.'/../../vendor/autoload.php';

use RacingCli\Command\GenerateSeriesCommand;

$config  = require_once __DIR__ . '/../../config/app.php';
$servies = require_once __DIR__ . '/../../config/services.php';

$app = new Silex\Application($config);

foreach ($servies as $service => $config) {
    $app->register(new $service(), $config);
}

$app['console']->add(new GenerateSeriesCommand($app));
$app['console']->run();
