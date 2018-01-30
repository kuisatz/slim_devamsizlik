<?php
/**
 s
 * @copyright Copyright (c)  
 * @license   
 */
namespace DAL\Factory\PDO;


/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @author Okan CIRAN
 * @since 22.10.2017
 */
class InfoDuyuruFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoDuyuru  = new \DAL\PDO\InfoDuyuru();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $infoDuyuru -> setSlimApp($slimapp); 
        return $infoDuyuru;
      
    }
    
    
}