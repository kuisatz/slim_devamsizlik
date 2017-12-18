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
class SysNotificationRestservices extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ sys_notification_restservices tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  15.12.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $statement = $pdo->prepare(" 
                    UPDATE sys_notification_restservices
                    SET  deleted= 1 , active = 1,
                          op_user_id = " . intval($opUserIdValue) . "     
                    WHERE id = " . intval($params['id']));
                $update = $statement->execute();
                $afterRows = $statement->rowCount();
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
            } else {
                $errorInfo = '23502';  /// 23502  not_null_violation
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_notification_restservices tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  15.12.2016  
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $statement = $pdo->prepare("
            SELECT 
                a.id, 
                a.rrp_id,
                a.restservice,
                concat( rr.name,' - ',  rs.name,' - ' ,  rp.name , ' map ') AS map_adi,
                rrp.role_id, 
                rr.name AS role_name,
                rrp.resource_id, 
                rs.name AS resource_name,
                rrp.privilege_id,
                rp.name AS privilege_name,		 
                a.c_date AS create_date,		    
                a.deleted, 
                sd.description AS state_deleted,                 
                a.active, 
                sd1.description AS state_active,  
                a.description,                                     
                a.op_user_id,
                u.username AS op_user_name
            FROM sys_notification_restservices  a
            INNER JOIN sys_acl_rrp rrp ON rrp.id = a.rrp_id AND rrp.deleted =0 AND rrp.active =0 
            INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id = 647 AND sd.deleted = 0 AND sd.active = 0
            INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0  
            INNER JOIN info_users u ON u.id = a.op_user_id 
            INNER JOIN sys_acl_roles rr ON rr.id = rrp.role_id AND rr.deleted = 0 AND rr.active = 0 
            INNER JOIN sys_acl_resources rs ON rs.id = rrp.resource_id AND rs.deleted = 0 AND rs.active = 0 
            INNER JOIN sys_acl_privilege rp ON rp.id = rrp.privilege_id AND rp.deleted = 0 AND rp.active = 0             
            WHERE a.deleted =0 AND a.active =0
            ORDER BY map_adi,a.restservice
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
     * @ sys_notification_restservices tablosunda role_id, resource_id ve privilege_id aynı kayıtta daha önce oluşturulmuş mu? 
     * @version v 1.0 15.12.2016
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
                name AS name , 
                '" . $params['name'] . "' AS value , 
                name ='" . $params['name'] . "' AS control,
                concat(name , ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) as message                             
            FROM sys_notification_restservices                
            WHERE LOWER(REPLACE(name,' ','')) = LOWER(REPLACE('" . $params['name'] . "',' ','')) 
                AND services_group_id = ".intval($params['services_group_id'])." 
                AND language_id = ".intval($params['language_id'])." 
                " . $addSql . " 
               AND deleted =0   
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
     * @ sys_notification_restservices tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  15.12.2016
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
                $languageId = NULL;
                $languageIdValue = 647;
                if ((isset($params['language_code']) && $params['language_code'] != "")) {                
                    $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                    if (\Utill\Dal\Helper::haveRecord($languageId)) {
                        $languageIdValue = $languageId ['resultSet'][0]['id'];                    
                    }
                } 
                $kontrol = $this->haveRecords(array('language_id' =>$languageIdValue, 
                    'services_group_id' =>$params['services_group_id'],
                    'name' =>$params['name']));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {                    
                     
                    
                    $sql = "
                INSERT INTO sys_notification_restservices(
                        services_group_id, 
                        name, 
                        description, 
                        description_eng, 
                        language_id, 
                        op_user_id
                        )
                VALUES (
                        :services_group_id, 
                        :name, 
                        :description, 
                        :description_eng, 
                        :language_id, 
                        :op_user_id
                                             )   ";                    
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':op_user_id', $opUserIdValue, \PDO::PARAM_INT);
                    $statement->bindValue(':services_group_id', $params['services_group_id'], \PDO::PARAM_INT);
                    $statement->bindValue(':language_id', $languageIdValue, \PDO::PARAM_INT);
                    $statement->bindValue(':name', $params['services_group_id'], \PDO::PARAM_STR);
                    $statement->bindValue(':description',$params['description'], \PDO::PARAM_STR);
                    $statement->bindValue(':description_eng',$params['description_eng'], \PDO::PARAM_STR);
                    // echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $insertID = $pdo->lastInsertId('sys_notification_restservices_id_seq');
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
     * sys_notification_restservices tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!   
     * @version v 1.0  15.12.2016
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
                $languageId = NULL;
                $languageIdValue = 647;
                if ((isset($params['language_code']) && $params['language_code'] != "")) {                
                    $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                    if (\Utill\Dal\Helper::haveRecord($languageId)) {
                        $languageIdValue = $languageId ['resultSet'][0]['id'];                    
                    }
                } 
                $kontrol = $this->haveRecords(array('language_id' =>$languageIdValue, 
                    'id' =>$params['id'],
                    'services_group_id' =>$params['services_group_id'],
                    'name' =>$params['name']));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {  
                    $sql = "
                UPDATE sys_notification_restservices
                SET   
                    services_group_id = :services_group_id, 
                    name = :name, 
                    description = :description, 
                    description_eng = :description_eng, 
                    language_id = :language_id, 
                    op_user_id = :op_user_id
                WHERE id = " . intval($params['id']);
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':op_user_id', $opUserIdValue, \PDO::PARAM_INT);
                    $statement->bindValue(':services_group_id', $params['services_group_id'], \PDO::PARAM_INT);
                    $statement->bindValue(':language_id', $languageIdValue, \PDO::PARAM_INT);
                    $statement->bindValue(':name', $params['name'], \PDO::PARAM_STR);
                    $statement->bindValue(':description',$params['description'], \PDO::PARAM_STR);
                    $statement->bindValue(':description_eng',$params['description_eng'], \PDO::PARAM_STR);
                    //  echo debugPDO($sql, $params);          
                    $update = $statement->execute();
                    $affectedRows = $statement->rowCount();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
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
     * @ Gridi doldurmak için sys_notification_restservices tablosundan kayıtları döndürür !!
     * @version v 1.0  15.12.2016
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
            $sort = "rr.name,rs.name,rp.name,a.restservice";
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
                    a.rrp_id,
                    a.restservice,
                    concat( rr.name,' - ',  rs.name,' - ' ,  rp.name , ' map ') AS map_adi,
                    rrp.role_id, 
                    rr.name AS role_name,
                    rrp.resource_id, 
                    rs.name AS resource_name,
                    rrp.privilege_id,
                    rp.name AS privilege_name,		 
                    a.c_date AS create_date,		    
                    a.deleted, 
                    sd.description AS state_deleted,                 
                    a.active, 
                    sd1.description AS state_active,  
                    a.description,                                     
                    a.op_user_id,
                    u.username AS op_user_name
                FROM sys_notification_restservices  a
                INNER JOIN sys_acl_rrp rrp ON rrp.id = a.rrp_id AND rrp.deleted =0 AND rrp.active =0 
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id = 647 AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0  
                INNER JOIN info_users u ON u.id = a.op_user_id 
                INNER JOIN sys_acl_roles rr ON rr.id = rrp.role_id AND rr.deleted = 0 AND rr.active = 0 
                INNER JOIN sys_acl_resources rs ON rs.id = rrp.resource_id AND rs.deleted = 0 AND rs.active = 0 
                INNER JOIN sys_acl_privilege rp ON rp.id = rrp.privilege_id AND rp.deleted = 0 AND rp.active = 0             
                WHERE a.deleted =0 AND a.active =0
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
            //  echo debugPDO($sql, $parameters);
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
     * @ Gridi doldurmak için sys_notification_restservices tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  15.12.2016
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
                FROM sys_notification_restservices  a
                INNER JOIN sys_acl_rrp rrp ON rrp.id = a.rrp_id AND rrp.deleted =0 AND rrp.active =0 
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id = 647 AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0  
                INNER JOIN info_users u ON u.id = a.op_user_id 
                INNER JOIN sys_acl_roles rr ON rr.id = rrp.role_id AND rr.deleted = 0 AND rr.active = 0 
                INNER JOIN sys_acl_resources rs ON rs.id = rrp.resource_id AND rs.deleted = 0 AND rs.active = 0 
                INNER JOIN sys_acl_privilege rp ON rp.id = rrp.privilege_id AND rp.deleted = 0 AND rp.active = 0             
                WHERE a.deleted =0 AND a.active =0
                    ";
            $statement = $pdo->prepare($sql);
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
     * @ sys_notification_restservices bilgilerini döndürür !!
     * filterRules aktif 
     * @version v 1.0  15.12.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillNotificationRestservicesList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            if (isset($params['page']) && $params['page'] != "" && isset($params['rows']) && $params['rows'] != "") {
                $offset = ((intval($params['page']) - 1) * intval($params['rows']));
                $limit = intval($params['rows']);
            } else {
                $limit = 10;
                $offset = 0;
            }

            $sortArr = array();
            $orderArr = array();
            if (isset($params['sort']) && $params['sort'] != "") {
                $sort = trim($params['sort']);
                $sortArr = explode(",", $sort);
                if (count($sortArr) === 1)
                    $sort = trim($params['sort']);
            } else {
                $sort = " name";
            }

            if (isset($params['order']) && $params['order'] != "") {
                $order = trim($params['order']);
                $orderArr = explode(",", $order);
                //print_r($orderArr);
                if (count($orderArr) === 1)
                    $order = trim($params['order']);
            } else {
                $order = "ASC";
            }

            $sorguStr = null;
            if ((isset($params['filterRules']) && $params['filterRules'] != "")) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) { 
                            case 'name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND name" . $sorguExpression . ' ';

                                break;
                             case 'service_group_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND service_group_name" . $sorguExpression . ' ';

                                break;                            
                            case 'description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND description" . $sorguExpression . ' ';

                                break;
                             case 'description_eng':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND description_eng" . $sorguExpression . ' ';

                                break;
                            case 'op_user_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND op_user_name" . $sorguExpression . ' ';

                                break;  
                            case 'state_active':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND state_active" . $sorguExpression . ' ';

                                break; 
                            default:
                                break;
                        }
                    }
                }
            } else {
                $sorguStr = null;
                $filterRules = "";
            }
            $sorguStr = rtrim($sorguStr, "AND ");

                            
            $sql = " 
                SELECT
                    id,
                    name,                             
                    services_group_id, 
                    service_group_name,
                    description,
                    description_eng,                             
                    active, 
                    state_active,                              
                    op_user_id,
                    op_user_name,
                    deleted
                    FROM (
                        SELECT 
                            a.id,
                            a.name,                             
                            a.services_group_id,   
                            ssg.name AS service_group_name,
                            a.description,
                            a.description_eng,                             
                            a.active, 
                            sd1.description AS state_active,                              
                            a.op_user_id,
                            u.username AS op_user_name,
                            a.deleted
                        FROM sys_notification_restservices a
                        INNER JOIN sys_services_groups ssg ON ssg.id = a.services_group_id AND ssg.active =0 AND ssg.deleted =0 
                        INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0  
                        INNER JOIN info_users u ON u.id = a.op_user_id 
                        WHERE a.deleted =0 AND a.active =0 AND a.language_parent_id =0 
                    ) AS xtable 
                    WHERE deleted=0  
                    " . $sorguStr . "
                                       
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

    /**
     * @author Okan CIRAN
     * @ sys_notification_restservices bilgilerinin sayısını döndürür !!
     * filterRules aktif 
     * @version v 1.0  15.12.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillNotificationRestservicesListRtc($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sorguStr = null;
            if ((isset($params['filterRules']) && $params['filterRules'] != "")) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND name" . $sorguExpression . ' ';

                                break;
                             case 'service_group_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND service_group_name" . $sorguExpression . ' ';

                                break;                            
                            case 'description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND description" . $sorguExpression . ' ';

                                break;
                             case 'description_eng':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND description_eng" . $sorguExpression . ' ';

                                break;
                            case 'op_user_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND op_user_name" . $sorguExpression . ' ';

                                break;  
                            case 'state_active':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND state_active" . $sorguExpression . ' ';

                                break; 
                            default:
                                break;
                        }
                    }
                }
            } else {
                $sorguStr = null;
                $filterRules = "";
            }
            $sorguStr = rtrim($sorguStr, "AND ");
                           
            $sql = "
                SELECT count(id) FROM (
                SELECT
                    id,
                    name,                             
                    services_group_id, 
                    service_group_name,
                    description,
                    description_eng,                             
                    active, 
                    state_active,                              
                    op_user_id,
                    op_user_name,
                    deleted
                    FROM (
                        SELECT 
                            a.id,
                            a.name,                             
                            a.services_group_id,   
                            ssg.name AS service_group_name,
                            a.description,
                            a.description_eng,                             
                            a.active, 
                            sd1.description AS state_active,                              
                            a.op_user_id,
                            u.username AS op_user_name,
                            a.deleted
                        FROM sys_notification_restservices a
                        INNER JOIN sys_services_groups ssg ON ssg.id = a.services_group_id AND ssg.active =0 AND ssg.deleted =0 
                        INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = 647 AND sd1.deleted = 0 AND sd1.active = 0  
                        INNER JOIN info_users u ON u.id = a.op_user_id 
                        WHERE a.deleted =0 AND a.active =0 AND a.language_parent_id =0 
                    ) AS xtable 
                    WHERE deleted=0  
                    " . $sorguStr . "               
                      ) AS xxtable  
                        
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
 
    /**
     * @author Okan CIRAN
     * @ sys_notification_restservices tablosundan parametre olarak  gelen id kaydın aktifliğini
     *  0(aktif) ise 1 , 1 (pasif) ise 0  yapar. !!
     * @version v 1.0  15.12.2016
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
                UPDATE sys_notification_restservices
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM sys_notification_restservices
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
     * @ sys_notification_restservices tablosundan url e karsılık gelen operasyonun bilgilerini döndürür !!       
     * @version v 1.0  15.12.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getNotificationRestserviceId($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "
                SELECT 
                    a.id, 
                    1=1 AS control
                FROM sys_notification_restservices a                
                WHERE  
                    a.deleted =0 AND 
                    a.active =0 AND 
                    a.language_parent_id =0 AND 
                    a.services_group_id = 33 AND
                    a.name = '". $params['url']."'
                LIMIT 1               
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
