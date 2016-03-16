<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface as SymfonyValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class Competitor implements ValidatorInterface
{
    private $validator;

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Request $request)
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

        return $this->validator->validateValue($request->request->all(), $constraint);
    }
}
