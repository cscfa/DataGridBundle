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

use Cscfa\Bundle\DataGridBundle\Objects\DataGridContainer;
use Cscfa\Bundle\DataGridBundle\Objects\DataGridStepper;

/**
 * DatagridExtension class.
 *
 * The DatagridExtension class define
 * the twig extension to display
 * the CSManager datagrid.
 *
 * @category Twig extension
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class DatagridExtension extends \Twig_Extension
{

    /**
     * DatagridExtension attribute
     * 
     * This attribute indicate the
     * template to use on rendering.
     * 
     * @var string
     */
    protected $template;

    /**
     * Set arguments
     * 
     * Service argument definition
     * 
     * @param string $template The template to use
     */
    public function setArguments($template)
    {
        $this->template = $template;
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
            new \Twig_SimpleFunction('renderDatagrid', array(
                $this,
                'renderDatagrid'
            ), array(
                'is_safe' => array(
                    'html'
                ),
                'needs_environment' => true
            )),
            new \Twig_SimpleFunction('datagc', array(
                $this,
                'renderCallback'
            ), array(
                'is_safe' => array(
                    'html'
                )
            ))
        );
    }

    /**
     * Render datagrid
     * 
     * This method render the datagrid
     * with the given DataGridContainer
     * given instance.
     * 
     * @param \Twig_Environment $twig The twig environment
     * @param DataGridContainer $data The data to render
     * 
     * @throws \Exception if the header validity is on an error state
     */
    public function renderDatagrid(\Twig_Environment $twig, DataGridContainer $data, $template = null)
    {
        if (! $data->validHeaders()) {
            throw new \Exception("The headers count must be equals to the access methods count", 500);
        }
        
        if (! $data->hasStepper()) {
            $data->setStepper(new DataGridStepper());
        }
        
        if ($template === null) {
            $template = $this->template;
        }
        
        return $twig->render($template, array(
            "data" => $data
        ));
    }

    /**
     * Render callbacks
     * 
     * This method allow to render
     * the stepper callbacks.
     * 
     * @param string            $name    The callback name
     * @param string            $index   The step index
     * @param DataGridStepper   $stepper The stepper to use
     * 
     * @return string
     */
    public function renderCallback($name, $index = null, DataGridStepper $stepper = null)
    {
        if ($stepper === null) {
            if ($this->stepper === null) {
                throw new \Exception("Stepper must be registered first or given as argument", 500);
            }
            $stepper = $this->stepper;
        }
        
        if (! $stepper->isSafe($name)) {
            return htmlentities(html_entity_decode(($stepper->call($name, $index))));
        } else {
            return $stepper->call($name, $index);
        }
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
        return "cscfa_datagrid.extension";
    }
}
