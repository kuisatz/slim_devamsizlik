<?php

/**
 *  Framework 
 *
 * @link      https://github.com/corner82/slim_test for the canonical source repository
 * @copyright Copyright (c) 2015  
 * @license   
 */

namespace DAL\PDO;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CIRAN
 * @since 08.06.2017
 */
class SysCorporation extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ sys_corporation tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  08.06.2017
     * @param array $params
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
           $MachineId = $this -> haveMachineRecords(array('id' => $params['id']));
           if (!\Utill\Dal\Helper::haveRecord($MachineId)) {
                
                $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
                if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                    $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                    $statement = $pdo->prepare(" 
                UPDATE sys_corporation
                SET  deleted= 1 , active = 1 ,
                     op_user_id = " . $opUserIdValue . "     
                WHERE id = " . intval($params['id']));
                    $update = $statement->execute();
                    $afterRows = $statement->rowCount();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
                } else {
                    $errorInfo = '23502';   // 23502  not_null_violation
                    $errorInfoColumn = 'pk';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23503';   // 23503  foreign_key_violation
                $errorInfoColumn = 'Machine Grup Id';
                $pdo->rollback();
                return array("found" =>false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_corporation tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  08.06.2017  
     * @param array $params
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $statement = $pdo->prepare("
                SELECT 
                    a.id, 
                    a.name ,
                    a.abbreviation, 
                    a.description, 
                    a.logo,        
                    a.parent_id,                  		                   
                    a.deleted, 
                    sd.description as state_deleted,                 
                    a.active, 
                    sd1.description as state_active, 
                    a.op_user_id,
                    u.username AS op_user_name,
                    a.c_date                      
                FROM sys_corporation  a
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id= 647 AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0                             
                INNER JOIN info_users u ON u.id = a.op_user_id                              
                ORDER BY a.parent_id 
                                 "); 
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
     * @ sys_corporation tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  08.06.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $kontrol = $this->haveRecords($params);
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {      
                    $sql = "
                INSERT INTO sys_corporation(
                        name, 
                        abbreviation, 
                        description, 
                        logo, 
                        parent_id,   
                        op_user_id
                       )
                VALUES (
                        '".$params['name']."', 
                        '".$params['abbreviation']."', 
                        '".$params['description']."', 
                        '".$params['logo']."', 
                        ".intval($params['parent_id']).",  
                        ".intval($opUserIdValue)."  
                            )   ";
                    $statement = $pdo->prepare($sql);                
                   //  echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $insertID = $pdo->lastInsertId('sys_corporation_id_seq');
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
                } else {
                    $errorInfo = '23505';
                    $errorInfoColumn = 'name';
                    $pdo->rollback();                    
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);                    
                }
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_corporation tablosunda user_id li consultant daha önce kaydedilmiş mi ?  
     * @version v 1.0 08.06.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $addSql = "";
            if (isset($params['id'])) {
                $addSql = " AND a.id != " . intval($params['id']) . " ";
            }
            $sql = "  
            SELECT  
               a.name  AS name , 
               '" . $params['name'] . "' AS value ,  
                cast(1 as bit) AS control,
                CONCAT(a.name, ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM sys_corporation  a                          
            WHERE   
                lower(a.name) = trim( lower( '".$params['name']."' ))   
                  " . $addSql . " 
               AND a.deleted =0    
                               ";
            $statement = $pdo->prepare($sql);
          // echo debugPDO($sql, $params);
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
     * sys_corporation tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  08.06.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id']; 
                $kontrol = $this->haveRecords($params);
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                                      
                    $sql = "
                    UPDATE sys_corporation
                    SET   
                        name = '".$params['name']."', 
                        abbreviation ='".$params['abbreviation']."', 
                        description = '".$params['description']."', 
                        logo = '".$params['logo']."',
                        op_user_id = ".intval($opUserIdValue)."  
                    WHERE id = " . intval($params['id']);
                    $statement = $pdo->prepare($sql); 
                    //echo debugPDO($sql, $params);
                    $update = $statement->execute();                    
                    $affectedRows = $statement->rowCount();                 
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
                } else {
                    // 23505 	unique_violation
                    $errorInfo = '23505'; 
                    $errorInfoColumn = 'name';
                     $pdo->rollback();                  
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                 $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_corporation tablosundan kayıtları döndürür !!
     * @version v 1.0  08.06.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGrid($args = array()) {
        if (isset($args['page']) && $args['page'] != "" && isset($args['rows']) && $args['rows'] != "") {
            $offset = ((intval($args['page']) - 1) * intval($args['rows']));
            $limit = intval($args['rows']);
        } else {
            $limit = 10;
            $offset = 0;
        }

        $sortArr = array();
        $orderArr = array();
        if (isset($args['sort']) && $args['sort'] != "") {
            $sort = trim($args['sort']);
            $sortArr = explode(",", $sort);
            if (count($sortArr) === 1)
                $sort = trim($args['sort']);
        } else {
            $sort = "a.parent_id ";
        }

        if (isset($args['order']) && $args['order'] != "") {
            $order = trim($args['order']);
            $orderArr = explode(",", $order);
            //print_r($orderArr);
            if (count($orderArr) === 1)
                $order = trim($args['order']);
        } else {
            //$order = "desc";
            $order = "ASC";
        }
 
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "
                SELECT 
                    a.id, 
                    a.name ,
                    a.abbreviation, 
                    a.description, 
                    a.logo,        
                    a.parent_id,                  		                   
                    a.deleted, 
                    sd.description as state_deleted,                 
                    a.active, 
                    sd1.description as state_active, 
                    a.op_user_id,
                    u.username AS op_user_name,
                    a.c_date                      
                FROM sys_corporation  a
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id= 647 AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0                             
                INNER JOIN info_users u ON u.id = a.op_user_id    
                WHERE a.deleted =0              
                ORDER BY    " . $sort . " "
                    . "" . $order . " "
                    . "LIMIT " . $pdo->quote($limit) . " "
                    . "OFFSET " . $pdo->quote($offset) . " ";
            $statement = $pdo->prepare($sql);
            $parameters = array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pdo->quote($limit),
                'offset' => $pdo->quote($offset),
            );
            //   echo debugPDO($sql, $parameters);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_corporation tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  08.06.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');      
            $sql = "
               SELECT 
                    COUNT(a.id) AS COUNT  
                FROM sys_corporation  a
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id= 647 AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0                             
                INNER JOIN info_users u ON u.id = a.op_user_id    
                WHERE a.deleted =0                 
                    ";
            $statement = $pdo->prepare($sql);
           // echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }
 
    /**
     * @author Okan CIRAN
     * @ tree doldurmak için sys_corporation tablosundan tüm kayıtları döndürür !!
     * @version v 1.0  08.06.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillCorporationsTree($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $parentId = 0;
            if (isset($params['parent_id']) && $params['parent_id'] != "") {
                $parentId = $params['parent_id'];
            }
            $sql = "                
                SELECT                    
                    a.id,                     
                    a.name,
                    a.parent_id,
                    a.active ,
                    CASE 
			(SELECT DISTINCT 1 state_type FROM sys_corporation WHERE parent_id = a.id AND deleted = 0)    
                             WHEN 1 THEN 'closed'
                             ELSE 'open'  
                    END AS state_type,
                    CASE
                        (SELECT DISTINCT 1 parent_id FROM sys_corporation WHERE id = a.id AND deleted = 0 AND parent_id =0 )    
                        WHEN 1 THEN 'true'
                    ELSE 'false'   
                    END AS root_type,
                    CASE 
                        (SELECT DISTINCT 1 state_type FROM sys_corporation WHERE parent_id = a.id AND deleted = 0)    
                         WHEN 1 THEN 'false'
                    ELSE 'true'   
                    END AS last_node,
                    NULL AS icon_class,
                    a.abbreviation, 
                    a.description, 
                    a.logo
                FROM sys_corporation a  
                WHERE                    
                   a.parent_id = " .intval($parentId) . " AND 
                    a.deleted = 0  
                ORDER BY name  
             
                                 ";
              $statement = $pdo->prepare($sql);
           // echo debugPDO($sql, $params);
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
     * @ sys_corporation tablosundan parametre olarak  gelen id kaydın aktifliğini
     *  0(aktif) ise 1 , 1 (pasif) ise 0  yapar. !!
     * @version v 1.0  11.07.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makeActiveOrPassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                if (isset($params['id']) && $params['id'] != "") {

                    $sql = "                 
                UPDATE sys_corporation
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM sys_corporation
                                WHERE id = " . intval($params['id']) . "
                ),
                op_user_id = " . intval($opUserIdValue) . "
                WHERE id = " . intval($params['id']);
                    $statement = $pdo->prepare($sql);
                    //  echo debugPDO($sql, $params);
                    $update = $statement->execute();
                    $afterRows = $statement->rowCount();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                }
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
   /**
     * @author Okan CIRAN
     * @ sys_corporation tablosunda user_id li consultant daha önce kaydedilmiş mi ?  
     * @version v 1.0 15.01.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveMachineRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $languageId = NULL;
            $languageIdValue = 647;
            if ((isset($params['language_code']) && $params['language_code'] != "")) {                
                $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                if (\Utill\Dal\Helper::haveRecord($languageId)) {
                    $languageIdValue = $languageId ['resultSet'][0]['id'];                    
                }
            }  
            $sql = " 
            SELECT  
                a.machine_tool_grup_id AS name ,             
                a.machine_tool_grup_id = " .  intval($params['id']) . " AS control,
                'Bu grup altına Makina Kaydı Bulunmakta. Lütfen Kontrol Ediniz !!!' AS message   
            FROM sys_corporation  a  
            INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0 
            LEFT JOIN sys_language lx ON lx.deleted =0 AND lx.active =0 AND lx.id = " . intval($languageIdValue) . "
            LEFT JOIN sys_corporation ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.language_id = lx.id
            WHERE a.machine_tool_grup_id = ". intval($params['id']). "
                AND a.language_parent_id =0                  
                AND a.deleted =0    
            LIMIT 1                     
                               ";
            $statement = $pdo->prepare($sql);
           //echo debugPDO($sql, $params);
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
