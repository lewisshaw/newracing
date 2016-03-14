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
                'boat_class_name' => [
                    new Assert\NotBlank(['message' => 'Boat class name must not be blank']),
                    new Assert\Type([
                        'type' => 'string',
                        'message' => 'Please enter a valid boat class name'
                    ]),
                ],
                'persons' => [
                    new Assert\NotBlank(['message' => 'Persons must not be blank']),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Crew must be numeric'
                    ]),
                ]
            ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
