<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Domain;

use Symfony\Component\Uid\Uuid;

abstract class DomainEventBase implements DomainEventInterface
{
    private Uuid $id;

    private \DateTimeImmutable $occurredOn;

    public function __construct(?Uuid $id = null, ?\DateTimeImmutable $occurredOn = null)
    {
        $this->id = $id ?? Uuid::v4();
        $this->occurredOn = $occurredOn ?? new \DateTimeImmutable();
    }

    abstract public static function from(Uuid $id, \DateTimeImmutable $occurredOn, array $data): DomainEventInterface;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
