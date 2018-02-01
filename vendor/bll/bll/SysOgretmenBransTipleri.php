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
class SysOgretmenBransTipleri extends \BLL\BLLSlim {

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
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');
        return $DAL->insert($params);
    }

    /**
     * Data update function 
     * @param array $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');
        return $DAL->update(  $params);
    }

    /**
     * Data delete function
     * @param array $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');     
        $resultSet =  $DAL->getAll($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');
        $resultSet = $DAL->fillGrid($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);
        return $resultSet['resultSet'];
    }

    /**
     *  
     * @param array  $params
     * @return array
     */
    public function fillOgretmenBransTipleri($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');
        $resultSet = $DAL->fillOgretmenBransTipleri($params);
        return $resultSet['resultSet'];
    }
    
    /**
     *  
     * @param array  $params
     * @return array
     */
    public function fillOgretmenBransTipleriRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');
        $resultSet = $DAL->fillOgretmenBransTipleriRtc($params);
        return $resultSet['resultSet'];
    }

    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');
        return $DAL->makeActiveOrPassive($params);
    }  
    
    /**
     *  
     * @param array  $params
     * @return array
     */
    public function fillOgretmenBransTipleriCmb($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysOgretmenBransTipleriPDO');
        $resultSet = $DAL->fillOgretmenBransTipleriCmb($params);
        return $resultSet['resultSet'];
    }
}
