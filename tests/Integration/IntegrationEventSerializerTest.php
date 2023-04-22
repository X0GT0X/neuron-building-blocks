<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Integration;

use Neuron\BuildingBlocks\Integration\IntegrationEvent;
use Neuron\BuildingBlocks\Integration\IntegrationEventSerializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Serializer\SerializerInterface;

class IntegrationEventSerializerTest extends TestCase
{
    public function testDecode(): void
    {
        $encodedEnvelope = [
            'body' => 'serialized_body',
            'headers' => [
                'stamps' => \serialize([]),
            ],
        ];
        $event = $this->createStub(IntegrationEvent::class);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($encodedEnvelope['body'], IntegrationEvent::class, 'json')
            ->willReturn($event);

        $integrationEventSerializer = new IntegrationEventSerializer($serializer);

        $decodedEnvelope = $integrationEventSerializer->decode($encodedEnvelope);

        $this->assertEquals($event, $decodedEnvelope->getMessage());
    }

    public function testEncode(): void
    {
        $event = $this->createStub(IntegrationEvent::class);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('serialize')
            ->with($event, 'json')
            ->willReturn('serialized_body');

        $integrationEventSerializer = new IntegrationEventSerializer($serializer);

        $encodedEnvelope = $integrationEventSerializer->encode(new Envelope($event));

        $this->assertEquals('serialized_body', $encodedEnvelope['body']);
        $this->assertEquals(\serialize([]), $encodedEnvelope['headers']['stamps']);
    }

    public function testThatEncodeThrowsExceptionIfEventIsNotInstanceOfIntegrationEvent(): void
    {
        $serializer = $this->createStub(SerializerInterface::class);

        $integrationEventSerializer = new IntegrationEventSerializer($serializer);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(\sprintf('Unsupported message class \'%s\'', \stdClass::class));

        $integrationEventSerializer->encode(new Envelope(new \stdClass()));
    }
}
