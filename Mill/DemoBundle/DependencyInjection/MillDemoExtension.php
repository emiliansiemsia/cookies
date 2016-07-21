<?php

namespace Mill\DemoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class MillDemoExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

	$container->setParameter('mill_demo_extension.cookies', $config['cookies']);
	$container->setParameter('mill_demo_extension.encryptor', $config['encryptor']);
	$container->setParameter('mill_demo_extension.password', $config['password']);
	$container->setParameter('mill_demo_extension.iv', $config['iv']);
	
        $listener = new Definition(
            'Mill\DemoBundle\EventListener\CookiesListener',
            ['%mill_demo_extension.cookies%', '%mill_demo_extension.encryptor%', '%mill_demo_extension.password%', '%mill_demo_extension.iv%']
        );

        $listener->addTag('kernel.event_listener', ['event' => 'kernel.request', 'method' => 'onKernelRequest', 'priority' => 10000]);
        $listener->addTag('kernel.event_listener', ['event' => 'kernel.response', 'method' => 'onKernelResponse', 'priority' => -10000]);
        $container->setDefinition('mill_demo_extension', $listener);
    }
}
