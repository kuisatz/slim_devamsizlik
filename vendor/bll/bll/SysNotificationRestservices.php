<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace BLL\BLL;

/**
 * Business Layer class for report Configuration entity
 */
class SysNotificationRestservices extends \BLL\BLLSlim {

    /**
     * constructor
     */
    public function __construct() {
        //parent::__construct();
    }

    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function insert($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        return $DAL->insert($params);
    }

    /**
     * Data update function
     * @param array $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        return $DAL->update($params);
    }

    /**
     * Data delete function
     * @param array $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        return $DAL->getAll($params);
    }

    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid($params = array()) {

        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        $resultSet = $DAL->fillGrid($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);
        return $resultSet['resultSet'];
    }
 
    /**
     * Function to fill operation types on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillNotificationRestservicesList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        $resultSet = $DAL->fillNotificationRestservicesList($params);  
        return $resultSet['resultSet'];
    }
     /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillNotificationRestservicesListRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        $resultSet = $DAL->fillNotificationRestservicesListRtc($params);  
        return $resultSet['resultSet'];
    }  
 
  
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        return $DAL->makeActiveOrPassive($params);
    } 
    
    /**
     * get User Id - pkTemp
     * @param array $params
     * @return array
     */
    public function getNotificationRestserviceId($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNotificationRestservicesPDO');
        return $DAL->getNotificationRestserviceId($params);
    }
  

}
