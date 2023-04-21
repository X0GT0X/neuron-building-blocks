<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Integration;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface as TransportSerializerInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class IntegrationEventSerializer implements TransportSerializerInterface
{
    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    /**
     * @param array{headers: array{stamps: string}, body: string} $encodedEnvelope
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $headers = $encodedEnvelope['headers'];
        $event = $this->serializer->deserialize($encodedEnvelope['body'], SentIntegrationEventInterface::class, 'json');

        $stamps = \unserialize($headers['stamps']);

        return new Envelope($event, $stamps);
    }

    /**
     * @throws \Exception
     *
     * @return array{headers: array{stamps: string}, body: string}
     */
    public function encode(Envelope $envelope): array
    {
        $event = $envelope->getMessage();

        if (!$event instanceof IntegrationEvent) {
            throw new \Exception('Unsupported message class');
        }

        $allStamps = [];

        foreach ($envelope->all() as $stamps) {
            $allStamps = \array_merge($allStamps, $stamps);
        }

        return [
            'body' => $this->serializer->serialize($event, 'json'),
            'headers' => [
                'stamps' => \serialize($allStamps),
            ],
        ];
    }
}
