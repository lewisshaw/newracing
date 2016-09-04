<?php
namespace RacingUi\Controller\Api;

use Symfony\Component\HttpFoundation\Request;

class RaceImport
{

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function import(Request $request)
    {
        //receives json with Race name, race date, race csv
        //Add a race with correct details
        //Try processing the CSV
        //If no errors, publish race
        return $password;
    }
}
