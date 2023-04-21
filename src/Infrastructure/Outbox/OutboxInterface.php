<?php

namespace Neuron\BuildingBlocks\Infrastructure\Outbox;

interface OutboxInterface
{
    public function add(OutboxMessage $message): void;
}
