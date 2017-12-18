<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace DAL\PDO;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CIRAN
 */
class MobilSetDbConfigx extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @  
     * @version v 1.0  30-10-2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
                            
        }
    }

    /**
     * @author Okan CIRAN
     * @  
     * @version v 1.0  30-10-2017 
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
                            
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_acl_action_rrp tablosunda name sutununda daha önce oluşturulmuş mu? 
     * @version v 1.0 11.08.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $addSql = "";
            if (isset($params['id'])) {
                $addSql = " AND id != " . intval($params['id']) . " ";
            }
            $sql = " 
           SELECT  
                name as name , 
                '" . $params['name'] . "' as value , 
                name ='" . $params['name'] . "' as control,
                concat(name , ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) as message                             
            FROM sys_acl_action_rrp        
            WHERE LOWER(REPLACE(name,' ','')) = LOWER(REPLACE('" . $params['name'] . "',' ','')) 
                AND resource_id = ".intval($params['resource_id'])."
                ". $addSql . " 
               AND deleted =0   
                               ";
            $statement = $pdo->prepare($sql);
            //   echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);

            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_acl_action_rrp tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  30-10-2017
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
                            
        }
    }    

    /**
     * @author Okan CIRAN
     * sys_acl_action_rrp tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  30-10-2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try {
                            
                            
        } catch (\PDOException $e /* Exception $e */) {
                            
        }
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_acl_action_rrp tablosundan kayıtları döndürür !!
     * @version v 1.0  30-10-2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGrid($args = array()) {
                            
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
               }
    }

    /**     
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_acl_action_rrp tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  30-10-2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
             }
    }
                            
    /** 
     * @author Okan CIRAN
     * @ login olan userin okul bilgileri ve okul id leri   !!
     * @version v 1.0  30.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function mobilDBConfig($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactoryMobil'); 
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = 70;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
                SELECT   
                    a.id,
                    a.configclass , 
                    sss.[name] as dbname,
                    (CASE WHEN (1 = 1) THEN 1 ELSE 0 END) AS control                    
                FROM BILSANET_MOBILE.[dbo].[Mobil_Settings] a
                INNER JOIN sys.sysdatabases sss on sss.dbid = ".$did." 
                WHERE a.id= ".$cid." ; 
            SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
  //  echo debugPDO($sql, $params);
            $statement->execute(); 
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    
                              
}
