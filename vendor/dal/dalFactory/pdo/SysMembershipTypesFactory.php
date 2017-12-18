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
 * created date : 21.06.2015
 */
class SysMembershipTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysMembershipTypes  = new \DAL\PDO\SysMembershipTypes();
        $slimapp = $serviceLocator->get('slimapp');
        $sysMembershipTypes -> setSlimApp($slimapp);
        return $sysMembershipTypes;      
    }
    
    
}