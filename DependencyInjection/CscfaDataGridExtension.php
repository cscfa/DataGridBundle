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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @category Bundle
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class CscfaDataGridExtension extends Extension
{

    /**
     * {@inheritdoc}
     * 
     * @param array            $configs   The extension configuration
     * @param ContainerBuilder $container The bundle ContainerBuilder
     * 
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        
        if (isset($config) && isset($config["template"])) {
            $template = $config["template"];
        } else {
            $template = null;
        }
        
        $container->setParameter('cscfa_datagrid_template', $template);
        
        if (isset($config) && isset($config["paginator_template"])) {
            $paginatorTemplate = $config["paginator_template"];
        } else {
            $paginatorTemplate = null;
        }
        
        $container->setParameter('cscfa_paginator_template', $paginatorTemplate);
        
        if (isset($config) && isset($config["paginator_limit_template"])) {
            $paginatorLimitTemplate = $config["paginator_limit_template"];
        } else {
            $paginatorLimitTemplate = null;
        }
        
        $container->setParameter('cscfa_paginator_limit_template', $paginatorLimitTemplate);
    }
}
