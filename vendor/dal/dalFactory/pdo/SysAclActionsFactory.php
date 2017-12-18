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
 * created date : 13.12.2015
 */
class SysAclActionsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {      
        $sysAclActions  = new \DAL\PDO\SysAclActions()   ;         
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAclActions -> setSlimApp($slimapp);         
        return $sysAclActions;
      
    }
    
    
}