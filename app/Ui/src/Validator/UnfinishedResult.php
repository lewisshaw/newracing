<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UnfinishedResult
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
                'result_helm' => [new Assert\NotBlank()],
                'result_crew' => [],
                'unfinished_type' => [new Assert\NotBlank()],
                'redirect_url'    => [new Assert\NotBlank()],
            ]);

        return $this->validator->validateValue($data, $constraint);
    }
}
