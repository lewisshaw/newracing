<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface as SymfonyValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class UnfinishedResult implements ValidatorInterface
{
    private $validator;

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    // TODO Validate the competitors array
    public function validate(Request $request)
    {
        $constraint = new Assert\Collection([
                'boat_class_id' => [
                    new Assert\NotBlank([
                        'message' => 'Boat class Id must not be blank'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Boat class Id must be numeric'
                    ]),
                ],
                'sail_number' => [
                    new Assert\NotBlank([
                        'message' => 'Sail Number must not be blank'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Sail Number must be numeric'
                    ]),
                ],
                'result_helm' => [
                    new Assert\NotBlank([
                        'message' => 'Helm Id must not be blank'
                    ]),
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
                ],
                'unfinished_type' => [
                    new Assert\NotBlank([
                        'message' => 'Unfinished Type must not be blank'
                    ]),
                    new Assert\Choice([
                        'choices' => ['DNF', 'DNS'],
                        'message' => 'Result Type must be valid',
                    ])
                ],
                'redirect_url'    => [
                    new Assert\NotBlank([
                        'message' => 'Redirect Url must not be blank'
                    ])
                ],
            ]);

        return $this->validator->validateValue($request->request->all(), $constraint);
    }
}
