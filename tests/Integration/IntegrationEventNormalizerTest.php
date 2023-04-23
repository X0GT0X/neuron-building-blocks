<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Integration;

use Neuron\BuildingBlocks\Integration\IntegrationEvent;
use Neuron\BuildingBlocks\Integration\IntegrationEventNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Uid\Uuid;

class IntegrationEventNormalizerTest extends TestCase
{
    private IntegrationEvent $event;

    public function testThatSupportsIntegrationEvent(): void
    {
        $event = $this->createStub(IntegrationEvent::class);
        $objectNormalizer = $this->createStub(ObjectNormalizer::class);

        $normalizer = new IntegrationEventNormalizer($objectNormalizer);

        $this->assertTrue($normalizer->supportsNormalization($event));
    }

    public function testThatDoesNotSupportNothingExceptIntegrationEvent(): void
    {
        $objectNormalizer = $this->createStub(ObjectNormalizer::class);

        $normalizer = new IntegrationEventNormalizer($objectNormalizer);

        $this->assertFalse($normalizer->supportsNormalization(new \stdClass()));
    }

    public function testThatNormalizesDataCorrectly(): void
    {
        $objectNormalizer = $this->createMock(ObjectNormalizer::class);
        $objectNormalizer->expects($this->once())
            ->method('normalize')
            ->with($this->event)
            ->willReturn([
                'id' => '4a50f0ad-84b6-4758-a631-44b1511898ca',
                'occurredOn' => '2023-04-16 15:06:11',
                'data' => ['someParam' => 'someValue'],
            ]);

        $normalizer = new IntegrationEventNormalizer($objectNormalizer);

        $normalizedData = $normalizer->normalize($this->event);

        $this->assertEquals('4a50f0ad-84b6-4758-a631-44b1511898ca', $normalizedData['id']);
        $this->assertEquals('2023-04-16 15:06:11', $normalizedData['occurredOn']);
        $this->assertEquals(['someParam' => 'someValue'], $normalizedData['data']);
        $this->assertEquals('Event', $normalizedData['type']);
    }

    protected function setUp(): void
    {
        $this->event = new class(Uuid::fromString('4a50f0ad-84b6-4758-a631-44b1511898ca'), new \DateTimeImmutable('2023-04-16 15:06:11')) extends IntegrationEvent {
            public function getData(): array
            {
                return [];
            }

            public static function from(Uuid $id, \DateTimeImmutable $occurredOn, array $data): IntegrationEvent
            {
                return new self($id, $occurredOn);
            }

            public static function getEventType(): string
            {
                return 'Event';
            }
        };
    }
}
