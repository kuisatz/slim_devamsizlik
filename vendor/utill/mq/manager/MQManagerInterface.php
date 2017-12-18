<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Utill\MQ\Manager;

/**
 * interface to set MQ Manager
 * @author Okan CIRAN
 */
interface MQManagerInterface {
    /**
     * injects MQ manager instance extended from Zend
     * service manager instance in Slimm Application
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @author Okan CIRAN
     */
    public function setMQManager(\Zend\ServiceManager\ServiceLocatorInterface $serviceManager);
    
    /**
     * gets MQ manager instance extended from 
     * Zend service manager instance from Slimm Application
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     * @author Okan CIRAN
     */
    public function getMQManager();
}



