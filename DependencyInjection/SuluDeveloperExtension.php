<?php
/*
 * This file is part of the Sulu.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\DeveloperBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class SuluDeveloperExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $extensions = $container->getExtensions();

        if (isset($extensions['sulu_core'])) {
            $prepend = array(
                'content' => array(
                    'structure' => array(
                        'paths' => array(
                            array(
                                'path' => __DIR__ . '/../Resources/config/snippets',
                                'type' => 'snippet',
                            ),
                            array(
                                'path' => __DIR__ . '/../Resources/config/pages',
                                'type' => 'page',
                            ),
                        ),
                    ),
                ),
            );

            $container->prependExtensionConfig('sulu_core', $prepend);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
