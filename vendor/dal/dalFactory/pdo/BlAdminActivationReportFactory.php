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
 * created date : 08.03.2015
 */
class BlAdminActivationReportFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $blAdminActivationReport  = new \DAL\PDO\BlAdminActivationReport()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $blAdminActivationReport -> setSlimApp($slimapp);
        
 
        
        return $blAdminActivationReport;
      
    }
    
    
}