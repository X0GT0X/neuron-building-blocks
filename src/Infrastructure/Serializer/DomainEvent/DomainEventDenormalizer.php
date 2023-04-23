<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Serializer\DomainEvent;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Uid\Uuid;

final readonly class DomainEventDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        return $data['type']::from(
            new Uuid($data['id']),
            new \DateTimeImmutable($data['occurredOn']),
            $data,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null)
    {
        return \is_a($type, DomainEventInterface::class, true);
    }
}
