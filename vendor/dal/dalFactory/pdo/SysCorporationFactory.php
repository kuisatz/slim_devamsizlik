<?php
/**
 *   Framework 
 *
 * @link      https://github.com/corner82/slim_test for the canonical source repository
 * @copyright Copyright (c) 2015 
 * @license   
 */
namespace DAL\Factory\PDO;


/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @author Okan CIRAN
 * created date : 11.06.2017
 */
class SysCorporationFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCorporation  = new \DAL\PDO\SysCorporation();      
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCorporation -> setSlimApp($slimapp);      
        return $sysCorporation;
      
    }
    
    
}