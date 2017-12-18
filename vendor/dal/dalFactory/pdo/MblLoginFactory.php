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
 * created date : 25.10.2016
 */
class MblLoginFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $mblLogin  = new \DAL\PDO\MblLogin();  
        $slimapp = $serviceLocator->get('slimapp');            
        $mblLogin -> setSlimApp($slimapp);
        return $mblLogin;
      
    }
    
    
}