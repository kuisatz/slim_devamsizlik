<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace DAL;

/**
 * DAL manager extended from Zend\ServiceManager\ServiceManager
 * @author Okan CIRAN
 */
class DalManager extends \Zend\ServiceManager\ServiceManager {
    
    public function __construct(\Zend\ServiceManager\ConfigInterface $config = null) {
        parent::__construct($config);
    }
}
