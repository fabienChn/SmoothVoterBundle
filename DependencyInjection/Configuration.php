<?php

namespace fabienChn\SmoothVoterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('smooth_voter');

        $rootNode
            ->children()
                ->scalarNode('user_entity')->defaultValue('\App\Entity\User')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
