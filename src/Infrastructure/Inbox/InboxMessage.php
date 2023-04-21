<?php

namespace Neuron\Infrastructure\Configuration\Inbox;

use Symfony\Component\Uid\Uuid;

readonly class InboxMessage
{
    public function __construct(
        public Uuid $id,
        public \DateTimeImmutable $occurredOn,
        public string $data,
    ) {
    }
}
