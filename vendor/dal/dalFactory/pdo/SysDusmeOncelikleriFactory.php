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
class SysDusmeOncelikleriFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysDusmeOncelikleri = new \DAL\PDO\SysDusmeOncelikleri();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysDusmeOncelikleri -> setSlimApp($slimapp); 
        return $sysDusmeOncelikleri; 
    }
     
}