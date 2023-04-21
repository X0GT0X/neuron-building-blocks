<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Application\Event;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Symfony\Component\Uid\Uuid;

interface DomainEventNotificationInterface
{
    public function getId(): Uuid;

    public function getDomainEvent(): DomainEventInterface;
}
