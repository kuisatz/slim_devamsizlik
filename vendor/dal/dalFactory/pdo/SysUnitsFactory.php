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
 * created date : 08.02.2016
 */
class SysUnitsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysUnits  = new \DAL\PDO\SysUnits();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysUnits -> setSlimApp($slimapp);
        
 
        
        return $sysUnits;
      
    }
    
    
}