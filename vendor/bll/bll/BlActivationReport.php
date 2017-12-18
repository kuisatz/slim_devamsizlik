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
class BlActivationReport extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array | null $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array | null $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        return $DAL->getAll($params);
    }
    
    
    /**
     *  
     * @param array$params
     * @return array
     */
    public function getConsultantOperation($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        $resultSet = $DAL->getConsultantOperation($params);  
        return $resultSet;
    }
    
       /**
     * 
     * @param array$params
     * @return array
     */
    public function getAllFirmCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        $resultSet = $DAL->getAllFirmCount($params);  
        return $resultSet;
    }
    
     /**
     *  
     * @param array$params
     * @return array
     */
    public function getConsultantFirmCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        $resultSet = $DAL->getConsultantFirmCount($params);  
        return $resultSet;
    }
    
        
     /**
     *  
     * @param array$params
     * @return array
     */
    public function getConsultantUpDashBoardCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        $resultSet = $DAL->getConsultantUpDashBoardCount($params);  
        return $resultSet;
    }
    
     /**
     *  
     * @param array$params
     * @return array
     */
    public function getConsWaitingForConfirm($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        $resultSet = $DAL->getConsWaitingForConfirm($params);  
        return $resultSet;
    }
    
    
     /**
     *  
     * @param array$params
     * @return array
     */
    public function getUrgeUpDashBoardCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        $resultSet = $DAL->getUrgeUpDashBoardCount($params);  
        return $resultSet;
    }
    /**
     *  
     * @param array$params
     * @return array
     */
    public function fillUrgeOrganizations($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        $resultSet = $DAL->fillUrgeOrganizations($params);  
        return $resultSet;
    }
    /**
     *  
     * @param array$params
     * @return array
     */
    public function fillUrgeOrganizationsRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        $resultSet = $DAL->fillUrgeOrganizationsRtc($params);  
        return $resultSet;
    }
     
     /**
     *  
     * @param array$params
     * @return array
     */
    public function getUrgeUpFirstDashBoardCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blActivationReportPDO');
        $resultSet = $DAL->getUrgeUpFirstDashBoardCount($params);  
        return $resultSet;
    }
    
    
    
    
}

