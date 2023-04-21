<?php

namespace Neuron\BuildingBlocks\Tests\Domain;

use Neuron\BuildingBlocks\Domain\StringEnumTrait;
use PHPUnit\Framework\TestCase;

class StringEnumTraitTest extends TestCase
{
    public function testThatReturnsValidEnumValues(): void
    {
        $this->assertEquals(['VALUE', 'OTHER_VALUE'], SomeStringEnum::values());
    }
}

enum SomeStringEnum : string {
    use StringEnumTrait;

    case Value = 'VALUE';

    case OtherValue = 'OTHER_VALUE';
}
