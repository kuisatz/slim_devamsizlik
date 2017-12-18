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
 * created date : 18.07.2016
 */
class SysUniversitiesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysUniversities  = new \DAL\PDO\SysUniversities();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysUniversities -> setSlimApp($slimapp); 
        return $sysUniversities;
      
    }
    
    
}