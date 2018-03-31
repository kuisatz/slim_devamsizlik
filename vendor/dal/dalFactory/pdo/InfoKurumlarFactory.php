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
class InfoKurumlarFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoKurumlar  = new \DAL\PDO\InfoKurumlar();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $infoKurumlar -> setSlimApp($slimapp); 
        return $infoKurumlar; 
    }
     
}