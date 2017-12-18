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
class InfoUsersVerbalFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoUsersVerbal = new \DAL\PDO\InfoUsersVerbal();
        $slimApp = $serviceLocator->get('slimApp');
        $infoUsersVerbal->setSlimApp($slimApp);
        return $infoUsersVerbal;
    }

}
