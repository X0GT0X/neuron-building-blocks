<?php

namespace Neuron\BuildingBlocks\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const NAME = 'neuron_building_blocks';

    public const INTEGRATION_EVENTS_MAP = 'integration_events_map';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::NAME);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->arrayNode(self::INTEGRATION_EVENTS_MAP)
            ->info('Associative array: event type => event class name.')
            ->useAttributeAsKey('name')
            ->normalizeKeys(false)
            ->variablePrototype()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}