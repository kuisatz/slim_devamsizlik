<?php
/**
 *  Framework 
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
class PostgreSQLConnectPDO implements \Zend\ServiceManager\FactoryInterface {
    
    /**
     * service ceration via factory on zend service manager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return boolean|\PDO
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        try {
           /* $pdo = new \PDO('pgsql:dbname=development;host=localhost;',
                            'postgres',
                            '1Qaaal123',
                            PostgreSQLConnectPDOConfig::getConfig());
            * 
            */ 
            $pdo = new \PDO("sqlsrv:Server=ZZX;Database=Bilsanet1", 
                            "sa", 
                            "12345678oki"
                            );
     
 /*       $serverName = "tcp:ZZX,1433";  
            $connectionOptions = array("Database"=>"BILSANET",  
                "Uid"=>"sa", "PWD"=>"12345678oki");  
            $pdo = sqlsrv_connect($serverName, $connectionOptions);  
            if($pdo == false)  
                die(FormatErrors(sqlsrv_errors()));  
      * 
      */
            
   //    $pdo = new \PDO("sqlsrv:Server=ZZX;Database=BILSANET", "sa", "12345678oki");     
         
            return $pdo;
        } catch (PDOException $e) {
            return false;
        } 


    }

}
