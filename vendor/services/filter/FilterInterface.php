<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Services\Filter;

/**
 * Interface for zend filter class implementations
 * @author Okan CIRAN
 * @since 13/01/2016
 */
interface FilterInterface {
    public function setFilter($params = null);
    public function getFilter($name = null);
    public function setFilterValue($value);
    public function getFilterValue();
}