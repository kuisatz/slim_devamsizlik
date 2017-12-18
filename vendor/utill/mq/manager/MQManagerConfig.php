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
 * class called for MQ manager config 
 * MQ manager uses Zend Service manager and 
 * config class is compliant zend service config structure
 * @author Okan CIRAN
 */
class MQManagerConfig{
    
    /**
     * constructor
     */
    public function __construct() {
        
    }
    
    /**
     * config array for zend service manager config
     * @var array
     */
    protected $config= array(
        // Initial configuration with which to seed the ServiceManager.
        // Should be compatible with Zend\ServiceManager\Config.
         'service_manager' => array(
             'invokables' => array(
                 //'test' => 'Utill\BLL\Test\Test'
             ),
             'factories' => [
                 /**
                  * @author Okan CIRAN
                  * @todo first test to publish exceptions by manager has failed
                  * if further test do not work please erase 'MQException' below and related class
                  */
                 'serviceExceptions' => 'Utill\MQ\Factory\FactoryServiceExceptions',
                 'serviceRestInsertLog' => 'Utill\MQ\Factory\FactoryServiceRestInsertLog',
                 'serviceRestUpdateLog' => 'Utill\MQ\Factory\FactoryServiceRestUpdateLog',
                 'serviceRestDeleteLog' => 'Utill\MQ\Factory\FactoryServiceRestDeleteLog',
                 'serviceLogoutLog' => 'Utill\MQ\Factory\FactoryServiceLogoutLog',
                 'servicePageLog' => 'Utill\MQ\Factory\FactoryServicePageLog',
                 'serviceLoginLog' => 'Utill\MQ\Factory\FactoryServiceLoginLog',
             ],  
             
         ),
     );
    
    /**
     * return config array for zend MQ manager config
     * @return array | null
     * @author Okan CIRAN
     */
    public function getConfig() {
        return $this->config['service_manager'];
    }

}





