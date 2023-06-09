<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Application\Event;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Uid\Uuid;

abstract readonly class AbstractDomainEventNotification implements DomainEventNotificationInterface
{
    public function __construct(
        private Uuid $id,
        private DomainEventInterface $domainEvent,
    ) {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getDomainEvent(): DomainEventInterface
    {
        return $this->domainEvent;
    }
}
