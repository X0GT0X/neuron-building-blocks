<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Integration;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Uid\Uuid;

final readonly class IntegrationEventDenormalizer implements DenormalizerInterface
{
    public function __construct(
        private IntegrationEventMap $integrationEventMap
    ) {
    }

    /**
     * @param array{type: string, id: string, occurredOn: string, data: array<string, mixed>} $data
     *
     * @throws \DateMalformedStringException
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): IntegrationEvent
    {
        $eventClass = $this->integrationEventMap->getEventClassByEventType($data['type']);

        /** @var IntegrationEvent */
        return $eventClass::from(
            Uuid::fromString($data['id']),
            new \DateTimeImmutable($data['occurredOn']),
            $data['data']
        );
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return IntegrationEvent::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            IntegrationEvent::class => true,
        ];
    }
}
