<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace BLL;

/**
 * interface to set BLL Manager
 * @author Okan CIRAN
 */
interface BLLManagerInterface {
    /**
     * injects Dal manager instance extended from Zend
     * service manager instance in Slimm Application
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @author Okan CIRAN
     */
    public function setBLLManager(\Zend\ServiceManager\ServiceLocatorInterface $serviceManager);
    
    /**
     * gets Dal manager instance extended from 
     * Zend service manager instance from Slimm Application
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     * @author Okan CIRAN
     */
    public function getBLLManager();
}

