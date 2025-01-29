<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Infrastructure\Serializer\BaseId;

use Neuron\BuildingBlocks\Domain\BaseId;
use Neuron\BuildingBlocks\Infrastructure\Serializer\BaseId\BaseIdNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BaseIdNormalizerTest extends TestCase
{
    public function testThatSupportsBaseId(): void
    {
        $baseId = new BaseId('63957461-9332-434f-b1be-53058455c933');
        $objectNormalizer = $this->createStub(NormalizerInterface::class);

        $normalizer = new BaseIdNormalizer($objectNormalizer);

        $this->assertTrue($normalizer->supportsNormalization($baseId));
    }

    public function testThatDoesNotSupportNothingExceptBaseId(): void
    {
        $objectNormalizer = $this->createStub(NormalizerInterface::class);
        $normalizer = new BaseIdNormalizer($objectNormalizer);

        $this->assertFalse($normalizer->supportsNormalization(\stdClass::class));
    }

    public function testThatNormalizesDataCorrectly(): void
    {
        $baseId = new BaseId('63957461-9332-434f-b1be-53058455c933');
        $objectNormalizer = $this->createMock(NormalizerInterface::class);
        $objectNormalizer->expects($this->once())
            ->method('normalize')
            ->with($baseId)
            ->willReturn([
                'value' => '63957461-9332-434f-b1be-53058455c933',
            ]);

        $normalizer = new BaseIdNormalizer($objectNormalizer);

        $normalizedData = $normalizer->normalize($baseId);

        $this->assertEquals('63957461-9332-434f-b1be-53058455c933', $normalizedData['value']);
        $this->assertEquals($baseId::class, $normalizedData['type']);
    }
}
