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

/**
 * DataGridContainer class.
 *
 * The DataGridContainer implement
 * access method to datagrid array.
 *
 * @category Object
 * @package  CscfaDataGridBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class DataGridContainer
{
    /**
     * Datagrid container constant
     * 
     * This constant indicate that the
     * given array contain objects.
     * 
     * @var integer
     */
    const TYPE_OBJECT = 0;
    /**
     * Datagrid container constant
     * 
     * This constant indicate that the 
     * given array contain an array.
     * 
     * @var integer
     */
    const TYPE_ARRAY = 1;
    
    /**
     * DataGridContainer attribute
     * 
     * This attribute contain the string 
     * access methods to the containers 
     * elements.
     * 
     * @var array
     */
    protected $accessMethods;
    
    /**
     * DataGridContainer attribute
     * 
     * This attribute contain an array
     * that allow access to the datagrid
     * elements to render.
     * 
     * @var array
     */
    protected $container;
    
    /**
     * DataGridContainer attribute
     * 
     * This attribute contain an integer
     * that indicate the container elements
     * types.
     * 
     * @var integer
     */
    protected $type;
    
    /**
     * DataGridContainer attribute
     * 
     * This attribute contain an array
     * that indicate the container elements
     * heads.
     * 
     * @var array
     */
    protected $header;
    
    /**
     * DataGridContainer attribute
     * 
     * This attribute contain the 
     * datagrid stepper.
     * 
     * @var DataGridStepper
     */
    protected $stepper;
    
    /**
     * DataGridContainer attribute
     * 
     * This attribute contain an array
     * that indicate each steps elements
     * of the datagrid.
     * 
     * @var array
     */
    private $processed;
    
    /**
     * Object constructor
     * 
     * The default object constructor
     * 
     * @param array   $container     The array conatining the elements to display
     * @param array   $accessMethods The access method to the contained elements
     * @param array   $headers       The access method to the contained elements
     * @param integer $containedType The containing elements types
     */
    public function __construct($container = array(), $accessMethods = array(), $headers = array(), $containedType = self::TYPE_ARRAY){
        
        $this->container = $container;
        
        $this->accessMethods = $accessMethods;
        
        $this->header = $headers;
        
        $this->type = $containedType;
        
    }

    /**
     * Get container
     * 
     * This methods allow to access 
     * to the elements container.
     * 
     * @return array
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set container
     * 
     * This methods allow to set
     * the elements container.
     * 
     * @param array $container The elements container
     * 
     * @return DataGridContainer
     */
    public function setContainer(array $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Get access methods
     * 
     * This methods allow to access 
     * to the elements access methods.
     * 
     * @return array
     */
    public function getAccessMethods()
    {
        return $this->accessMethods;
    }

    /**
     * Set access methods
     * 
     * This methods allow to set
     * the elements access methods.
     * 
     * @param array $accessMethods The elements access methods
     * 
     * @return DataGridContainer
     */
    public function setAccessMethods(array $accessMethods)
    {
        $this->accessMethods = $accessMethods;
        return $this;
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
        return $this->header;
    }

    /**
     * Set header
     * 
     * This methods allow to set
     * the elements headers.
     * 
     * @param array $header The elements headers
     * 
     * @return DataGridContainer
     */
    public function setHeader(array $header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * Get type
     * 
     * This methods allow to access 
     * to the elements type.
     * 
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * This methods allow to set
     * the elements type.
     *
     * @param integer $type The elements types
     * 
     * @throws \Exception        if the given type is undefined
     * @return DataGridContainer
     */
    public function setType($type)
    {
        if (!in_array($type, array(self::TYPE_ARRAY, self::TYPE_OBJECT))) {
            throw new \Exception("$type must be array, associative or object", 500, null);
        }
        
        $this->type = $type;
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
     * @return DataGridContainer
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
     * @return integer
     */
    public function getProcessed()
    {
        return $this->processed;
    }
    
    /**
     * Valid headers
     * 
     * This method validate the number
     * of headers compared to the access
     * method count.
     * 
     * Return true if eguals, elswhere,
     * return false.
     * 
     * @return boolean
     */
    public function validHeaders()
    {
        if (count($this->accessMethods) == count($this->header)) {
            return true;
        } else {
            return false;
        }
    }
     
    /**
     * Get data
     * 
     * This method return the
     * data to inject into the
     * datagrid.
     * 
     * @throws \Exception if the container is not an array or if the type is undefined
     * @return array
     */
    public function getData()
    {
        if (!is_array($this->container)) {
            throw new \Exception("the container must be an array", 500);
        } else {
            switch ($this->type) {
                case self::TYPE_OBJECT:
                    return $this->getDataFromObject($this->accessMethods, $this->container);
                    break;
                case self::TYPE_ARRAY:
                    return $this->getDataFromArray($this->accessMethods, $this->container);
                    break;
                default:
                    throw new \Exception("Undefined container type", 500);
            }
        }
    }
    
    /**
     * Get data from objects
     * 
     * This method return the data from
     * the object elements
     * 
     * @param array $access    The access method
     * @param array $container The container
     * 
     * @throws \Exception If the elements are not objects or if one of the method doesn't exist
     * @return array
     */
    protected function getDataFromObject($access, $container) 
    {
        $datas = array();
        $this->processed = array("type"=>"object");
        $i = $j = 0;
        
        foreach ($container as $element) {
            
            if (!is_object($element)) {
                throw new \Exception("The element must be an object", 500);
            }
            
            $position = count($datas);
            $datas[] = array();
            $this->processed[$i] = array("primary"=>$element);
            
            foreach ($access as $method) {
                
                if (!method_exists($element, $method)) {
                    throw new \Exception(sprintf("The method %s doesn't exist", $method), 500);
                } else {
                    $data = $element->{$method}();
                    $datas[$position][] = $data;
                    $this->processed[$i][$j] = array("access"=>$method, "data"=>$data);
                }
                $j ++;
            }
            $i ++;
        }
        
        return $datas;
    }
    
    /**
     * Get data from array
     * 
     * This method return the data from
     * the object array
     * 
     * @param array $access    The access method
     * @param array $container The container
     * 
     * @throws \Exception If the elements are not objects or if one of the key doesn't exist
     * @return array
     */
    protected function getDataFromArray($access, $container) 
    {
        $datas = array();
        $this->processed = array("type"=>"array");
        $i = $j = 0;
        
        foreach ($container as $element) {
            
            if (!is_array($element)) {
                throw new \Exception("The element must be an array", 500);
            }
            
            $position = count($datas);
            $datas[] = array();
            $this->processed[$i] = array("primary"=>$container);
            
            foreach ($access as $key) {
                
                if (!array_key_exists($key, $element)) {
                    throw new \Exception(sprintf("The key %s doesn't exist", $key), 500);
                } else {
                    $data = $element[$key];
                    $datas[$position][] = $data;
                    $this->processed[$i][$j] = array("access"=>$key, "data"=>$data);
                }
                $j ++;
            }
            $i ++;
        }
        
        return $datas;
    }
 
}
