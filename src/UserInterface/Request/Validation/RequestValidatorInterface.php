<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\UserInterface\Request\Validation;

use Neuron\BuildingBlocks\UserInterface\Request\RequestInterface;

interface RequestValidatorInterface
{
    /**
     * @throws RequestValidationException
     */
    public function validate(RequestInterface $request): void;
}
