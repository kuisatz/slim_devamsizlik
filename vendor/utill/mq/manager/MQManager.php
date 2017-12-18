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
 * DAL manager extended from Zend\ServiceManager\ServiceManager
 * @author Okan CIRAN
 */
class MQManager extends \Zend\ServiceManager\ServiceManager {
    
    public function __construct(\Zend\ServiceManager\ConfigInterface $config = null) {
        parent::__construct($config);
    }
}


