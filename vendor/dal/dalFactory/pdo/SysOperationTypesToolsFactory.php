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
 * @since 11/02/2016
 */
class SysOperationTypesToolsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysOperationTypes  = new \DAL\PDO\SysOperationTypesTools();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysOperationTypes -> setSlimApp($slimapp);

        return $sysOperationTypes;
    }
    
    
}