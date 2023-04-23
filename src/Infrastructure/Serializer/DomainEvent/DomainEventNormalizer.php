<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Serializer\DomainEvent;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final readonly class DomainEventNormalizer implements NormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer
    ) {
    }

    /**
     * @param DomainEventInterface $object
     *
     * @throws ExceptionInterface
     *
     * @return array{id: string, occurredOn: string, type: string}
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        /** @var array{id: string, occurredOn: string} $data */
        $data = $this->normalizer->normalize($object);

        $data['type'] = $object::class;

        return $data;
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof DomainEventInterface;
    }
}
