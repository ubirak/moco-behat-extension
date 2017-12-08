<?php

namespace Ubirak\MocoBehatExtension;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class MocoExtension implements Extension
{
    public function load(ContainerBuilder $container, array $config)
    {
        $container->setParameter('ubirak.moco.json_file', $config['json_file']);
        $container->setParameter('ubirak.moco.port', $config['port']);
        $container->setParameter('ubirak.moco.hostname', $config['hostname']);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        $loader->load('services.xml');
    }

    public function getConfigKey()
    {
        return 'moco';
    }

    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('json_file')->end()
                ->scalarNode('hostname')->end()
                ->scalarNode('port')->end()
            ->end()
        ;
    }

    public function process(ContainerBuilder $container)
    {
    }

    public function initialize(ExtensionManager $extensionManager)
    {
    }
}
