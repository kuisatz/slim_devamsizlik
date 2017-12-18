<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Services;

/**
 * interfac for DAL manager layer set and get purposes
 * @author Okan CIRAN
 */
interface DalManagerInterface {
    /**
     * injects Dal manager instance extended from Zend
     * service manager instance in Slimm Application
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @author Okan CIRAN
     */
    public function setDalManager(\Zend\ServiceManager\ServiceLocatorInterface $serviceManager);
    
    /**
     * gets Dal manager instance extended from 
     * Zend service manager instance from Slimm Application
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     * @author Okan CIRAN
     */
    public function getDAlManager();
}

