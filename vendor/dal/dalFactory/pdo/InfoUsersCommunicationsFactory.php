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
class InfoUsersCommunicationsFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoUsersCommunications = new \DAL\PDO\InfoUsersCommunications();
        $slimApp = $serviceLocator->get('slimApp');
        $infoUsersCommunications->setSlimApp($slimApp);
        return $infoUsersCommunications;
    }

}
