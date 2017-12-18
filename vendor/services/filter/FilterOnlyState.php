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
 */
class FilterOnlyState implements \Zend\ServiceManager\FactoryInterface {
    
    
    /**
     * service ceration via factory on zend service manager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return boolean|\PDO
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        // Create a filter chain and filter for usage
        $filterChain = new \Zend\Filter\FilterChain();
        $filterChain ->attach(new \Zend\Filter\Whitelist(array('list' => 
                                                array('closed','open'))));
        return $filterChain;

        
    }

}
