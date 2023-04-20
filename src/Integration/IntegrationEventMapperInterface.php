<?php

namespace Neuron\BuildingBlocks\Integration;

interface IntegrationEventMapperInterface
{
    /**
     * @param string $eventType
     * @return class-string
     */
    public function mapEventTypeToEventClass(string $eventType): string;
}
