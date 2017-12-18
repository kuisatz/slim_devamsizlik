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
 * @author Okan CİRANĞ
 * created date : 08.08.2016
 */
class SysOperationTypesRrpFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysOperationTypesRrp = new \DAL\PDO\SysOperationTypesRrp();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysOperationTypesRrp -> setSlimApp($slimapp); 
        return $sysOperationTypesRrp;      
    }    
    
}