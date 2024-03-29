<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface as SymfonyValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class PyNumber implements ValidatorInterface
{
    private $validator;

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Request $request)
    {
        $constraint = new Assert\Collection([
                'py_number' => [
                    new Assert\NotBlank([
                        'message' => 'PY Number must not be blank',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'PY Number must be numeric'
                    ]),
                ],
            ]);

        return $this->validator->validateValue($request->request->all(), $constraint);
    }
}
