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
class SysKurumTurleriFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysKurumTurleri  = new \DAL\PDO\SysKurumTurleri();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysKurumTurleri -> setSlimApp($slimapp); 
        return $sysKurumTurleri; 
    }
     
}