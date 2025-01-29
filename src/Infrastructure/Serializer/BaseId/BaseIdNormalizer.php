<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Serializer\BaseId;

use Neuron\BuildingBlocks\Domain\BaseId;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class BaseIdNormalizer implements NormalizerInterface
{
    public function __construct(
        private NormalizerInterface $normalizer
    ) {
    }

    /**
     * @param BaseId $data
     *
     * @throws ExceptionInterface
     *
     * @return array{value: string, type: string}
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        /** @var array{value: string} $result */
        $result = $this->normalizer->normalize($data);

        $result['type'] = $data::class;

        return $result;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof BaseId;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            BaseId::class => true,
        ];
    }
}
