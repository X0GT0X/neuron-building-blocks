<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Infrastructure\DomainEventsDispatching;

use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventNotificationNotFoundException;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventNotificationsResolver;
use PHPUnit\Framework\TestCase;

class DomainEventNotificationsResolverTest extends TestCase
{
    public function testThatReturnsNotificationTypeByDomainEvent(): void
    {
        $domainEventNotifications = [
            DomainEventNotificationStub::class,
        ];
        $domainEvent = new DomainEventStub();

        $notificationsResolver = new DomainEventNotificationsResolver($domainEventNotifications);

        $notificationType = $notificationsResolver->getNotificationTypeByDomainEvent($domainEvent);

        $this->assertEquals(DomainEventNotificationStub::class, $notificationType);
    }

    public function testThatThrowsExceptionWhenNotificationTypeNotFound(): void
    {
        $domainEvent = new DomainEventStub();

        $notificationsResolver = new DomainEventNotificationsResolver([]);

        $this->expectException(DomainEventNotificationNotFoundException::class);
        $this->expectExceptionMessage(\sprintf('Notification was not found for domain event %s', DomainEventStub::class));

        $notificationsResolver->getNotificationTypeByDomainEvent($domainEvent);
    }
}
