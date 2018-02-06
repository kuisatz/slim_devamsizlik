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
class InfoNobetProgramiFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoNobetProgrami  = new \DAL\PDO\InfoNobetProgrami();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $infoNobetProgrami -> setSlimApp($slimapp); 
        return $infoNobetProgrami; 
    }
     
}