<?php
/**
 * This file is a part of CSCFA datagrid project.
 * 
 * The datagrid project is a rendering project written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Object
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\DataGridBundle\Objects;

use Cscfa\Bundle\DataGridBundle\InterfaceOject\StepperInterface;
/**
 * DataGridStepper class.
 *
 * The DataGridStepper implement
 * access method to datagrid render
 * steps.
 *
 * @category Object
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class DataGridStepper
{
    
    /**
     * DataGridStepper attribute
     * 
     * This attribute contain a DataGridContainer
     * instance known as the parent datagrid
     * data container.
     * 
     * @var DataGridContainer
     */
    private $parent;
    
    /**
     * DataGridStepper attribute
     * 
     * This attribute contain an array
     * to store all of the registered
     * callbacks.
     * 
     * @var array
     */
    protected $callbacks;
    
    /**
     * Object constructor
     * 
     * The default object constructor
     * 
     * @param StepperInterface $parent The current stepper parent
     */
    public function __construct(StepperInterface $parent = null)
    {
        if ($parent !== null) {
            $parent->setStepper($this);
        }
    }

    /**
     * Get parent
     * 
     * Return the current stepper
     * parent.
     * 
     * @return StepperInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     * 
     * Set the current stepper
     * parent.
     * 
     * @param StepperInterface $parent the parent
     * 
     * @return DataGridStepper
     */
    public function setParent(StepperInterface $parent)
    {
        $this->parent = $parent;
        
        return $this;
    }
    
    /**
     * Add callbacks
     * 
     * Add a callback to use
     * into the datagrid rendering.
     * 
     * Note : view the template
     * definition to get the existants
     * callbacks.
     * 
     * @param string   $name    the callback name
     * @param \Closure $closure the callback
     * @param boolean  $isSafe  the rendering safe state
     */
    public function addCallback($name, \Closure $closure, $isSafe = false, $data = null)
    {
        $this->callbacks[$name] = array($closure, $isSafe, $data);
    }
    
    /**
     * Is safe
     * 
     * Check if a callback result
     * is safe rendered.
     * 
     * @param string $name The callback name
     * 
     * @return boolean
     */
    public function isSafe($name)
    {
        if (isset($this->callbacks[$name])) {
            return $this->callbacks[$name][1];
        } else {
            return false;
        }
    }
    
    /**
     * Call
     * 
     * This method call the callback
     * and return it's result.
     * 
     * @param string $name  The callback name
     * @param string $index The process index
     * 
     * @throws \Exception If the index doesn't exist
     */
    public function call($name, $index = null, $data = array())
    {
        $process = $this->parent->getProcessed();
        $type = $process["type"];
        $row = null;
        $element = null;
        $header = null;
        
        if ($index !== null) {
            if (strpos($index, ":") !== false) {
                list($index, $element) = explode(":", $index);
                
                $header = $this->parent->getHeader()[$element];
            }
            
            if (isset($process[$index])) {
                $row = $process[$index];
            } else {
                throw new \Exception(sprintf("The index '%s' doesn't exist", $index), 500);
            }
        }
        if (isset($this->callbacks[$name]) && is_callable($this->callbacks[$name][0])) {
            
            if (is_array($this->callbacks[$name][2])) {
                $this->callbacks[$name][2]["index"] = $index;
                $this->callbacks[$name][2]["element"] = $element;
                $this->callbacks[$name][2]["header"] = $header;
                $this->callbacks[$name][2]["stepper"] = $this;
            } else {
                $this->callbacks[$name][2] = array(
                    "index"=>$index, 
                    "element"=>$element, 
                    "header"=>$header, 
                    "stepper"=>$this
                );
            }
            
            $additionalData = array_merge($this->callbacks[$name][2], $data);
            
            return $this->callbacks[$name][0]($type, $process, $row, $additionalData);
        }
    }
 
}
