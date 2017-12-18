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
 * created date : 08.12.2015
 */
class SysSocialMediaFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSocialMedia  = new \DAL\PDO\SysSocialMedia();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSocialMedia -> setSlimApp($slimapp); 
        return $sysSocialMedia;
      
    }
    
    
}