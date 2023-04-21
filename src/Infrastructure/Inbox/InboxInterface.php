<?php

namespace Neuron\Infrastructure\Configuration\Inbox;

interface InboxInterface
{
    public function add(InboxMessage $message): void;
}
