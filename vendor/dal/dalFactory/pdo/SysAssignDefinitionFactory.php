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
class SysAssignDefinitionFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAssignDefinition  = new \DAL\PDO\SysAssignDefinition()   ;         
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAssignDefinition -> setSlimApp($slimapp); 
        return $sysAssignDefinition;
      
    }
    
    
}