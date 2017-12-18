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
 * service manager layer for filter functions
 * @author Okan CIRAN
 * @version 13/01/2016
 */
class FilterChainerCustom implements \Zend\ServiceManager\FactoryInterface {
    
    /**
     * service ceration via factory on zend service manager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return Utill\Strip\Strip
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        // Create a filter chain and filter for usage
        $filterChainerCustom = new \Utill\Strip\Strip();
        return $filterChainerCustom;

    }

}
