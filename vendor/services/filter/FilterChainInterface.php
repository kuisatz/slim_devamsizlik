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
 * ınterface to filterchain operations on overall
 * @author Okan CIRAN
 * @version 13/01/2016
 */
interface FilterChainInterface {
    public function setFilterChain(\Utill\Strip\Chain\AbstractStripChainer $filterChainer);
    public function getFilterChain($name = null);
}