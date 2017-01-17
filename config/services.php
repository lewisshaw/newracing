<?php
$localConfig = require_once __DIR__ . '/services.config.local.php';

return [
    'Silex\Provider\DoctrineServiceProvider' => [
        'db.options'   => $localConfig['db'],
    ],
    'Silex\Provider\ServiceControllerServiceProvider' => [],
    'Silex\Provider\TwigServiceProvider' => [
        'twig.path' => __DIR__ . '/../app/Ui/views',
    ],
    'Silex\Provider\FormServiceProvider' => [
        'form.secret' => 'fagsfsdf8sf8sd7fs8fsdfhtrt',
    ],
    'Silex\Provider\ValidatorServiceProvider' => [],
    'Silex\Provider\TranslationServiceProvider' => [],
    'Silex\Provider\SessionServiceProvider'=> [],
    'Silex\Provider\SecurityServiceProvider' => [
        'security.firewalls' => [
            'admin' => [
                'pattern' => '^/admin/',
                'form'    => ['login_path' => '/login', 'check_path' => '/admin/login_check'],
                'users'   => $localConfig['users'],
                'logout' => ['logout_path' => '/admin/logout'],
            ],
            'unsecured' => [
                'anonymous' => true,
            ],
        ],
    ],
    'Silex\Provider\UrlGeneratorServiceProvider' => [],
    'Racing\ServiceProvider\BoatClass' => [],
    'Racing\ServiceProvider\Competitor' => [],
    'Racing\ServiceProvider\Series' => [],
    'Racing\ServiceProvider\PyNumber' => [],
    'Racing\ServiceProvider\Race' => [],
    'Racing\ServiceProvider\HandicapResult' => [],
    'Racing\ServiceProvider\Result' => [],
    'Racing\ServiceProvider\ClassResult' => [],
    'Racing\ServiceProvider\Twig' => [],
    'Racing\ServiceProvider\User' => [],
    'Racing\ServiceProvider\Url' => [],
    'RacingCli\ServiceProvider\ConsoleServiceProvider' => [],
];
