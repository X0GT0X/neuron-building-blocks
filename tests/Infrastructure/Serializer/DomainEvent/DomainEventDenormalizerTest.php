<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Infrastructure\Serializer\DomainEvent;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Neuron\BuildingBlocks\Infrastructure\Serializer\DomainEvent\DomainEventDenormalizer;
use Neuron\BuildingBlocks\Tests\Stub\DomainEventStub;
use PHPUnit\Framework\TestCase;

class DomainEventDenormalizerTest extends TestCase
{
    public function testThatSupportsDomainEventInterface(): void
    {
        $denormalizer = new DomainEventDenormalizer();

        $this->assertTrue($denormalizer->supportsDenormalization([], DomainEventInterface::class));
    }

    public function testThatDoesNotSupportNothingExceptDomainEventInterface(): void
    {
        $denormalizer = new DomainEventDenormalizer();

        $this->assertFalse($denormalizer->supportsDenormalization([], \stdClass::class));
    }

    public function testThatDenormalizesDomainEventCorrectly(): void
    {
        $denormalizer = new DomainEventDenormalizer();

        $domainEvent = $denormalizer->denormalize([
            'id' => '63957461-9332-434f-b1be-53058455c933',
            'occurredOn' => '2023-04-16 15:06:11',
            'type' => DomainEventStub::class,
        ], DomainEventStub::class);

        $this->assertInstanceOf(DomainEventStub::class, $domainEvent);
        $this->assertEquals('63957461-9332-434f-b1be-53058455c933', (string) $domainEvent->getId());
        $this->assertEquals(new \DateTimeImmutable('2023-04-16 15:06:11'), $domainEvent->getOccurredOn());
    }
}
