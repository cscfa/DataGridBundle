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
 * DataGridPaginator class.
 *
 * The DataGridPaginator implement
 * pagination to datagrid array.
 *
 * @category Object
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class DataGridPaginator implements StepperInterface
{
    
    /**
     * DataGridPaginator Attribute
     * 
     * This attribute indicate
     * the requested page.
     * 
     * @var integer
     */
    protected $page;

    /**
     * DataGridPaginator Attribute
     *
     * This attribute indicate
     * the data limit per page.
     *
     * @var integer
     */
    protected $limit;

    /**
     * DataGridPaginator Attribute
     *
     * This attribute store
     * the datas to display.
     *
     * @var array
     */
    protected $datas;

    /**
     * DataGridPaginator Attribute
     *
     * This attribute store
     * the datas into separated
     * arrays.
     *
     * @var array
     */
    protected $pagedDatas;
    
    /**
     * DataGridPaginator attribute
     * 
     * This attribute contain the 
     * paginator stepper.
     * 
     * @var DataGridStepper
     */
    protected $stepper;
    
    /**
     * DataGridPaginator attribute
     * 
     * This attribute contain the 
     * paginator limits allowed
     * as array.
     * 
     * @var array
     */
    protected $allowedLimits;
    
    /**
     * DataGridPaginator constructor
     * 
     * The default constructor.
     * 
     * @param array   $datas The array of data to store
     * @param integer $page  The requested page
     * @param integer $limit The objects per page
     */
    public function __construct($datas = array(), $page = 0, $limit = 0)
    {
        $this->datas = $datas;
        
        $this->page = $page;
        
        $this->limit = $limit;
        
        $this->pagedDatas = array();
        
        $this->pageDatas();
    }

    /**
     * Get page
     * 
     * This method return the
     * requested page.
     * 
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set page
     * 
     * This method allow to
     * set the requested page.
     * 
     * @param integer $page  The page number
     * 
     * @return DataGridPaginator
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Get limit
     * 
     * This method return the
     * object limit per page.
     * 
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set limit
     * 
     * This method allow to
     * set the object limit 
     * per page.
     * 
     * @param integer $limit  The object limit per page
     * 
     * @return DataGridPaginator
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        $this->pageDatas();
        return $this;
    }

    /**
     * Get datas
     * 
     * This method return the
     * stored datas.
     * 
     * @return array
     */
    public function getDatas()
    {
        return $this->datas;
    }

    /**
     * Set datas
     * 
     * This method allow to
     * set the stored datas.
     * 
     * @param array $datas  The stored datas
     * 
     * @return DataGridPaginator
     */
    public function setDatas(array $datas)
    {
        $this->datas = $datas;
        $this->pageDatas();
        return $this;
    }

    /**
     * Get allowed limits
     * 
     * This method return the
     * data limits allowed
     * to be displayed.
     * 
     * @return array
     */
    public function getAllowedLimits()
    {
        $array = $this->allowedLimits;
        sort($array);
        return $array;
    }

    /**
     * Set allowed limits
     * 
     * This method allow to set
     * the data limits allowed
     * to be displayed.
     * 
     * @param array $allowedLimits The allowed limits
     * 
     * @return DataGridPaginator
     */
    public function setAllowedLimits(array $allowedLimits)
    {
        $this->allowedLimits = $allowedLimits;
        return $this;
    }
 
    /**
     * Get stepper
     * 
     * This methods allow to access 
     * to the datagrid stepper.
     * 
     * @return DataGridStepper
     */
    public function getStepper()
    {
        return $this->stepper;
    }

    /**
     * Set stepper
     *
     * This methods allow to set
     * the datagrid stepper.
     *
     * @param DataGridStepper $type the datagrid stepper
     * 
     * @return DataGridPaginator
     */
    public function setStepper(DataGridStepper $stepper)
    {
        $this->stepper = $stepper->setParent($this);
        return $this;
    }
    
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
    public function hasStepper()
    {
        return ($this->stepper !== null);
    }
 
    /**
     * Get getProcessed
     * 
     * This methods allow to access 
     * to the steps processed datas.
     * 
     * @return array
     */
    public function getProcessed(){
        return array_merge(array("type"=>1), $this->pagedDatas);
    }

    /**
     * Get header
     * 
     * This methods allow to access 
     * to the elements headers.
     * 
     * @return array
     */
    public function getHeader()
    {
        return array_keys($this->pagedDatas);
    }
 
    /**
     * Page datas
     * 
     * This method process the
     * data pagination.
     * 
     * @return void
     */
    protected function pageDatas()
    {
        if ($this->limit > 0 && !empty($this->datas)) {
            $this->pagedDatas = array_chunk($this->datas, $this->limit);
        } else {
            $this->pagedDatas = array();
        }
    }
    
    /**
     * Page isset
     * 
     * This method check if the 
     * requested page exist and 
     * return the result of the test.
     * 
     * @return boolean
     */
    public function pageIsset()
    {
        return ($this->page > 0 && $this->page <= $this->getMaxPage());
    }
    
    /**
     * Get max page
     * 
     * This method return the
     * maximum amount of page
     * that allow the stored
     * data count.
     * 
     * @return integer
     */
    public function getMaxPage()
    {
        return count($this->pagedDatas);
    }
    
    /**
     * Get page data
     * 
     * This method return the
     * datas of the requested 
     * page.
     * 
     * @return array
     */
    public function getPageData()
    {
        if (!$this->pageIsset()) {
            return array();
        } else {
            if (isset($this->pagedDatas[$this->page - 1])) {
                return $this->pagedDatas[$this->page - 1];
            } else {
                return array();
            }
        }
    }

}