<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Symfony\Component\Console\Application;
use RacingCli\Command\GenerateSeriesCommand;

$application = new Application();
$application->add(new GenerateSeriesCommand());
$application->run();
