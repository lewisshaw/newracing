<?php
namespace Racing\Results;

class PyResultCalculator
{
    public function calculateTime(array $result, array $race)
    {
        if (!$result['crew'] && $result['boatClassPersons'] > 1) {
            $result['pyNumber'] -= 20;
        }
        $timeAfterLaps = round(bcmul(bcdiv($result['time'], $result['laps'], 5), $race['laps'], 5));
        return round(bcdiv(bcmul($timeAfterLaps, 1000, 5), $result['pyNumber'], 5));
    }
}
