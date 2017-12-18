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
class InfoUsersSendingMailFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoUsersSendingMail = new \DAL\PDO\InfoUsersSendingMail();
        $slimApp = $serviceLocator->get('slimApp');
        $infoUsersSendingMail->setSlimApp($slimApp);
        return $infoUsersSendingMail;
    }

}
