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
 * created date : 01.08.2016
 */
class SysAssignDefinitionRolesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAssignDefinitionRoles  = new \DAL\PDO\SysAssignDefinitionRoles()   ;         
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAssignDefinitionRoles -> setSlimApp($slimapp); 
        return $sysAssignDefinitionRoles;
      
    }
    
    
}