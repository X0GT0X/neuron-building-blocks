<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Integration;

use Neuron\BuildingBlocks\Integration\IntegrationEvent;
use Neuron\BuildingBlocks\Integration\IntegrationEventDenormalizer;
use Neuron\BuildingBlocks\Integration\IntegrationEventMap;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class IntegrationEventDenormalizerTest extends TestCase
{
    private IntegrationEvent $event;

    public function testThatSupportsIntegrationEvent(): void
    {
        $integrationEventMap = $this->createStub(IntegrationEventMap::class);

        $denormalizer = new IntegrationEventDenormalizer($integrationEventMap);

        $this->assertTrue($denormalizer->supportsDenormalization([], IntegrationEvent::class));
    }

    public function testThatDoesNotSupportNothingExceptIntegrationEvent(): void
    {
        $integrationEventMap = $this->createStub(IntegrationEventMap::class);

        $denormalizer = new IntegrationEventDenormalizer($integrationEventMap);

        $this->assertFalse($denormalizer->supportsDenormalization([], \stdClass::class));
    }

    public function testThatDenormalizesIntegrationEventCorrectly(): void
    {
        $integrationEventMap = $this->createMock(IntegrationEventMap::class);
        $integrationEventMap->expects($this->once())
            ->method('getEventClassByEventType')
            ->with('Event')
            ->willReturn($this->event::class);

        $denormalizer = new IntegrationEventDenormalizer($integrationEventMap);

        $normalizedData = [
            'id' => '4a50f0ad-84b6-4758-a631-44b1511898ca',
            'occurredOn' => '2023-04-16 15:06:11',
            'data' => ['someParam' => 'someValue'],
            'type' => 'Event',
        ];

        /** @var IntegrationEvent $event */
        $event = $denormalizer->denormalize($normalizedData, IntegrationEvent::class);

        $this->assertEquals($this->event->getId(), $event->getId());
        $this->assertEquals($this->event->getOccurredOn(), $event->getOccurredOn());
        $this->assertEquals($this->event->getData(), $event->getData());
    }

    protected function setUp(): void
    {
        $this->event = new class(Uuid::fromString('4a50f0ad-84b6-4758-a631-44b1511898ca'), new \DateTimeImmutable('2023-04-16 15:06:11'), 'someValue') extends IntegrationEvent {
            public function __construct(
                Uuid $id,
                \DateTimeImmutable $occurredOn,
                private readonly string $someParam
            ) {
                parent::__construct($id, $occurredOn);
            }

            public function getData(): array
            {
                return [
                    'someParam' => $this->someParam,
                ];
            }

            public static function from(Uuid $id, \DateTimeImmutable $occurredOn, array $data): IntegrationEvent
            {
                return new self($id, $occurredOn, $data['someParam']);
            }

            public static function getEventType(): string
            {
                return 'Event';
            }
        };
    }
}
