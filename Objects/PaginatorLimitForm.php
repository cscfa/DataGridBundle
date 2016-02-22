<?php
/**
 * This file is a part of CSCFA datagrid project.
 * 
 * The datagrid project is a symfony bundle written in php
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

use Cscfa\Bundle\DataGridBundle\Form\Type\PaginatorLimit;
/**
 * PaginatorLimitForm class.
 *
 * The PaginatorLimitForm implement
 * methods to select the paginator
 * limit.
 *
 * @category Object
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class PaginatorLimitForm
{

    /**
     * PaginatorLimitForm attribute
     *
     * This attribute indicate the
     * current page of the paginator.
     *
     * @var integer
     */
    protected $page;

    /**
     * PaginatorLimitForm attribute
     *
     * This attribute indicate the
     * limit to use on rendering.
     *
     * @var integer
     */
    protected $limit;

    /**
     * PaginatorLimitForm attribute
     *
     * This attribute indicate the
     * last used limit on rendering.
     *
     * @var integer
     */
    protected $lastLimit;

    /**
     * PaginatorLimitForm attribute
     *
     * This attribute indicate the
     * allowed limits on rendering.
     *
     * @var array
     */
    protected $allowedLimits;

    /**
     * Get page
     * 
     * This method return the
     * current paged page.
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
     * This method allow to set 
     * the current paged page.
     * 
     * @param integer $page The current paged page
     * 
     * @return PaginatorLimit
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
     * needed page limit.
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
     * This method allow to set 
     * the needed page limit.
     * 
     * @param integer $limit The needed page limit
     * 
     * @return PaginatorLimit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Get last limit
     *
     * This method return the
     * last limit of the paginator.
     *
     * @return integer
     */
    public function getLastLimit()
    {
        return $this->lastLimit;
    }

    /**
     * Set last limit
     * 
     * This method allow to set 
     * the last limit of the 
     * paginator.
     * 
     * @param integer $lastLimit The last limit of the paginator
     * 
     * @return PaginatorLimit
     */
    public function setLastLimit($lastLimit)
    {
        $this->lastLimit = $lastLimit;
        return $this;
    }

    /**
     * Get allowed limit
     *
     * This method return the
     * allowed limits of the 
     * paginator.
     *
     * @return array
     */
    public function getAllowedLimits()
    {
        return $this->allowedLimits;
    }

    /**
     * Set allowed limit
     * 
     * This method allow to set 
     * the allowed limit of the 
     * paginator.
     * 
     * @param array $allowedLimits The allowed limit of the paginator
     * 
     * @return PaginatorLimit
     */
    public function setAllowedLimits(array $allowedLimits)
    {
        $this->allowedLimits = $allowedLimits;
        return $this;
    }
    
}