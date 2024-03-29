<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface as SymfonyValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class ClassResult implements ValidatorInterface
{
    private $validator;

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Request $request)
    {
        $constraint = new Assert\Collection([
                'boat_class_id' => [
                    new Assert\NotBlank(['message' => 'Boat class must not be blank']),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Boat class Id must be numeric'
                    ]),
                ],
                'sail_number' => [
                    new Assert\NotBlank(['message' => 'Sail number must not be blank']),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Sail Number must be numeric'
                    ]),
                ],
                'result_position' => [
                    new Assert\NotBlank(['message' => 'Position must not be blank']),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Position must be numeric'
                    ]),
                ],
                'result_helm' => [
                    new Assert\NotBlank(['message' => 'Helm must not be blank']),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Helm Id must be numeric'
                    ]),
                ],
                'result_crew' => [
                    new Assert\Regex([
                        'pattern' => '/^\d*$/',
                        'message' => 'Crew Id must be blank or numeric'
                    ]),
                ]
            ]);

        return $this->validator->validateValue($request->request->all(), $constraint);
    }
}
