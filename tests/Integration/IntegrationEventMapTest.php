<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\Tests\Integration;

use Neuron\BuildingBlocks\Exception\ConfigurationException;
use Neuron\BuildingBlocks\Integration\IntegrationEvent;
use Neuron\BuildingBlocks\Integration\IntegrationEventMap;
use PHPUnit\Framework\TestCase;

class IntegrationEventMapTest extends TestCase
{
    public function testThatAddsIntegrationEventMapping(): void
    {
        $event = $this->createStub(IntegrationEvent::class);

        $integrationEventMap = new IntegrationEventMap();

        $integrationEventMap->addIntegrationEventMapping('Event', $event::class);

        $this->assertEquals($event::class, $integrationEventMap->getEventClassByEventType('Event'));
    }

    public function testThatThrowsConfigurationExceptionIfClassNotFound(): void
    {
        $integrationEventMap = new IntegrationEventMap();

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage(\sprintf('Event class %s does not exist', 'NonExistingEventClass'));

        $integrationEventMap->addIntegrationEventMapping('Event', 'NonExistingEventClass');
    }

    public function testThatThrowsConfigurationExceptionIfClassDoesNotExtendIntegrationEventClass(): void
    {
        $nonEventClass = \stdClass::class;

        $integrationEventMap = new IntegrationEventMap();

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage(\sprintf('Event class %s does not extend %s', $nonEventClass, IntegrationEvent::class));

        $integrationEventMap->addIntegrationEventMapping('Event', $nonEventClass);
    }
}
