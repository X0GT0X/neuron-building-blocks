<?php

use Symfony\Component\Uid\Uuid;

class IntegrationEvent
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        public Uuid $id,
        public DateTimeImmutable $occurredOn,
        public string $eventType,
        public array $data,
    ) {
    }
}
