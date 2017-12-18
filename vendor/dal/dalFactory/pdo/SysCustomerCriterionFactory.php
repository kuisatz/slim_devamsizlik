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
class SysCustomerCriterionFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCustomerCriterion  = new \DAL\PDO\SysCustomerCriterion();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCustomerCriterion -> setSlimApp($slimapp); 
        return $sysCustomerCriterion;
      
    }
    
    
}