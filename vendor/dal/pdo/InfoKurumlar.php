<?php

/**
 *Framework 
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
class InfoKurumlar extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ info_Kurumlar tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  07.01.2016
     * @param array $params
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
       try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $id = 0;
                if (isset($params['id']) && $params['id'] != "") {
                    $id = $params['id'];
                }
                $statement = $pdo->prepare(" 
                UPDATE info_Kurumlar
                SET deleted= 1, active = 1,
                op_user_id = " . intval($opUserIdValue) . "
                WHERE id = ".intval($id) 
                        );
                //Execute our DELETE statement.
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
     * @ info_Kurumlar tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  25.01.2016    
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory'); 
            $statement = $pdo->prepare("
            SELECT 
                a.id, 
                a.main_group, 
                a.first_group, 
                a.second_group,  
                COALESCE(NULLIF(a.description, ''), a.description_eng) AS name,  
                a.deleted, 
                a.parent_id, 
                a.active, 
                a.user_id, 
                a.language_parent_id, 
                a.language_code,
                COALESCE(NULLIF(l.language_eng, ''), l.language) AS language_name, 
                sd.description AS state_deleted,  
                sd1.description AS state_active,  
                u.username
            FROM info_Kurumlar a  
            INNER JOIN info_Kurumlar sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_code = a.language_code AND sd.deleted = 0 AND sd.active = 0 
            INNER JOIN info_Kurumlar sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_code = a.language_code AND sd1.deleted = 0 AND sd1.active = 0
            INNER JOIN sys_language l ON l.language_main_code = a.language_code AND l.deleted = 0 AND l.active = 0 
            INNER JOIN info_users u ON u.id = a.user_id 
            WHERE a.deleted =0 AND a.language_code = :language_code            
            ORDER BY a.id, a.parent_id                
                                 ");
            $statement->bindValue(':language_code', $params['language_code'], \PDO::PARAM_STR); 
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
     * @ info_Kurumlar tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  25.01.2016
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {        
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $pdo->beginTransaction();
            $kontrol = $this->haveRecords($params); 
            if (!\Utill\Dal\Helper::haveRecord($kontrol)) { 
                $kurumTurId = 0;
                if (isset($params['kurumTurId']) && $params['kurumTurId'] != "") {
                    $kurumTurId = $params['kurumTurId'];
                }
                $logo = '';
                if (isset($params['logo']) && $params['logo'] != "") {
                    $logo = $params['logo'];
                }
                $name = '';
                if (isset($params['name']) && $params['name'] != "") {
                    $name = $params['name'];
                }
                $nameAbb = '';
                if (isset($params['nameAbb']) && $params['nameAbb'] != "") {
                    $nameAbb = $params['nameAbb'];
                }
                $ilId = 0;
                if (isset($params['ilId']) && $params['ilId'] != "") {
                    $ilId = $params['ilId'];
                }
                $ilceId = 0;
                if (isset($params['ilceId']) && $params['ilceId'] != "") {
                    $kurumTurId = $params['ilceId'];
                } 
                $mahalleId = 0;
                if (isset($params['mahalleId']) && $params['mahalleId'] != "") {
                    $kurumTurId = $params['mahalleId'];
                }
                $adres1 = '';
                if (isset($params['adres1']) && $params['adres1'] != "") {
                    $adres1 = $params['adres1'];
                }
                $adres2 = '';
                if (isset($params['adres2']) && $params['adres2'] != "") {
                    $adres2 = $params['adres2'];
                }
                $postcode = '';
                if (isset($params['postcode']) && $params['postcode'] != "") {
                    $postcode = $params['postcode'];
                }
                $mebkodu = '';
                if (isset($params['mebkodu']) && $params['mebkodu'] != "") {
                    $mebkodu = $params['mebkodu'];
                }
            
                            
                $sql = "
                INSERT INTO info_Kurumlar(
                        ,kurumTurId
                        ,name
                        ,nameAbb
                        ,logo
                        ,ilId
                        ,ilceId
                        ,mahalleId
                        ,adres1
                        ,adres2
                        ,postcode
                        ,mebkodu
                        )
                VALUES (
                        ".$kurumTurId.",
                        '".$name."',   
                        '".$nameAbb."',   
                        ".$ilId.",
                        ".$ilceId.",
                        ".$mahalleId.",
                        '".$adres1."',
                        '".$adres2."', 
                        '".$postcode."',
                        '".$mebkodu."'
                         )   ";
                $statement = $pdo->prepare($sql);              
               // echo debugPDO($sql, $params);
                $result = $statement->execute();
                $insertID = $pdo->lastInsertId();
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
            } else {  
                $errorInfo = '23505'; 
                 $pdo->rollback();
                $result= $kontrol;  
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**    
     * @author Okan CIRAN
     * @ info_Kurumlar tablosunda name sutununda daha önce oluşturulmuş mu? 
     * @version v 1.0 15.01.2016
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $addSql = "";
            if (isset($params['id'])) {
                $addSql = " AND id != " . intval($params['id']) . " ";
            }             
            $sql = " 
            SELECT  
                aciklama as name , 
                '1' AS value , 
                cast(1 as bit) AS control,
                concat('', ' Kurum daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM info_Kurumlar                
            WHERE mebkodu = '".$params['mebkodu']."' 
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
     * info_Kurumlar tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  25.01.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');           
            $pdo->beginTransaction();     
            $kontrol = $this->haveRecords($params); 
            if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                $id = 0;
                if (isset($params['id']) && $params['id'] != "") {
                    $id = $params['id'];
                }
                $kurumTurId = 0;
                if (isset($params['kurumTurId']) && $params['kurumTurId'] != "") {
                    $kurumTurId = $params['kurumTurId'];
                }
                $logo = '';
                if (isset($params['logo']) && $params['logo'] != "") {
                    $logo = $params['logo'];
                }
                $name = '';
                if (isset($params['name']) && $params['name'] != "") {
                    $name = $params['name'];
                }
                $nameAbb = '';
                if (isset($params['nameAbb']) && $params['nameAbb'] != "") {
                    $nameAbb = $params['nameAbb'];
                }
                $ilId = 0;
                if (isset($params['ilId']) && $params['ilId'] != "") {
                    $ilId = $params['ilId'];
                }
                $ilceId = 0;
                if (isset($params['ilceId']) && $params['ilceId'] != "") {
                    $kurumTurId = $params['ilceId'];
                } 
                $mahalleId = 0;
                if (isset($params['mahalleId']) && $params['mahalleId'] != "") {
                    $kurumTurId = $params['mahalleId'];
                }
                $adres1 = '';
                if (isset($params['adres1']) && $params['adres1'] != "") {
                    $adres1 = $params['adres1'];
                }
                $adres2 = '';
                if (isset($params['adres2']) && $params['adres2'] != "") {
                    $adres2 = $params['adres2'];
                }
                $postcode = '';
                if (isset($params['postcode']) && $params['postcode'] != "") {
                    $postcode = $params['postcode'];
                }
                $mebkodu = '';
                if (isset($params['mebkodu']) && $params['mebkodu'] != "") {
                    $mebkodu = $params['mebkodu'];
                }
                $sql = "
                UPDATE info_Kurumlar
                SET   
                    kurumTurId =   ".$kurumTurId.",
                    name = '".$name."',   
                    nameAbb = '".$nameAbb."',   
                    ilId = ".$ilId.",
                    ilceId = ".$ilceId.",
                    mahalleId = ".$mahalleId.",
                    adres1 = '".$adres1."',
                    adres2 = '".$adres2."', 
                    postcode = '".$postcode."',
                    mebkodu = '".$mebkodu."' 
                WHERE id = " . intval($id);
                $statement = $pdo->prepare($sql); 
                $update = $statement->execute();
                $affectedRows = $statement->rowCount();
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
            } else {                
                // 23505 	unique_violation
                $errorInfo = '23505';// $kontrol ['resultSet'][0]['message'];  
                $pdo->rollback();
                $result= $kontrol;            
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
 
    /**  
     * @author Okan CIRAN
     * @ Gridi doldurmak için info_Kurumlar tablosundan kayıtları döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $params
     * @return array
     * @throws \PDOException
     */
    public function fillGrid($params = array()) {
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
            $sort = "id, parent_id";            
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
 
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $sql = "
            SELECT 
                id, 
                main_group, 
                first_group, 
                second_group,  
                name,  
                deleted, 
                parent_id, 
                active, 
                user_id, 
                language_parent_id, 
                language_code,
                language_name, 
                state_deleted,  
                state_active,  
                username FROM (
                        SELECT 
                            a.id, 
                            a.main_group, 
                            a.first_group, 
                            a.second_group,  
                            COALESCE(NULLIF(a.description, ''), a.description_eng) AS name,  
                            a.deleted, 
                            a.parent_id, 
                            a.active, 
                            a.user_id, 
                            a.language_parent_id, 
                            a.language_code,
                            COALESCE(NULLIF(l.language_eng, ''), l.language) AS language_name, 
                            sd.description AS state_deleted,  
                            sd1.description AS state_active,  
                            u.username
                        FROM info_Kurumlar a  
                        INNER JOIN info_Kurumlar sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_code = a.language_code AND sd.deleted = 0 AND sd.active = 0 
                        INNER JOIN info_Kurumlar sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_code = a.language_code AND sd1.deleted = 0 AND sd1.active = 0
                        INNER JOIN sys_language l ON l.language_main_code = a.language_code AND l.deleted = 0 AND l.active = 0 
                        INNER JOIN info_users u ON u.id = a.user_id 
                        WHERE a.deleted =0 AND language_code = '" . $params['language_code'] . "' ) AS asd               
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
     * @ Gridi doldurmak için info_Kurumlar tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  25.01.2016
     * @param array | null $params
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $whereSQL = '';
            $whereSQL1 = ' WHERE a1.deleted =0 ';
            $whereSQL2 = ' WHERE a2.deleted =1 ';            
            $sql = "
                SELECT 
                    COUNT(a.id) AS COUNT ,
                    (SELECT COUNT(a1.id) FROM info_Kurumlar a1  
                    INNER JOIN info_Kurumlar sd1x ON sd1x.main_group = 15 AND sd1x.first_group= a1.deleted AND sd1x.language_code = 'tr' AND sd1x.deleted = 0 AND sd1x.active = 0
                    INNER JOIN info_Kurumlar sd11 ON sd11.main_group = 16 AND sd11.first_group= a1.active AND sd11.language_code = 'tr' AND sd11.deleted = 0 AND sd11.active = 0                             
                    INNER JOIN info_users u1 ON u1.id = a1.user_id 
                     " . $whereSQL1 . " ) AS undeleted_count, 
                    (SELECT COUNT(a2.id) FROM info_Kurumlar a2
                    INNER JOIN info_Kurumlar sd2 ON sd2.main_group = 15 AND sd2.first_group= a2.deleted AND sd2.language_code = 'tr' AND sd2.deleted = 0 AND sd2.active = 0
                    INNER JOIN info_Kurumlar sd12 ON sd12.main_group = 16 AND sd12.first_group= a2.active AND sd12.language_code = 'tr' AND sd12.deleted = 0 AND sd12.active = 0                             
                    INNER JOIN info_users u2 ON u2.id = a2.user_id 			
                      " . $whereSQL2 . " ) AS deleted_count                        
                FROM info_Kurumlar a
                INNER JOIN info_Kurumlar sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_code = 'tr' AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN info_Kurumlar sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_code = 'tr' AND sd1.deleted = 0 AND sd1.active = 0                             
                INNER JOIN info_users u ON u.id = a.user_id 
                " . $whereSQL . "
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
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

    /** 
     * @author Okan CIRAN
     * su  an kullanılmıyor
     * @ combobox doldurmak için info_Kurumlar tablosundan parent ı 0 olan kayıtları (Ana grup) döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $params
     * @return array
     * @throws \PDOException
     */ 
    public function fillKurumlar($params = array()) {
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
            $sort = "a.name ";            
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
                            case 'Name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.name" . $sorguExpression . ' '; 
                                break;
                            case 'KurumTurName':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND b.name" . $sorguExpression . ' '; 
                                break; 
                            case 'Adres1':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.adres1" . $sorguExpression . ' '; 
                                break;
                            case 'Adres2':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND s.adres2" . $sorguExpression . ' '; 
                                break;
                            case 'Postcode':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.postcode" . $sorguExpression . ' '; 
                                break;
                            case 'NameAbb':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.nameAbb" . $sorguExpression . ' '; 
                                break;
                             case 'IlAdi':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.IlAdi" . $sorguExpression . ' '; 
                                break;
                             case 'IlceAdi':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.IlceAdi" . $sorguExpression . ' '; 
                                break;
                            
                            
                            default:
                                break;
                        }
                    }
                }
            }  
            $sorguStr = rtrim($sorguStr, "AND ");
            
 
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $sql = "
            SELECT
                a.id
                ,a.kurumTurId
                ,b.name as kurumTurName
                ,a.name
                ,a.nameAbb
                ,a.logo
                ,a.ilId
                ,il.IlAdi
                ,a.ilceId
                ,ilc.IlceAdi 
                ,a.adres1
                ,a.adres2
                ,a.postcode
                ,a.active
                ,a.deleted
            FROM info_Kurumlar a 
            inner join sys_KurumTurleri b on b.id =a.kurumTurId and b.active =0 and b.deleted =0 
            inner join Iller il on il.id = a.ilId
            inner join Ilceler ilc on ilc.IlceID = a.ilceId
            WHERE
                a.deleted =0   
                " . $sorguStr . "
            ORDER BY    " . $sort . " "
            . "" . $order . "  
            OFFSET ".$offset." ROWS FETCH NEXT ".$limit." ROWS ONLY;       
            " ;
            
            $statement = $pdo->prepare($sql); 
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
     * su  an kullanılmıyor
     * @ combobox doldurmak için info_Kurumlar tablosundan parent ı 0 olan kayıtları (Ana grup) döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $params
     * @return array
     * @throws \PDOException
     */
    public function fillKurumlarRtc() {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory'); 
            $sorguStr = null;
            if ((isset($params['filterRules']) && $params['filterRules'] != "")) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                 $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'Name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.name" . $sorguExpression . ' '; 
                                break;
                            case 'KurumTurName':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND b.name" . $sorguExpression . ' '; 
                                break; 
                            case 'Adres1':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.adres1" . $sorguExpression . ' '; 
                                break;
                            case 'Adres2':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND s.adres2" . $sorguExpression . ' '; 
                                break;
                            case 'Postcode':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.postcode" . $sorguExpression . ' '; 
                                break;
                            case 'NameAbb':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.nameAbb" . $sorguExpression . ' '; 
                                break;
                             case 'IlAdi':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.IlAdi" . $sorguExpression . ' '; 
                                break;
                             case 'IlceAdi':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.IlceAdi" . $sorguExpression . ' '; 
                                break;
                            default:
                                break;
                        }
                    }
                }
            }                
            $sorguStr = rtrim($sorguStr, "AND ");
            $sql = "
            SELECT
                count(a.id) as count  
            FROM info_Kurumlar a 
            inner join sys_KurumTurleri b on b.id =a.kurumTurId and b.active =0 and b.deleted =0 
            inner join Iller il on il.id = a.ilId
            inner join Ilceler ilc on ilc.IlceID = a.ilceId 
            WHERE
                a.deleted =0  
                " . $sorguStr . "
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
     * @ info_Kurumlar tablosundan parametre olarak  gelen id kaydın aktifliğini
     *  0(aktif) ise 1 , 1 (pasif) ise 0  yapar. !!
     * @version v 1.0  13.06.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makeActiveOrPassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                if (isset($params['id']) && $params['id'] != "") {

                    $sql = "                 
                UPDATE info_Kurumlar
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM info_Kurumlar
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
     * @ nobetçi ögretmenler dropdown ya da tree ye doldurmak için info_Kurumlar tablosundan kayıtları döndürür !!
     * @version v 1.0  18.07.2017
     * @param array | null $params
     * @return array
     * @throws \PDOException 
     */
    public function fillKurumlarCmb($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');   
            $sql = "   
                SELECT
                a.id, 
                a.name,  
                a.active,
                0 AS state_type 
            FROM info_Kurumlar a 
            WHERE
                a.deleted =0 AND a.active=0  
            ORDER BY  a.name 
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