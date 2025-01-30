<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\UserInterface\Request\Validation;

use Neuron\BuildingBlocks\UserInterface\Request\ConstraintViolationListTransformer;
use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class RequestValidator implements RequestValidatorInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(RequestInterface $request): void
    {
        $constraintViolationList = $this->validator->validate($request);
        $errors = ConstraintViolationListTransformer::transformToArray($constraintViolationList);

        if (\count($errors) > 0) {
            throw new RequestValidationException($errors);
        }
    }
}
