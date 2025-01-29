<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching;

use Neuron\BuildingBlocks\Application\Event\AbstractDomainEventNotification;
use Neuron\BuildingBlocks\Application\Event\DomainEventNotification;
use Neuron\BuildingBlocks\Domain\DomainEventInterface;

class DomainEventNotificationsResolver implements DomainEventNotificationsResolverInterface
{
    /** @var class-string<AbstractDomainEventNotification>[] */
    private array $domainEventNotifications = [];

    /**
     * @param class-string<AbstractDomainEventNotification>[] ...$domainEventNotifications
     */
    public function __construct(array ...$domainEventNotifications)
    {
        foreach ($domainEventNotifications as $notification) {
            \array_push($this->domainEventNotifications, ...$notification);
        }
    }

    /**
     * @throws \ReflectionException
     * @throws DomainEventNotificationNotFoundException
     */
    public function getNotificationTypeByDomainEvent(DomainEventInterface $domainEvent): string
    {
        foreach ($this->domainEventNotifications as $notification) {
            $reflection = new \ReflectionClass($notification);

            $domainEventNotificationAttribute = $reflection->getAttributes(DomainEventNotification::class)[0];

            if ($domainEventNotificationAttribute->getArguments()[0] === $domainEvent::class) {
                return $notification;
            }
        }

        throw new DomainEventNotificationNotFoundException($domainEvent);
    }
}
