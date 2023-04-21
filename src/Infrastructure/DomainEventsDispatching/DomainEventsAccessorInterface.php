<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\DomainEventDispatching;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;

interface DomainEventsAccessorInterface
{
    /**
     * @return DomainEventInterface[]
     */
    public function getAllDomainEvents(): array;

    public function clearAllDomainEvents(): void;
}
