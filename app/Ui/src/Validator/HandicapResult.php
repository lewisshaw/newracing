<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class HandicapResult
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    // TODO Validate the competitors array
    public function validate(array $data)
    {
        $constraint = new Assert\Collection([
                'py_number_id' => [
                    new Assert\NotBlank([
                        'message' => 'PY Number Id must not be blank',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'PY Number Id must be numeric'
                    ]),
                ],
                'sail_number' => [
                    new Assert\NotBlank([
                        'message' => 'Sail Number must not be blank',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Sail Number must be numeric'
                    ]),
                ],
                'result_laps' => [
                    new Assert\NotBlank([
                        'message' => 'Laps must not be blank',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Laps must be numeric'
                    ]),
                ],
                'result_minutes' => [
                    new Assert\NotBlank([
                        'message' => 'Minutes must not be blank',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Minutes must be numeric'
                    ]),
                ],
                'result_seconds' => [
                    new Assert\NotBlank([
                        'message' => 'Seconds must not be blank',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Seconds must be numeric'
                    ]),
                ],
                'result_helm' => [
                    new Assert\NotBlank([
                        'message' => 'Helm Id must not be blank',
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
            ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
