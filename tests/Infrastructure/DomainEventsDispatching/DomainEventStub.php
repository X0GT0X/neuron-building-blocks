<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Infrastructure\DomainEventsDispatching;

use Neuron\BuildingBlocks\Domain\DomainEventBase;
use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Uid\Uuid;

class DomainEventStub extends DomainEventBase
{
    public static function from(Uuid $id, \DateTimeImmutable $occurredOn, array $data): DomainEventInterface
    {
        return new self();
    }
}
