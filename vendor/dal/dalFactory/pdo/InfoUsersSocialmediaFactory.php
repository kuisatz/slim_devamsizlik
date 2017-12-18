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
class InfoUsersSocialmediaFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoUsersSocialmedia = new \DAL\PDO\InfoUsersSocialmedia();
        $slimApp = $serviceLocator->get('slimApp');
        $infoUsersSocialmedia->setSlimApp($slimApp);
        return $infoUsersSocialmedia;
    }

}
