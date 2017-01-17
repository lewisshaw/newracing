<?php
namespace RacingUi\Controller\User\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class SeriesTable implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $factory = $app['controllers_factory'];

        $factory->get('/{seriesId}', 'user.seriestable.controller:index');

        return $factory;
    }
}
