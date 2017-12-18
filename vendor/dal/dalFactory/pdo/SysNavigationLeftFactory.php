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
 * created date : 08.12.2015
 */
class SysNavigationLeftFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysNavigationLeft = new \DAL\PDO\SysNavigationLeft()   ;   
             //print_r('asqweqweqwewqweeee ') ; 
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysNavigationLeft -> setSlimApp($slimapp);
        
 
        
        return $sysNavigationLeft;
      
    }
    
    
}