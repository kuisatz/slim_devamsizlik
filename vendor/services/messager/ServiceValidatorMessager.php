<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Services\Messager;

/**
 * service manager layer for validator messager functions
 * @author Okan CIRAN
 * @version 13/01/2016
 */
class ServiceValidatorMessager implements \Zend\ServiceManager\FactoryInterface {
    
    /**
     * service ceration via factory on zend service manager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return Utill\Strip\Strip
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        // Create a message broker for usage
        $messager = new \Messager\ValidatorMessager();
        return $messager;

    }

}