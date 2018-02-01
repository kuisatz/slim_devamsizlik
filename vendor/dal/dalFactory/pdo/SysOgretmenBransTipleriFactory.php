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
class SysOgretmenBransTipleriFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysOgretmenBransTipleri  = new \DAL\PDO\SysOgretmenBransTipleri();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysOgretmenBransTipleri -> setSlimApp($slimapp); 
        return $sysOgretmenBransTipleri; 
    }
     
}