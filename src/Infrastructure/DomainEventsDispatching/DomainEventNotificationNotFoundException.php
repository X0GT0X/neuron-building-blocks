<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;

class DomainEventNotificationNotFoundException extends \Exception
{
    public function __construct(DomainEventInterface $domainEvent)
    {
        parent::__construct(\sprintf('Notification was not found for domain event %s', $domainEvent::class));
    }
}
