<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Domain;

trait StringEnumTrait
{
    /**
     * @return string[]
     */
    public static function values(): array
    {
        return \array_column(self::cases(), 'value');
    }
}
