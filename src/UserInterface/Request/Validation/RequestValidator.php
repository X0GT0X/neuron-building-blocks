<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\UserInterface\Request\Validation;

use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
        $constraintViolations = $this->transformConstraintViolationListToArray($constraintViolationList);

        if (\count($constraintViolations) > 0) {
            $errors = \array_map(static fn (ConstraintViolationInterface $constraintViolation) => [
                'property' => $constraintViolation->getPropertyPath(),
                'message' => $constraintViolation->getMessage(),
            ], $constraintViolations);

            throw new RequestValidationException($errors);
        }
    }

    /**
     * @return ConstraintViolationInterface[]
     */
    private function transformConstraintViolationListToArray(ConstraintViolationListInterface $violationList): array
    {
        $violations = [];

        foreach ($violationList as $violation) {
            $violations[] = $violation;
        }

        return $violations;
    }
}
