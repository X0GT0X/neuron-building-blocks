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

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        $eventClass = $this->integrationEventMap->getEventClassByEventType($data['type']);

        return $eventClass::from(
            Uuid::fromString($data['id']),
            new \DateTimeImmutable($data['occurredOn']),
            $data['data']
        );
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return IntegrationEvent::class === $type;
    }
}
