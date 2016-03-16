<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface as SymfonyValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class Race implements ValidatorInterface
{
    private $validator;

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Request $request)
    {
        $constraint = new Assert\Collection([
                'race_type' => [
                    new Assert\NotBlank([
                        'message' => 'Race type must not be blank',
                    ]),
                    new Assert\Choice([
                        'choices' => ['HANDICAP', 'CLASS'],
                        'message' => 'Please select a valid race type',
                    ])
                ],
                'race_name' => [
                    new Assert\NotBlank([
                        'message' => 'Race name must not be blank'
                    ]),
                    new Assert\Type([
                        'type'    => 'string',
                        'message' => 'Race name must be a string',
                    ]),
                ],
                'race_laps' => [
                    new Assert\NotBlank([
                        'message' => 'Laps must not be blank',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Laps must be numeric'
                    ]),
                ],
                'race_date' => [
                    new Assert\NotBlank([
                        'message' => 'Date must not be blank',
                    ]),
                    new Assert\Date([
                        'message' => 'Date must be a valid date in the format yyyy-mm-dd'
                    ])
                ],
            ]);

        return $this->validator->validateValue($request->request->all(), $constraint);
    }
}
