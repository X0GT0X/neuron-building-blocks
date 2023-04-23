<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Domain;

use Symfony\Component\Uid\Uuid;

interface DomainEventInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public static function from(Uuid $id, \DateTimeImmutable $occurredOn, array $data): self;

    public function getId(): Uuid;

    public function getOccurredOn(): \DateTimeImmutable;
}
