<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface as SymfonyValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class BoatClass implements ValidatorInterface
{
    private $validator;

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Request $request)
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

        return $this->validator->validateValue($request->request->all(), $constraint);
    }
}
