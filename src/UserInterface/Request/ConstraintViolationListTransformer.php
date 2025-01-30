<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\UserInterface\Request;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationListTransformer
{
    /**
     * @return array<string|int, array<string, string|null>>|array<int, array<string, mixed>>|array<string, string|null> $errors
     */
    public static function transformToArray(ConstraintViolationListInterface $constraintViolationList): array
    {
        $constraintViolations = self::transformConstraintViolationListToArray($constraintViolationList);

        return \array_map(static fn (ConstraintViolationInterface $constraintViolation) => [
            'property' => $constraintViolation->getPropertyPath(),
            'message' => $constraintViolation->getMessage(),
        ], $constraintViolations);
    }

    /**
     * @return ConstraintViolationInterface[]
     */
    private static function transformConstraintViolationListToArray(ConstraintViolationListInterface $violationList): array
    {
        $violations = [];

        foreach ($violationList as $violation) {
            $violations[] = $violation;
        }

        return $violations;
    }
}
