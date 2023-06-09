<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Stub;

use Neuron\BuildingBlocks\Application\Event\AbstractDomainEventNotification;
use Neuron\BuildingBlocks\Application\Event\DomainEventNotification;

#[DomainEventNotification(DomainEventStub::class)]
readonly class DomainEventNotificationStub extends AbstractDomainEventNotification
{
}
