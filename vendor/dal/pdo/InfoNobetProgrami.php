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
class InfoNobetProgrami extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ info_nobetProgrami tablosundan parametre olarak  gelen id kaydını siler. !!
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
                UPDATE info_nobetProgrami
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
     * @ info_nobetProgrami tablosundaki tüm kayıtları getirir.  !!
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
            FROM info_nobetProgrami a  
            INNER JOIN info_nobetProgrami sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_code = a.language_code AND sd.deleted = 0 AND sd.active = 0 
            INNER JOIN info_nobetProgrami sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_code = a.language_code AND sd1.deleted = 0 AND sd1.active = 0
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
     * @ info_nobetProgrami tablosuna yeni bir kayıt oluşturur.  !!
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
            $ogrId = 0;
            if (isset($params['ogrId']) && $params['ogrId'] != "") {
                $ogrId = $params['ogrId'];
            }
            $nobetYeri = '';
            if (isset($params['nobetYeri']) && $params['nobetYeri'] != "") {
                $nobetYeri = $params['nobetYeri'];
            }
            $addSQL =NULL;
            $addSQLValue =NULL;
            $bastar = NULL;
            if (isset($params['bastar']) && $params['bastar'] != "") {
                $bastar = $params['bastar'];
                $addSQL .=' bastar, ';
                $addSQLValue .= "'".$bastar."',";
            }
            $basSaat = NULL;
            if (isset($params['basSaat']) && $params['basSaat'] != "") {
                $basSaat = $params['basSaat'];
                $addSQL .=' basSaat, ';
                $addSQLValue .= "'".$basSaat."',";
            }
            $bittar = NULL;
            if (isset($params['bittar']) && $params['bittar'] != "") {
                $bittar = $params['bittar'];
                $addSQL .=' bittar, ';
                $addSQLValue .= "'".$bittar."',";
            }
            $bitSaat = NULL;
            if (isset($params['bitSaat']) && $params['bitSaat'] != "") {
                $bitSaat = $params['bitSaat'];
                $addSQL .=' bitSaat, ';
                $addSQLValue .= "'".$bitSaat."',";
            }
                $sql = "
                INSERT INTO info_nobetProgrami(
                        ogrId,
                        ".$addSQL." 
                        nobetYeri 
                        )
                VALUES (
                        ".$ogrId.",
                        ".$addSQLValue." 
                        '".$nobetYeri."'  
                                            
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
     * @ info_nobetProgrami tablosunda name sutununda daha önce oluşturulmuş mu? 
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
            if (isset($params['ogrId']) && $params['ogrId'] != "") {
                $addSql .=" AND ogrId = " . $params['ogrId'] . " "; 
                if (isset($params['nobetYeri']) && $params['nobetYeri'] != "") {
                    $addSql .=" AND nobetYeri = '" . $params['nobetYeri'] . "' ";
                }
                if (isset($params['bastar']) && $params['bastar'] != "") {
                    $addSql .=" AND bastar = '" . $params['bastar'] . "' ";
                }
                if (isset($params['bittar']) && $params['bittar'] != "") {
                    $addSql .=" AND bittar = '" . $params['bittar'] . "' ";
                }
                if (isset($params['basSaat']) && $params['basSaat'] != "") {
                    $addSql .=" AND basSaat = '" . $params['basSaat'] . "' ";
                }
                if (isset($params['bitSaat']) && $params['bitSaat'] != "") {
                    $addSql .=" AND bitSaat = '" . $params['bitSaat'] . "' ";
                }
            } ELSE { $addSql .=" AND ogrId = -1 "; }
            $sql = " 
            SELECT  
                aciklama as name , 
                '1' AS value , 
                cast(1 as bit) AS control,
                concat(NobetYeri , ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM info_nobetProgrami                
            WHERE 1=1  
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
     * info_nobetProgrami tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
            $ogrId = 0;
            if (isset($params['ogrId']) && $params['ogrId'] != "") {
                $ogrId = $params['ogrId'];
            }
            $nobetYeri = '';
            if (isset($params['nobetYeri']) && $params['nobetYeri'] != "") {
                $nobetYeri = $params['nobetYeri'];
            }
            $addSQL =NULL;   
            if (isset($params['bastar']) && $params['bastar'] != "") { 
                $addSQL .=" bastar = '".$params['bastar']."',";
            }             
            if (isset($params['bittar']) && $params['bittar'] != "") { 
                $addSQL .=" bittar = '".$params['bittar']."',";
            }
            if (isset($params['basSaat']) && $params['basSaat'] != "") {
                $addSql .=" AND basSaat = '" . $params['basSaat']."',";
            }
            if (isset($params['bitSaat']) && $params['bitSaat'] != "") {
                $addSql .=" AND bitSaat = '" . $params['bitSaat']."',";
            }
                $sql = "
                UPDATE info_nobetProgrami
                SET    
                    ogrId = ".$ogrId.",   
                    ". $addSql . " 
                    nobetYeri = '".$nobetYeri."'  
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
     * @ Gridi doldurmak için info_nobetProgrami tablosundan kayıtları döndürür !!
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
                        FROM info_nobetProgrami a  
                        INNER JOIN info_nobetProgrami sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_code = a.language_code AND sd.deleted = 0 AND sd.active = 0 
                        INNER JOIN info_nobetProgrami sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_code = a.language_code AND sd1.deleted = 0 AND sd1.active = 0
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
     * @ Gridi doldurmak için info_nobetProgrami tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
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
                    (SELECT COUNT(a1.id) FROM info_nobetProgrami a1  
                    INNER JOIN info_nobetProgrami sd1x ON sd1x.main_group = 15 AND sd1x.first_group= a1.deleted AND sd1x.language_code = 'tr' AND sd1x.deleted = 0 AND sd1x.active = 0
                    INNER JOIN info_nobetProgrami sd11 ON sd11.main_group = 16 AND sd11.first_group= a1.active AND sd11.language_code = 'tr' AND sd11.deleted = 0 AND sd11.active = 0                             
                    INNER JOIN info_users u1 ON u1.id = a1.user_id 
                     " . $whereSQL1 . " ) AS undeleted_count, 
                    (SELECT COUNT(a2.id) FROM info_nobetProgrami a2
                    INNER JOIN info_nobetProgrami sd2 ON sd2.main_group = 15 AND sd2.first_group= a2.deleted AND sd2.language_code = 'tr' AND sd2.deleted = 0 AND sd2.active = 0
                    INNER JOIN info_nobetProgrami sd12 ON sd12.main_group = 16 AND sd12.first_group= a2.active AND sd12.language_code = 'tr' AND sd12.deleted = 0 AND sd12.active = 0                             
                    INNER JOIN info_users u2 ON u2.id = a2.user_id 			
                      " . $whereSQL2 . " ) AS deleted_count                        
                FROM info_nobetProgrami a
                INNER JOIN info_nobetProgrami sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_code = 'tr' AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN info_nobetProgrami sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_code = 'tr' AND sd1.deleted = 0 AND sd1.active = 0                             
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
     * @ combobox doldurmak için info_nobetProgrami tablosundan parent ı 0 olan kayıtları (Ana grup) döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $params
     * @return array
     * @throws \PDOException
     */ 
    public function fillNobetBilgileri($params = array()) {
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
            $sort = "concat(b.ad,' ',b.soyad) ";            
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
                            case 'Adsoyad':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(b.ad,' ',b.soyad)" . $sorguExpression . ' '; 
                                break;
                            case 'NobetYeri':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.nobetYeri" . $sorguExpression . ' '; 
                                break; 
                            case 'Bastar':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(FORMAT( a.bastar, 'dd-MM-yyyy') ,' ', cast(a.basSaat as nvarchar(5))) " . $sorguExpression . ' '; 
                                break;
                            case 'Bittar':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(FORMAT( a.bittar, 'dd-MM-yyyy') ,' ', cast(a.basSaat as nvarchar(5))) " . $sorguExpression . ' '; 
                                break;
                            case 'BasSaat':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND CAST(a.bitSaat as nvarchar(5))" . $sorguExpression . ' '; 
                                break;
                            case 'BitSaat':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND CAST(a.bitSaat as nvarchar(5)) " . $sorguExpression . ' '; 
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
                a.id,
                a.ogrId,
                concat(b.ad,' ',b.soyad) as adsoyad, 
                a.nobetYeri, 
                CONCAT(FORMAT( a.bastar, 'dd-MM-yyyy') ,' ', CAST(a.basSaat as nvarchar(5))) as bastar,
                CAST(a.basSaat as nvarchar(5)) as basSaat, 
                CONCAT(FORMAT( a.bittar, 'dd-MM-yyyy') ,' ', CAST(a.bitSaat as nvarchar(5))) as bittar,
                CAST(a.bitSaat as nvarchar(5)) as bitSaat,
                a.active,
                a.deleted
            FROM info_nobetProgrami a
            INNER JOIN info_ogretmenler b ON b.id = a.ogrId AND b.active =0 AND b.deleted =0 
            WHERE
                a.deleted =0 AND /* a.active=0 AND */ 
               /*	getdate() BETWEEN a.bastar AND a.bittar */ 
                '2017-10-17' BETWEEN a.bastar AND a.bittar
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
     * @ combobox doldurmak için info_nobetProgrami tablosundan parent ı 0 olan kayıtları (Ana grup) döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $params
     * @return array
     * @throws \PDOException
     */
    public function fillNobetBilgileriRtc() {
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
                           case 'Adsoyad':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(b.ad,' ',b.soyad)" . $sorguExpression . ' '; 
                                break;
                            case 'NobetYeri':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.nobetYeri" . $sorguExpression . ' '; 
                                break; 
                            case 'Bastar':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(FORMAT( a.bastar, 'dd-MM-yyyy') ,' ', cast(a.basSaat as nvarchar(5))) " . $sorguExpression . ' '; 
                                break;
                            case 'Bittar':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(FORMAT( a.bittar, 'dd-MM-yyyy') ,' ', cast(a.basSaat as nvarchar(5))) " . $sorguExpression . ' '; 
                                break;
                            case 'BasSaat':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND CAST(a.bitSaat as nvarchar(5))" . $sorguExpression . ' '; 
                                break;
                            case 'BitSaat':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND CAST(a.bitSaat as nvarchar(5)) " . $sorguExpression . ' '; 
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
            FROM info_nobetProgrami a
            INNER JOIN info_ogretmenler b ON b.id = a.ogrId AND b.active =0 AND b.deleted =0 
            WHERE
                a.deleted =0 AND /* a.active=0 AND */ 
               /*	getdate() BETWEEN a.bastar AND a.bittar */ 
                '2017-10-17' BETWEEN a.bastar AND a.bittar
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
     * su  an kullanılmıyor
     * @ combobox doldurmak için info_nobetProgrami tablosundan kayıtları döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $params
     * @return array
     * @throws \PDOException
     */ 
    public function fillNobetBilgileriDshBrd($params = array()) {
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
            $sort = "concat(b.ad,' ',b.soyad) ";            
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
                             case 'Adsoyad':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(b.ad,' ',b.soyad)" . $sorguExpression . ' '; 
                                break;
                            case 'NobetYeri':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.nobetYeri" . $sorguExpression . ' '; 
                                break; 
                            case 'Bastar':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(FORMAT( a.bastar, 'dd-MM-yyyy') ,' ', cast(a.basSaat as nvarchar(5))) " . $sorguExpression . ' '; 
                                break;
                            case 'Bittar':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(FORMAT( a.bittar, 'dd-MM-yyyy') ,' ', cast(a.basSaat as nvarchar(5))) " . $sorguExpression . ' '; 
                                break;
                            case 'BasSaat':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND CAST(a.bitSaat as nvarchar(5))" . $sorguExpression . ' '; 
                                break;
                            case 'BitSaat':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND CAST(a.bitSaat as nvarchar(5)) " . $sorguExpression . ' '; 
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
                a.id,
                a.ogrId,
                concat(b.ad,' ',b.soyad) as adsoyad, 
                a.nobetYeri, 
                CONCAT(FORMAT( a.bastar, 'dd-MM-yyyy') ,' ', CAST(a.basSaat as nvarchar(5))) as bastar,
                CAST(a.basSaat as nvarchar(5)) as basSaat, 
                CONCAT(FORMAT( a.bittar, 'dd-MM-yyyy') ,' ', CAST(a.bitSaat as nvarchar(5))) as bittar,
                CAST(a.bitSaat as nvarchar(5)) as bitSaat 
            FROM info_nobetProgrami a
            INNER JOIN info_ogretmenler b ON b.id = a.ogrId AND b.active =0 AND b.deleted =0 
            WHERE
                a.deleted =0 AND a.active=0 AND
               /*	getdate() BETWEEN a.bastar AND a.bittar */ 
                '2017-10-17' BETWEEN a.bastar AND a.bittar
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
     * @ grid doldurmak için info_nobetProgrami tablosundan  kayıtlarının sayısını döndürür !!
     * @version v 1.0  25.01.2016
     * @param array | null $params
     * @return array
     * @throws \PDOException
     */
    public function fillNobetBilgileriDshBrdRtc() {
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
                            case 'Adsoyad':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(b.ad,' ',b.soyad)" . $sorguExpression . ' '; 
                                break;
                            case 'NobetYeri':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.nobetYeri" . $sorguExpression . ' '; 
                                break; 
                            case 'Bastar':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(FORMAT( a.bastar, 'dd-MM-yyyy') ,' ', cast(a.basSaat as nvarchar(5))) " . $sorguExpression . ' '; 
                                break;
                            case 'Bittar':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND concat(FORMAT( a.bittar, 'dd-MM-yyyy') ,' ', cast(a.basSaat as nvarchar(5))) " . $sorguExpression . ' '; 
                                break;
                            case 'BasSaat':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND CAST(a.bitSaat as nvarchar(5))" . $sorguExpression . ' '; 
                                break;
                            case 'BitSaat':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND CAST(a.bitSaat as nvarchar(5)) " . $sorguExpression . ' '; 
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
            FROM info_nobetProgrami a
            INNER JOIN info_ogretmenler b ON b.id = a.ogrId AND b.active =0 AND b.deleted =0 
            WHERE
                a.deleted =0 AND a.active=0 AND
               /*	getdate() BETWEEN a.bastar AND a.bittar */ 
                '2017-10-17' BETWEEN a.bastar AND a.bittar
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
     * @ info_nobetProgrami tablosundan parametre olarak  gelen id kaydın aktifliğini
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
                UPDATE info_nobetProgrami
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM info_nobetProgrami
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
     * @ nobetçi ögretmenler dropdown ya da tree ye doldurmak için info_nobetProgrami tablosundan kayıtları döndürür !!
     * @version v 1.0  18.07.2017
     * @param array | null $params
     * @return array
     * @throws \PDOException 
     */
    public function fillNobetBilgileriNowCmb($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');   
            $sql = "            
                SELECT
                    a.id,
                  /*  a.ogrId,*/
                    concat(b.ad,' ',b.soyad) as name,
                    a.active, 
                    0 AS state_type 
                   /* a.nobetYeri,
                    CONCAT(FORMAT( a.bastar, 'dd-MM-yyyy') ,' ', CAST(a.basSaat as nvarchar(5))) as bastar,
                    CONCAT(FORMAT( a.bittar, 'dd-MM-yyyy') ,' ', CAST(a.bitSaat as nvarchar(5))) as bittar */ 
                FROM info_nobetProgrami a
                INNER JOIN info_ogretmenler b ON b.id = a.ogrId AND b.active =0 AND b.deleted =0 
                WHERE
                    a.deleted =0 AND a.active=0 AND
                   /*	getdate() BETWEEN a.bastar AND a.bittar */ 
                    '2017-10-17' BETWEEN a.bastar AND a.bittar 
                ORDER BY concat(b.ad,' ',b.soyad) 
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
