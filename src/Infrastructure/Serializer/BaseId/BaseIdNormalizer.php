<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Serializer\BaseId;

use Neuron\BuildingBlocks\Domain\BaseId;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final readonly class BaseIdNormalizer implements NormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer
    ) {
    }

    /**
     * @param BaseId $object
     *
     * @throws ExceptionInterface
     *
     * @return array{value: string, type: string}
     */
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        /** @var array{value: string} $data */
        $data = $this->normalizer->normalize($object);

        $data['type'] = $object::class;

        return $data;
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof BaseId;
    }
}
