<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Serializer\BaseId;

use Neuron\BuildingBlocks\Domain\BaseId;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class BaseIdDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        return new $data['type']($data['value']);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return \is_a($type, BaseId::class, true);
    }
}
