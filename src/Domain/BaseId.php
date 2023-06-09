<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Domain;

use Symfony\Component\Uid\Uuid;

class BaseId implements \Stringable
{
    private Uuid $value;

    public function __construct(string $value)
    {
        $this->value = Uuid::fromString($value);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function getValue(): Uuid
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value->equals($other->value);
    }
}
