<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\DomainEventDispatching;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;

interface DomainEventNotificationsResolverInterface
{
    /**
     * @throws DomainEventNotificationNotFoundException
     */
    public function getNotificationTypeByDomainEvent(DomainEventInterface $domainEvent): string;
}
