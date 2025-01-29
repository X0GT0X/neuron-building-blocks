<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Serializer\DomainEvent;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class DomainEventNormalizer implements NormalizerInterface
{
    public function __construct(
        private NormalizerInterface $normalizer
    ) {
    }

    /**
     * @param DomainEventInterface $data
     *
     * @throws ExceptionInterface
     *
     * @return array{id: string, occurredOn: string, type: string}
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        /** @var array{id: string, occurredOn: string} $result */
        $result = $this->normalizer->normalize($data);

        $result['type'] = $data::class;

        return $result;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof DomainEventInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            DomainEventInterface::class => true,
        ];
    }
}
