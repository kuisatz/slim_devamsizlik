<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace DAL;

/**
 * abstract DAL class for DAl layer base classes
 * @author Okan CIRAN
 */
abstract class DalSlim extends AbstractDal
                                implements  \Slim\SlimAppInterface {
    
    /**
     * Slim application instance
     * @var Slim\Slim
     */
    protected $slimApp;
    
    
    /**
     * implemented method from \DAL\DalInterface has been overriden
     * @param array $params
     * @author Okan CIRAN
     * @since 16/01/2016
     */
    public function haveRecords($params = array()) {
        
    }
    
    /**
     * return slim app
     * @return Slim\Slim
     */
    public function getSlimApp() {
        return $this->slimApp;
    }

    /**
     * sets slim app
     * @param \Slim\Slim $slimApp
     */
    public function setSlimApp(\Slim\Slim $slimApp) {
        $this->slimApp = $slimApp;
    }

   

}
