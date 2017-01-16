<?php

require_once __DIR__.'/../../vendor/autoload.php';

use RacingCli\Command\GenerateSeriesCommand;

$app = new Cilex\Application('Racing');

$app->command(new GenerateSeriesCommand());
$app->run();
