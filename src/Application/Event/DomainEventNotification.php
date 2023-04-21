<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Application\Event;

#[\Attribute]
class DomainEventNotification
{
    public string $domainEvent;

    public function __construct(string $domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }
}
