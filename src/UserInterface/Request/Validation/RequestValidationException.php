<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\UserInterface\Request\Validation;

class RequestValidationException extends \Exception
{
    private const MESSAGE = 'Request validation exception';

    /**
     * @param array<string|int, array<string, string|null>>|array<int, array<string, mixed>>|array<string, string|null> $errors
     */
    public function __construct(
        private readonly array $errors
    ) {
        parent::__construct(self::MESSAGE);
    }

    /**
     * @return array<string|int, array<string, string|null>>|array<int, array<string, mixed>>|array<string, string|null>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
