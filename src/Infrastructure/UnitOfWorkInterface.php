<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure;

interface UnitOfWorkInterface
{
    public function commit(): void;
}
