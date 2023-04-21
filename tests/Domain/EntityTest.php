<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Domain;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Neuron\BuildingBlocks\Domain\Entity;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function testAddDomainEvent(): void
    {
        $entity = new Entity();
        $domainEvent = $this->createStub(DomainEventInterface::class);

        $entity->addDomainEvent($domainEvent);

        $this->assertCount(1, $entity->getDomainEvents());
        $this->assertEquals($domainEvent, $entity->getDomainEvents()[0]);
    }

    public function testClearDomainEvents(): void
    {
        $entity = new Entity();
        $entity->addDomainEvent($this->createStub(DomainEventInterface::class));

        $entity->clearDomainEvents();

        $this->assertCount(0, $entity->getDomainEvents());
    }
}
