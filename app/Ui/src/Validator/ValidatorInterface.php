<?php
namespace RacingUi\Validator;

use Symfony\Component\HttpFoundation\Request;

interface ValidatorInterface
{
    public function validate(Request $request);
}
