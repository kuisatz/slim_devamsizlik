<?php
/**
 *Framework 
 *
 * @link 
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Services\Database\Postgresql;

 
/**
 * service manager layer for database connection
 * @author Okan CIRAN
 */
class PostgreSQLConnectDevamsizlikPDO implements \Zend\ServiceManager\FactoryInterface {
    
    /**
     * service ceration via factory on zend service manager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return boolean|\PDO
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        try {
           /* $pdo = new \PDO('pgsql:dbname=sorubankasi;host=localhost;',
                            'postgres',
                            '1Qaaal123',
                            PostgreSQLConnectPDOConfig::getConfig());
            */ 
              $pdo = new \PDO("sqlsrv:Server=.;Database=BILSANET_DEVAMSIZLIK", 
                            "sa", 
                            "12345678oki"
                            );
             
            return $pdo;
        } catch (PDOException $e) {
            return false;
        } 


    }

}
