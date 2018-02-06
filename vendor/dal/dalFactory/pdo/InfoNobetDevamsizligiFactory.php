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
class InfoNobetDevamsizligiFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoNobetDevamsizligi  = new \DAL\PDO\InfoNobetDevamsizligi();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $infoNobetDevamsizligi -> setSlimApp($slimapp); 
        return $infoNobetDevamsizligi; 
    }
     
}