<?php
namespace RacingUi\Controller;

use Racing\Dal\SeriesFile;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class SeriesFileController
{
    private $seriesFileDal;
    private $app;

    public function __construct(Application $app, SeriesFile $seriesFileDal)
    {
        $this->seriesFileDal = $seriesFileDal;
        $this->app = $app;
    }

    public function upload(Request $request)
    {
        $upload = $request->files->get('league-table-file');
        $seriesId = $request->get('series-id');
        $filename = 'league-table-' . $seriesId . '.htm';
        $savedFile = $upload->move(__DIR__ . '/../../../../public/league-tables/', $filename);
        $currentFile = $this->seriesFileDal->get($seriesId);
        if ($currentFile) {
            $this->seriesFileDal->update($seriesId, $filename);
        }
        else {
            $this->seriesFileDal->insert($seriesId, $filename);
        }

        $this->app['session']->set('message', 'Series file updated');
        return $this->app->redirect('/admin/series');
    }
}
