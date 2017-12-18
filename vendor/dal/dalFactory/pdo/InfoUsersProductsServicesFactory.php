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
class InfoUsersProductsServicesFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoUsersProductsServices = new \DAL\PDO\InfoUsersProductsServices();
        $slimApp = $serviceLocator->get('slimApp');
        $infoUsersProductsServices->setSlimApp($slimApp);
        return $infoUsersProductsServices;
    }

}
