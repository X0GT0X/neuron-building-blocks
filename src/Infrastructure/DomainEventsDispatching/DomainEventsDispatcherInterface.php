<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching;

interface DomainEventsDispatcherInterface
{
    public function dispatch(): void;
}
