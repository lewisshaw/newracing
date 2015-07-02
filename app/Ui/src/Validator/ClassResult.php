<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ClassResult
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
                'boat_class_id' => [new Assert\NotBlank()],
                'sail_number' => [new Assert\NotBlank()],
                'result_position' => [new Assert\NotBlank()],
                'result_helm' => [new Assert\NotBlank()],
                'result_crew' => [],
            ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
