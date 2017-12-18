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
 * created date : 21.10.2017
 */
class MobilSettingsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $mobilSettingsFactory  = new \DAL\PDO\MobilSettings();  
        $slimapp = $serviceLocator->get('slimapp');            
        $mobilSettingsFactory -> setSlimApp($slimapp);
        return $mobilSettingsFactory;
      
    }
    
    
}