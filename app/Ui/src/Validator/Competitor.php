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
                'first_name' => [new Assert\NotBlank()],
                'last_name'  => [new Assert\NotBlank()],
            ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
