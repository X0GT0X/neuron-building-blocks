<?php

namespace Neuron\BuildingBlocks\Integration;

use Neuron\BuildingBlocks\Exception\ConfigurationException;

class IntegrationEventMap
{
    /**
     * @var array<string, string>
     */
    private array $map = [];

    /**
     * @throws ConfigurationException
     */
    public function addIntegrationEventMapping(string $eventType, string $eventClass): void
    {
        if (!\class_exists($eventClass)) {
            throw new ConfigurationException(\sprintf('Event class %s does not exist', $eventClass));
        }

        if (!\is_a($eventClass, IntegrationEvent::class)) {
            throw new ConfigurationException(\sprintf('Event class %s does not extend %s', $eventClass, IntegrationEvent::class));
        }

        $this->map[$eventType] = $eventClass;
    }

    /**
     * @return class-string
     */
    public function getEventClassByEventType(string $eventType): string
    {
        return $this->map[$eventType];
    }
}
