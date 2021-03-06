<?php
/**
 * This file is a part of CSCFA datagrid project.
 * 
 * The datagrid project is a rendering bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Bundle
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\DataGridBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @category Bundle
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     * 
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        
        $rootNode = $treeBuilder->root('cscfa_data_grid');
        
        $rootNode->children()
            ->scalarNode("template")->defaultValue("CscfaDataGridBundle:Default:datagrid.html.twig")->end()
            ->end()
            ->children()
            ->scalarNode("paginator_template")->defaultValue("CscfaDataGridBundle:Default:paginatorPageSelector.html.twig")->end()
            ->end()
            ->children()
            ->scalarNode("paginator_limit_template")->defaultValue("CscfaDataGridBundle:Default:paginatorLimit.html.twig")->end()
            ->end();
        
        return $treeBuilder;
    }
}
