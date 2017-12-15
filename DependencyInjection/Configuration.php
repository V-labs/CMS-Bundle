<?php

namespace Vlabs\CmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
            ->end();

        $this->addNewCategorySection($rootNode);
        $this->addEditCategorySection($rootNode);
        $this->addNewPostSection($rootNode);
        $this->addEditPostSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addNewCategorySection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('form_category_new')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')->defaultValue(CategoryNewType::class)->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addEditCategorySection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('form_category_edit')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')->defaultValue(CategoryEditType::class)->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addNewPostSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('form_post_new')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')->defaultValue(PostNewType::class)->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addEditPostSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('form_post_edit')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('type')->defaultValue(PostEditType::class)->end()
                    ->end()
                ->end()
            ->end();
    }

}
