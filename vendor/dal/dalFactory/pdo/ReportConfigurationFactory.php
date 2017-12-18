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
 */
class ReportConfigurationFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $reportConfiguration = new \DAL\PDO\ReportConfiguration();
        $slimApp = $serviceLocator->get('slimApp');
        $reportConfiguration->setSlimApp($slimApp);
        return $reportConfiguration;
    }

}
