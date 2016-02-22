<?php
/**
 * This file is a part of CSCFA datagrid project.
 * 
 * The datagrid project is a rendering project written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Interface
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\DataGridBundle\InterfaceOject;

use Cscfa\Bundle\DataGridBundle\Objects\DataGridStepper;

/**
 * StepperInterface interface.
 *
 * The StepperInterface implement
 * access method to DataGridStepper.
 *
 * @category Interface
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface StepperInterface
{

    /**
     * Get stepper
     * 
     * This methods allow to access 
     * to the datagrid stepper.
     * 
     * @return DataGridStepper
     */
    public function getStepper();

    /**
     * Set stepper
     *
     * This methods allow to set
     * the datagrid stepper.
     *
     * @param DataGridStepper $type the datagrid stepper
     * 
     * @return StepperInterface
     */
    public function setStepper(DataGridStepper $stepper);
    
    /**
     * Has stepper
     * 
     * This method validate the stepper
     * existance.
     * 
     * Return true if exist, elswhere,
     * return false.
     * 
     * @return boolean
     */
    public function hasStepper();
 
    /**
     * Get getProcessed
     * 
     * This methods allow to access 
     * to the steps processed datas.
     * 
     * @return integer
     */
    public function getProcessed();

    /**
     * Get header
     * 
     * This methods allow to access 
     * to the elements headers.
     * 
     * @return array
     */
    public function getHeader();
    
}
