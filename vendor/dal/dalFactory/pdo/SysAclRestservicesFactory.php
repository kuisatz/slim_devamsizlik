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
 * created date : 27.07.2016
 */
class SysAclRestservicesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAclRestservices  = new \DAL\PDO\SysAclRestservices();  
        $slimapp = $serviceLocator->get('slimapp');            
        $sysAclRestservices -> setSlimApp($slimapp);
        return $sysAclRestservices;
      
    }
    
    
}