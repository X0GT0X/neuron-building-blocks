<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Infrastructure\Outbox;

use Symfony\Component\Uid\Uuid;

class OutboxMessage
{
    public function __construct(
        public Uuid $id,
        public \DateTimeImmutable $occurredOn,
        public string $type,
        public string $data,
    ) {
    }
}
