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
class SysBoroughFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysBorough  = new \DAL\PDO\SysBorough();   
       // print_r('servis  yaratılıyor...  ') ; 
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysBorough -> setSlimApp($slimapp);
        
 
        
        return $sysBorough;
      
    }
    
    
}