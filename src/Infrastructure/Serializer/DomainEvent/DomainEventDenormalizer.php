<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Serializer\DomainEvent;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Uid\Uuid;

final readonly class DomainEventDenormalizer implements DenormalizerInterface
{
    /**
     * @param array{type: string, id: string, occurredOn: string} $data
     *
     * @throws \DateMalformedStringException
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): DomainEventInterface
    {
        /** @var DomainEventInterface */
        return $data['type']::from(
            new Uuid($data['id']),
            new \DateTimeImmutable($data['occurredOn']),
            $data,
        );
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return \is_a($type, DomainEventInterface::class, true);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            DomainEventInterface::class => true,
        ];
    }
}
