<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Outbox;

interface OutboxInterface
{
    public function add(OutboxMessage $message): void;
}
