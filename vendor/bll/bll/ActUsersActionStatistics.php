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
class ActUsersActionStatistics extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
        return $DAL->insert($params);
    }
    
      
    /**
     * Check Data function
     * @param array | null $params
     * @return array
     */
    public function haveRecords($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
        return $DAL->haveRecords($params);
    }
    
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array | null $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array | null $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
  
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
     
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function getUsersCompanyNotifications1($params = array()) {       
        $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
        $resultSet = $DAL->getUsersCompanyNotifications($params);  
        return $resultSet['resultSet'];
        
        
    }
    
    public function getUsersCompanyNotifications($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
    return $DAL->getUsersCompanyNotifications($params);
    }
    
    public function getUsersLeftNotifications($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
    return $DAL->getUsersLeftNotifications($params);
    }

    public function getUsersRightNotifications($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
    return $DAL->getUsersRightNotifications($params);
    }
    
    public function getFirmHistoryV1($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('actUsersActionStatisticsPDO');
    return $DAL->getFirmHistoryV1($params);
    }
    
    
    

}



