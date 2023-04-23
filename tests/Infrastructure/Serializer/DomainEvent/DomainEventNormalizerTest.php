<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Infrastructure\Serializer\DomainEvent;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Neuron\BuildingBlocks\Infrastructure\Serializer\DomainEvent\DomainEventNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DomainEventNormalizerTest extends TestCase
{
    public function testThatSupportsDomainEventInterface(): void
    {
        $domainEvent = $this->createStub(DomainEventInterface::class);
        $objectNormalizer = $this->createStub(ObjectNormalizer::class);

        $normalizer = new DomainEventNormalizer($objectNormalizer);

        $this->assertTrue($normalizer->supportsNormalization($domainEvent));
    }

    public function testThatDoesNotSupportNothingExceptDomainEventInterface(): void
    {
        $objectNormalizer = $this->createStub(ObjectNormalizer::class);

        $normalizer = new DomainEventNormalizer($objectNormalizer);

        $this->assertFalse($normalizer->supportsNormalization(\stdClass::class));
    }

    public function testThatNormalizesDataCorrectly(): void
    {
        $domainEvent = $this->createStub(DomainEventInterface::class);
        $objectNormalizer = $this->createMock(ObjectNormalizer::class);
        $objectNormalizer->expects($this->once())
            ->method('normalize')
            ->with($domainEvent)
            ->willReturn([
                'id' => '63957461-9332-434f-b1be-53058455c933',
                'occurredOn' => '2023-04-16 15:06:11',
            ]);

        $normalizer = new DomainEventNormalizer($objectNormalizer);

        $normalizedData = $normalizer->normalize($domainEvent);

        $this->assertEquals('63957461-9332-434f-b1be-53058455c933', $normalizedData['id']);
        $this->assertEquals('2023-04-16 15:06:11', $normalizedData['occurredOn']);
        $this->assertEquals($domainEvent::class, $normalizedData['type']);
    }
}
