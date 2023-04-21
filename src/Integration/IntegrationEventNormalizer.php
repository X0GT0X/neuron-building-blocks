<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Integration;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final readonly class IntegrationEventNormalizer implements NormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer
    ) {
    }

    /**
     * @param IntegrationEvent $object
     *
     * @throws ExceptionInterface
     *
     * @return array{
     * id: string,
     * occurredOn: string,
     * type: string,
     * data: string,
     * }
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        /** @var array{id: string, occurredOn: string, data: string} $data */
        $data = $this->normalizer->normalize($object);

        $data['type'] = $object::getEventType();

        return $data;
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof IntegrationEvent;
    }
}
