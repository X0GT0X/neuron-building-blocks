<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Infrastructure\Serializer\BaseId;

use Neuron\BuildingBlocks\Domain\BaseId;
use Neuron\BuildingBlocks\Infrastructure\Serializer\BaseId\BaseIdDenormalizer;
use PHPUnit\Framework\TestCase;

class BaseIdDenormalizerTest extends TestCase
{
    public function testThatSupportsBaseId(): void
    {
        $denormalizer = new BaseIdDenormalizer();

        $this->assertTrue($denormalizer->supportsDenormalization([], BaseId::class));
    }

    public function testThatDoesNotSupportNothingExceptBaseId(): void
    {
        $denormalizer = new BaseIdDenormalizer();

        $this->assertFalse($denormalizer->supportsDenormalization([], \stdClass::class));
    }

    public function testThatDenormalizesBaseIdCorrectly(): void
    {
        $denormalizer = new BaseIdDenormalizer();

        $baseId = $denormalizer->denormalize([
            'value' => '63957461-9332-434f-b1be-53058455c933',
            'type' => BaseId::class,
        ], BaseId::class);

        $this->assertEquals('63957461-9332-434f-b1be-53058455c933', (string) $baseId->getValue());
    }
}
