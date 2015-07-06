<?php
namespace Racing\Error;

class Error
{
    private $message;

    public function __construct($message)
    {
        \Assert\that($message)->string()->notEmpty();
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
