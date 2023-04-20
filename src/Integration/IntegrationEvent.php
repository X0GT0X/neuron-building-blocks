<?php

namespace Neuron\BuildingBlocks\Integration;

use Symfony\Component\Uid\Uuid;

readonly abstract class IntegrationEvent
{
    public function __construct(
        private Uuid $id,
        private \DateTimeImmutable $occurredOn,
    ) {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    /**
     * @return array<string, mixed>
     */
    public abstract function getData(): array;


    /**
     * @param array<string, mixed> $data
     */
    public static abstract function from(Uuid $id, \DateTimeImmutable $occurredOn, array $data): self;

    public static abstract function getEventType(): string;
}
