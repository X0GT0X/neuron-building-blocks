<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Integration;

use Symfony\Component\Uid\Uuid;

abstract class IntegrationEvent
{
    public function __construct(
        private readonly Uuid $id,
        private readonly \DateTimeImmutable $occurredOn,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function getData(): array;

    /**
     * @param array<string, mixed> $data
     */
    abstract public static function from(Uuid $id, \DateTimeImmutable $occurredOn, array $data): self;

    abstract public static function getEventType(): string;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
