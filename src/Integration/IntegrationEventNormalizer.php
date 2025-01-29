<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Integration;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class IntegrationEventNormalizer implements NormalizerInterface
{
    public function __construct(
        private NormalizerInterface $normalizer
    ) {
    }

    /**
     * @param IntegrationEvent $data
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
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        /** @var array{id: string, occurredOn: string, data: string} $result */
        $result = $this->normalizer->normalize($data);

        $result['type'] = $data::getEventType();

        return $result;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof IntegrationEvent;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            IntegrationEvent::class => true,
        ];
    }
}
