<?php

namespace Neuron\BuildingBlocks\Integration;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class IntegrationEventDenormalizer implements DenormalizerInterface
{
    public function __construct(private IntegrationEventMapperInterface $integrationEventMapper)
    {
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $eventClass = $this->integrationEventMapper->mapEventTypeToEventClass($data['type']);

        return $eventClass::from(
            $data['id'],
            $data['occurredOn'],
            $data['data']
        );
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null)
    {
        return $type === IntegrationEvent::class;
    }
}
