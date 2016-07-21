<?php

namespace Mill\DemoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mill_demo_extension');

	$rootNode
		->children()
			->arrayNode('cookies')
				->prototype('scalar')->end()
				->defaultValue(['name', 'ip', 'expiration'])
			->end()
			->scalarNode('encryptor')
				->defaultValue('aes128')
			->end()
			->scalarNode('password')
				->defaultVAlue('s3cr3tpassword')
			->end()
			->scalarNode('iv')
				->defaultValue('1234567891234567')
			->end()
		->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
