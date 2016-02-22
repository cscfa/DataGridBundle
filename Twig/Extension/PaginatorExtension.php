<?php
/**
 * This file is a part of CSCFA datagrid project.
 * 
 * The datagrid project is a symfony bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Twig extension
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\DataGridBundle\Twig\Extension;

use Cscfa\Bundle\DataGridBundle\Objects\DataGridPaginator;
use Cscfa\Bundle\DataGridBundle\Objects\DataGridStepper;
use Symfony\Component\Form\FormFactory;
use Cscfa\Bundle\DataGridBundle\Objects\PaginatorLimitForm;

/**
 * PaginatorExtension class.
 *
 * The PaginatorExtension class define
 * the twig extension to display
 * the CSManager paginator.
 *
 * @category Twig extension
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class PaginatorExtension extends \Twig_Extension
{

    /**
     * PaginatorExtension attribute
     * 
     * This attribute indicate the
     * template to use on rendering.
     * 
     * @var string
     */
    protected $template;

    /**
     * PaginatorExtension attribute
     * 
     * This attribute indicate the
     * template to use on limit 
     * selection rendering.
     * 
     * @var string
     */
    protected $limitTemplate;

    /**
     * PaginatorExtension attribute
     * 
     * This attribute register the
     * form factory service.
     * 
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * Set arguments
     * 
     * Service argument definition
     * 
     * @param string      $template      The template to use
     * @param string      $limitTemplate The template to use on limit selection
     * @param FormFactory $formFactory   The form factory service
     */
    public function setArguments($template, $limitTemplate, FormFactory $formFactory)
    {
        $this->template = $template;
        $this->limitTemplate = $limitTemplate;
        $this->formFactory = $formFactory;
    }

    /**
     * Get functions
     * 
     * Return the function definitions
     * of the current twig extension.
     * 
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('renderPaginator', array(
                $this,
                'renderPaginator'
            ), array(
                'is_safe' => array(
                    'html'
                ),
                'needs_environment' => true
            )),
            new \Twig_SimpleFunction('renderPaginatorLimit', array(
                $this,
                'renderPaginatorLimit'
            ), array(
                'is_safe' => array(
                    'html'
                ),
                'needs_environment' => true
            ))
        );
    }

    /**
     * Render paginator
     * 
     * This method render the paginator
     * with the given DataGridPaginator
     * given instance.
     * 
     * @param \Twig_Environment $twig      The twig environment
     * @param DataGridPaginator $paginator The paginator to render
     */
    public function renderPaginator(\Twig_Environment $twig, DataGridPaginator $paginator, $template = null, $limit = 0)
    {
        if (! $paginator->hasStepper()) {
            $paginator->setStepper(new DataGridStepper());
        }
        
        if ($template === null) {
            $template = $this->template;
        }
        
        if ($limit > 0) {
            
            $page = $paginator->getPage();
            $interval = intval(floor($limit / 2));
            
            $start = $page - $interval;
            if ($start > $paginator->getMaxPage()) {
                $start = $paginator->getMaxPage() - $interval;
            }
            if ($start < 1) {
                $interval += 1 - $start;
                $start = 1;
            }
            $end = $page + $interval;
            if ($end > $paginator->getMaxPage()) {
                $end = $paginator->getMaxPage();
            }
        } else {
            $start = 1;
            $end = $paginator->getMaxPage();
        }
        
        return $twig->render($template, array(
            "pager" => $paginator,
            "start" => $start,
            "end" => $end
        ));
    }

    /**
     * Render paginator limit
     * 
     * This method render the paginator
     * limit with the given DataGridPaginator
     * given instance.
     * 
     * @param \Twig_Environment $twig      The twig environment
     * @param DataGridPaginator $paginator The paginator to render
     */
    public function renderPaginatorLimit(\Twig_Environment $twig, DataGridPaginator $paginator, $template = null)
    {
        if (! $paginator->hasStepper()) {
            $paginator->setStepper(new DataGridStepper());
        }
        
        if ($template === null) {
            $template = $this->limitTemplate;
        }
        
        $plf = new PaginatorLimitForm();
        $plf->setLastLimit($paginator->getLimit());
        $plf->setPage($paginator->getPage());
        $plf->setAllowedLimits($paginator->getAllowedLimits());
        $createForm = $this->formFactory->create("paginatorLimit", $plf);
        
        return $twig->render($template, array(
            "pager" => $paginator,
            "form" => $createForm->createView()
        ));
    }

    /**
     * Get name
     * 
     * Return the current extension name.
     * 
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return "cscfa_paginator.extension";
    }
}