<?php

declare(strict_types=1);

namespace Neuron\Infrastructure\Configuration\Inbox;

interface InboxInterface
{
    public function add(InboxMessage $message): void;
}
