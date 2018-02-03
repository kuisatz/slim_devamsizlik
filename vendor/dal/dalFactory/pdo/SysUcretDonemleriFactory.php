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
class SysUcretDonemleriFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysUcretDonemleri  = new \DAL\PDO\SysUcretDonemleri();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysUcretDonemleri -> setSlimApp($slimapp); 
        return $sysUcretDonemleri; 
    }
     
}