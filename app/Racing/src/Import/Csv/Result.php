<?php
namespace Racing\Import\Csv;

class Result
{
    private $success;
    private $errors;
    public function __construct($success, array $errors = [])
    {
        \Assert\that($success)->boolean();
        $this->success = $success;
        \Assert\thatAll($errors)->string();
        $this->errors = $errors;
    }

    public function isSuccessful()
    {
        return $this->success;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
