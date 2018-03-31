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
class SysHesaplanmaSekilleriFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysHesaplanmaSekilleri = new \DAL\PDO\SysHesaplanmaSekilleri();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysHesaplanmaSekilleri -> setSlimApp($slimapp); 
        return $sysHesaplanmaSekilleri; 
    }
     
}