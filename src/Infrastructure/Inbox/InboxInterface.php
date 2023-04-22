<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Inbox;

interface InboxInterface
{
    public function add(InboxMessage $message): void;
}
