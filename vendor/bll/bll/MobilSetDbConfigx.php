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
class MobilSetDbConfigx extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('mobilSetDbConfigxPDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mobilSetDbConfigxPDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array | null $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mobilSetDbConfigxPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array | null $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mobilSetDbConfigxPDO');
        return $DAL->getAll($params);
    }
    
    
   
    
    public function gnlKullaniciMebKoduFindByTcKimlikNo($params = array()) {
 
        $DAL = $this->slimApp->getDALManager()->get('mobilSetDbConfigxPDO');
        $resultSet = $DAL->gnlKullaniciMebKoduFindByTcKimlikNo($params);  
        return $resultSet['resultSet'];
    }
 
     
    
    public function mobilDBConfig($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mobilSetDbConfigxPDO');
    $resultSet = $DAL->mobilDBConfig($params);  
    return $resultSet['resultSet'];
    }
   
    
    
   
}

