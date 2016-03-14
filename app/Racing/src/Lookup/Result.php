<?php
namespace Racing\Lookup;

use Racing\Dal\Competitor;
use Racing\Dal\BoatClass;

class Result
{
    private $competitorDal;
    private $boatClassDal;

    public function __construct(Competitor $competitorDal, BoatClass $boatClassDal)
    {
        $this->competitorDal = $competitorDal;
        $this->boatClassDal  = $boatClassDal;
    }

    public function getCompetitors()
    {
        return $this->competitorDal->getAll();
    }

    public function getBoatClasses()
    {
        return $this->boatClassDal->getAllWithPy();
    }
}
