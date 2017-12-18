<?php

namespace Vlabs\CmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Vlabs\CmsBundle\Form\CategoryEditType;
use Vlabs\CmsBundle\Form\CategoryNewType;
use Vlabs\CmsBundle\Form\PostEditType;
use Vlabs\CmsBundle\Form\PostNewType;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vlabs_cms');

        $rootNode
            ->children()
                ->scalarNode('category_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('post_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('tag_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('media_class')->cannotBeEmpty()->end()
                ->arrayNode('colors')->prototype('scalar')->end()
                ->scalarNode('new_category_type')->defaultValue(CategoryNewType::class)->end()
                ->scalarNode('edit_category_type')->defaultValue(CategoryEditType::class)->end()
                ->scalarNode('new_post_type')->defaultValue(PostNewType::class)->end()
                ->scalarNode('edit_post_type')->defaultValue(PostEditType::class)->end()
            ->end();

        return $treeBuilder;
    }
}
