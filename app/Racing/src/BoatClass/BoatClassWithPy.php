<?php
namespace Racing\BoatClass;

use Racing\Dal\BoatClass;
use Racing\Dal\PyNumber;

class BoatClassWithPy
{
    private $boatClassDal;
    private $pyNumberDal;

    public function __construct(BoatClass $boatClassDal, PyNumber $pyNumberDal)
    {
        $this->boatClassDal = $boatClassDal;
        $this->pyNumberDal  = $pyNumberDal;
    }
    public function addOrUpdate($boatClassName, $pyNumber, $persons)
    {
        $boatClass = $this->boatClassDal->getByName($boatClassName);
        if ($boatClass) {
            $boatClassId = $boatClass['boatClassId'];
        }
        else {
            $boatClassId = $this->boatClassDal->insert($boatClassName, $persons);
        }

        if ($boatClass && $pyNumber == $boatClass['pyNumber']) {
            return true;
        }

        return $this->pyNumberDal->insert($boatClassId, $pyNumber);
    }
}
