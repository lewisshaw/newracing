<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface as SymfonyValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Series implements ValidatorInterface
{
    private $validator;

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data)
    {
        $constraint = new Assert\Collection([
            'series_name' => [
                new Assert\NotBlank([
                    'message' => 'Name must not be blank',
                ])
            ],
            'start_date'  => [
                new Assert\NotBlank([
                    'message' => 'Start date must not be blank',
                ]),
                new Assert\Date([
                    'message' => 'Start date must be a valid date in the format yyyy-mm-dd'
                ]),
            ],
            'end_date' => [
                new Assert\NotBlank([
                    'message' => 'End date must not be blank',
                ]),
                new Assert\Date([
                    'message' => 'Start date must be a valid date in the format yyyy-mm-dd'
                ]),
            ],
        ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
