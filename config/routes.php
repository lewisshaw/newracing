<?php
//Array of all mounted routes as route => provider
return [
    'admin/competitors'=> new RacingUi\Controller\Provider\Competitor(),
    'admin/series'=> new RacingUi\Controller\Provider\Series(),
    'admin/boatclasses'=> new RacingUi\Controller\Provider\BoatClass(),
    'admin/boatclasses/{boatClassId}/pynumbers'=> new RacingUi\Controller\Provider\PyNumber(),
    'admin/series/{seriesId}/races'=> new RacingUi\Controller\Provider\Race(),
    'admin/races/{raceId}/results/handicap'=> new RacingUi\Controller\Provider\HandicapResult(),
    'admin/races/{raceId}/results/class'=> new RacingUi\Controller\Provider\ClassResult(),
    'admin/races/{raceId}/unfinishedresults'=> new RacingUi\Controller\Provider\UnfinishedResult(),
    '/'=> new RacingUi\Controller\User\Provider\Home(),
    '/races/{raceId}/results/handicap'=> new RacingUi\Controller\User\Provider\HandicapResult(),
    '/races/{raceId}/results/class' => new RacingUi\Controller\User\Provider\ClassResult(),
    '/results/competitor/' => new RacingUi\Controller\User\Provider\CompetitorResults(),
];
