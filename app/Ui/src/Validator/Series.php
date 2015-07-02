<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Series
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data)
    {
        $constraint = new Assert\Collection([
            'series_name' => [new Assert\NotBlank()],
            'start_date'  => [new Assert\NotBlank(), new Assert\Date()],
            'end_date'    => [new Assert\NotBlank(), new Assert\Date()],
        ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
