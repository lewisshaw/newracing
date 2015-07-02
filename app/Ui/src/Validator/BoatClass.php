<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BoatClass
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data)
    {
        $constraint = new Assert\Collection([
                'boat_class_name' => [new Assert\NotBlank()],
            ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
