<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\DomainEventDispatching;

interface DomainEventsDispatcherInterface
{
    public function dispatch(): void;
}
