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
 * class for PDO postgreSQl connect variables
 */
class PostgreSQLConnectPDOConfig {
    
    /**
     * PDO connection configuration options,
     * @var array
     */
    public static $config = array(
            /*\PDO::ATTR_PERSISTENT => true*/);
    
    /**
     * returns static config array
     * @return array | null
     */
    public static function getConfig() {
        return self::$config;
    }
    
    
}

