<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Competitor
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data)
    {
        $constraint = new Assert\Collection([
                'first_name' => [
                    new Assert\NotBlank(['message' => 'First name must not be blank']),
                    new Assert\Type([
                        'type'    => 'string',
                        'message' => 'First name must be a string',
                    ]),
                ],
                'last_name'  => [
                    new Assert\NotBlank(['message' => 'Last name must not be blank']),
                    new Assert\Type([
                        'type'    => 'string',
                        'message' => 'Last name must be a string',
                    ]),
                ],
            ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
