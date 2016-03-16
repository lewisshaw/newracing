<?php
namespace RacingUi\Validator;

use Symfony\Component\Validator\ValidatorInterface as SymfonyValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class SeriesFile implements ValidatorInterface
{
    private $validator;

    public function __construct(SymfonyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Request $request)
    {
        $file = $request->files->get('league-table-file');

        $constraint = new Assert\File([
            'mimeTypes' => 'text/html',
        ]);

        $fileErrors = $this->validator->validateValue($file, $constraint);

        $constraint = new Assert\Collection([
                'series-id' => [
                    new Assert\NotBlank(['message' => 'Series Id must not be blank']),
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Series Id must be numeric'
                    ]),
                ],
            ]);

        $errors = $this->validator->validateValue($request->request->all(), $constraint);
        $errors->addAll($fileErrors);

        return $errors;
    }
}
