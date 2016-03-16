<?php
namespace Racing\Results;

class RaceResult
{
    private $race;
    private $results;

    public function __construct(array $race, array $results)
    {
        $this->race = $race;
        $this->results = $results;
    }

    public function getRace()
    {
        return $this->race;
    }

    public function getResults()
    {
        return $this->results;
    }
}
