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
 * created date : 15.07.2016
 */
class SysAclActionRrpFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAclActionRrp  = new \DAL\PDO\SysAclActionRrp();  
        $slimapp = $serviceLocator->get('slimapp');            
        $sysAclActionRrp -> setSlimApp($slimapp);
        return $sysAclActionRrp;
      
    }
    
    
}