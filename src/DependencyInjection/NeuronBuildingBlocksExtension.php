<?php

declare(strict_types=1);

namespace Neuron\BuildingBlocks\DependencyInjection;

use Neuron\BuildingBlocks\Integration\IntegrationEventMap;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class NeuronBuildingBlocksExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(IntegrationEventMap::class);

        foreach ($config[Configuration::INTEGRATION_EVENTS_MAP] ?? [] as $eventType => $eventClass) {
            $definition->addMethodCall('addIntegrationEventMapping', [$eventType, $eventClass]);
        }
    }
}
