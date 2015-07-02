<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Race
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data)
    {
        $constraint = new Assert\Collection([
                'race_type' => [new Assert\NotBlank()],
                'race_name' => [new Assert\NotBlank()],
                'race_laps' => [new Assert\NotBlank()],
                'race_date' => [new Assert\NotBlank(), new Assert\Date()],
            ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
