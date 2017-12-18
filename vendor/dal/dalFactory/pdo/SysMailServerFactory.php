<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace DAL\Factory\PDO;


/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @author Okan CIRAN
 * created date : 24.05.2016
 */
class SysMailServerFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {     
        $sysMailServer  = new \DAL\PDO\SysMailServer();          
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysMailServer -> setSlimApp($slimapp);
         
        
        return $sysMailServer;
      
    }
    
    
}