<?php

namespace Neuron\BuildingBlocks\Integration;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final readonly class IntegrationEventNormalizer implements NormalizerInterface
{
    public function __construct(private ObjectNormalizer $normalizer)
    {
    }

    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object);

        if ($object instanceof IntegrationEvent) {
            $data['type'] = $object::getEventType();
        }

        return $data;
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof IntegrationEvent;
    }
}
