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
 * example DAL layer class for test purposes
 * @author Okan CIRAN
 */
class InfoUsers extends \DAL\DalSlim {

    /**
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $pdo->beginTransaction();            
            $opUserIdParams = array('pk' =>  $params['pk'],);
            $opUserId = $this->slimApp->getServiceManager()->get('opUserIdBLL');
            $opUserIdArray= $opUserId->getUserId($opUserIdParams); 
            if (\Utill\Dal\Helper::haveRecord($opUserIdArray)) {
                $opUserIdValue = $opUserIdArray['resultSet'][0]['user_id'];
                $statement = $pdo->prepare("
                    UPDATE info_users 
                    SET deleted = 1, active =1,
                    user_id = " . $opUserIdValue . "                     
                    WHERE id = :id
                    ");
                $statement->bindValue(':id', $params['id'], \PDO::PARAM_INT);
                $update = $statement->execute();
                $affectedRows = $statement->rowCount();
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
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
     * @param array | null $args
     * @return type
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $languageCode = 'tr';
            $languageIdValue = 647;
            if (isset($params['language_code']) && $params['language_code'] != "") {
                $languageCode = $params['language_code'];
            }       
            $languageCodeParams = array('language_code' => $languageCode,);
            $languageId = $this->slimApp-> getBLLManager()->get('languageIdBLL');  
            $languageIdsArray= $languageId->getLanguageId($languageCodeParams);
            if (\Utill\Dal\Helper::haveRecord($languageIdsArray)) { 
                 $languageIdValue = $languageIdsArray ['resultSet'][0]['id']; 
            }  
            $statement = $pdo->prepare(" 
                    SELECT
                        a.id, 
                        ad.profile_public,                  
                        a.s_date, 
                        a.c_date, 
                        a.operation_type_id,                        
                        COALESCE(NULLIF(opx.operation_name, ''), op.operation_name_eng) AS operation_name,
                        ad.name, 
                        ad.surname, 
                        a.username, 
                        a.password, 
                        ad.auth_email,                   
                        ad.language_code, 
                        ad.language_id, 
                        l.language_eng as user_language,
			COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name,                        
                        a.active,                         
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                        ad.deleted,
                        COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,  			
                        a.op_user_id,
                        u.username AS op_user_name,
                        ad.act_parent_id, 
                        ad.auth_allow_id,                         
                        COALESCE(NULLIF(sd13x.description, ''), sd13.description_eng) AS auth_alow, 
                        ad.cons_allow_id,                        
                        COALESCE(NULLIF(sd14x.description, ''), sd14.description_eng) AS cons_allow,                   
                        ad.root_id,
                        a.consultant_id,
                        cons.name AS cons_name, 
                        cons.surname AS cons_surname,			 
                        COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS state_profile_public                        
                    FROM info_users a    
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0                     
                    LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0                     
                    INNER JOIN info_users_detail ad ON ad.deleted =0 AND ad.active =0 AND ad.root_id = a.id AND ad.language_parent_id = 0 
                    LEFT JOIN sys_operation_types op ON op.id = a.operation_type_id AND op.deleted =0 AND op.active =0 AND op.language_parent_id =0
                    LEFT JOIN sys_operation_types opx ON (opx.id = a.operation_type_id OR opx.language_parent_id = a.operation_type_id) and opx.language_id =lx.id  AND opx.deleted =0 AND opx.active =0 
		    
		    INNER JOIN sys_specific_definitions sd13 ON sd13.main_group = 13 AND ad.auth_allow_id = sd13.first_group AND sd13.deleted =0 AND sd13.active =0 AND sd13.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd14 ON sd14.main_group = 14 AND ad.cons_allow_id = sd14.first_group AND sd14.deleted =0 AND sd14.active =0 AND sd14.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= ad.profile_public AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_parent_id =0

                    LEFT JOIN sys_specific_definitions sd13x ON sd13x.main_group = 13 AND sd13x.language_id = lx.id AND (sd13x.id = sd13.id OR sd13x.language_parent_id = sd13.id) AND sd13x.deleted =0 AND sd13x.active =0
                    LEFT JOIN sys_specific_definitions sd14x ON sd14x.main_group = 14 AND sd14x.language_id = lx.id AND (sd14x.id = sd14.id OR sd14x.language_parent_id = sd14.id) AND sd14x.deleted =0 AND sd14x.active =0
                    LEFT JOIN sys_specific_definitions sd15x ON sd15x.main_group = 15 AND sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0 
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.main_group = 16 AND sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    LEFT JOIN sys_specific_definitions sd19x ON sd19x.main_group = 19 AND sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
                    
                    INNER JOIN info_users u ON u.id = a.op_user_id                      
                    LEFT JOIN info_users_detail cons ON cons.root_id = a.consultant_id AND cons.cons_allow_id =1 
                
                    ORDER BY ad.name, ad.surname
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
     * @ info_users_details tablosundan parametre olarak  gelen id kaydını aktifliğini 1 = pasif yapar. !!
     * @version v 1.0  09.02.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            //$pdo->beginTransaction();
            $statement = $pdo->prepare(" 
                UPDATE info_users
                SET                         
                    c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,                     
                    active = 1                    
                WHERE id = :id");
            $statement->bindValue(':id', $params['id'], \PDO::PARAM_INT);
            $update = $statement->execute();
            $afterRows = $statement->rowCount();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            //$pdo->commit();
            return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
        } catch (\PDOException $e /* Exception $e */) {
            //$pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_users tablosundan parametre olarak  gelen id kaydını aktifliğini 1 = pasif
     *  ve deleted 1 = silinmiş yapar. !!
     * @version v 1.0  09.02.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makeUserDeleted($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');        
            $statement = $pdo->prepare(" 
                UPDATE info_users 
                SET                         
                    c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,                     
                    active = 1 ,
                    deleted= 1                   
                WHERE id = :id");
            $statement->bindValue(':id', $params['id'], \PDO::PARAM_INT);
            $update = $statement->execute();
            $afterRows = $statement->rowCount();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);            
            return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
        } catch (\PDOException $e /* Exception $e */) {            
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_users tablosunda name sutununda daha önce oluşturulmuş mu? 
     * @version v 1.0 20.01.2016
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
            SELECT top 1 
                username AS username , 
                '" . $params['username'] . "' AS value , 
                cast(1 as bit) AS control,
                CONCAT(username , ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message                             
            FROM info_users                
            WHERE   
                LOWER(username) = LOWER('" . $params['username'] . "') "
                . $addSql . " 
               AND active =0         
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
     * @ info_users tablosunda name sutununda daha önce oluşturulmuş mu? 
     * @version v 1.0 20.01.2016
     * @return array
     * @throws \PDOException
     */
    public function haveEmail($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $addSql = "";
            if (isset($params['id'])) {
                $addSql = " AND id != " . intval($params['id']) . " ";
            }
            $sql = " 
            SELECT  
                auth_email AS auth_email , 
                '" . $params['auth_email'] . "' AS value , 
                auth_email ='" . $params['auth_email'] . "' AS control,
                CONCAT(auth_email , ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message                             
            FROM info_users_detail                
            WHERE   
                LOWER(auth_email) = LOWER('" . $params['auth_email'] . "') "
                    . $addSql . " 
               AND active =0         
               AND deleted =0   
                               ";
            $statement = $pdo->prepare($sql);
            //    echo debugPDO($sql, $params);
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
     * info_users tablosundaki kullanıcı kaydı oluşturur  !!     
     * @version v 1.0  26.01.2016
     * @param array | null $args
     * @return array
     * @throws PDOException
     */
    public function insert($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $pdo->beginTransaction(); 
            $kontrol = $this->haveRecords($params); // username kontrolu  
            if (!\Utill\Dal\Helper::haveRecord($kontrol)) { 
                $opUserIdParams = array('pk' =>  $params['pk'],);
                $opUserIdArray = $this->slimApp-> getBLLManager()->get('opUserIdBLL');  
                $opUserId = $opUserIdArray->getUserId($opUserIdParams);  
                if (\Utill\Dal\Helper::haveRecord($opUserId)) { 
                    $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                    $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];  
                    $roleId = 2 ; 
                 //   $password ='!!!!++.Qwerty.++!!!!'; 
                    $languageIdValue = 647;   
                    $preferredlanguageIdValue = 647;
                             
                    $url = null;
                    if (isset($params['url']) && $params['url'] != "") {
                        $url = $params['url'];
                    }    
                    $m = null;
                    if (isset($params['m']) && $params['m'] != "") {
                        $m = $params['m'];
                    }  
                    $a = null;
                    if (isset($params['a']) && $params['a'] != "") {
                        $a = $params['a'];
                    } 
                    $username ='bos geldi...';
                    if (isset($params['username']) && $params['username'] != "") {
                        $username = $params['username'];
                    } 
                    $authemail ='authemail.c.m.';
                    if (isset($params['auth_email']) && $params['auth_email'] != "") {
                        $authemail =$params['auth_email'];
                    } 
                    $surname ='surname.c.m.';
                    if (isset($params['surname']) && $params['surname'] != "") {
                        $surname =$params['surname'];
                    } 
                    $name ='name.c.m.';
                    if (isset($params['name']) && $params['name'] != "") {
                        $name =$params['name'];
                    } 
                    $password = null;
                    if (isset($params['password']) && $params['password'] != "") {
                        $password = $params['password'];
                    }   
                            
                    $sql = " 
                    INSERT INTO info_users( 
                            username, 
                            language_id,
                            op_user_id,
                            role_id 
                            )
                    VALUES (   
                            '".$username."', 
                            ".intval($languageIdValue).",
                            ".intval($opUserIdValue).",
                            ".intval($roleId)." 
                        )";

                    $statement = $pdo->prepare($sql);  
                    // echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $insertID = $pdo->lastInsertId();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                            

                    /*
                     * kullanıcı için gerekli olan private key ve value değerleri yaratılılacak.  
                     * kullanıcı için gerekli olan private key temp ve value temp değerleri yaratılılacak.  
                     */
                    $this->setPrivateKey(array('id' => $insertID,'username' => $username,'password' => $password,));
                   
                    /*
                     * kullanıcı bilgileri info_users_detail tablosuna kayıt edilecek.   
                     */
                    $this->insertDetail(
                            array(
                                'id' => $insertID,
                                'op_user_id' => $opUserIdValue,
                                'role_id' => $roleId,  
                                'language_id' => $preferredlanguageIdValue,  
                                'name' => $name,
                                'surname' => $surname,
                                'username' => $username,
                                'auth_email' => $authemail,
                                'act_parent_id' => NULL, //$params['act_parent_id'], 
                                'root_id' => $insertID, 
                                'password' => $password,
                            
                    ));      

                    $pdo->commit();
                 /*   $logDbData = $this->getUsernamePrivateKey(array('id' => $insertID));
                    $this->insertLogUser(array('oid' => $insertID ,
                                               'username'=> $logDbData['resultSet'][0]['username'],  
                                               'sf_private_key_value'=> $logDbData['resultSet'][0]['sf_private_key_value'],  
                                               'sf_private_key_value_temp'=> $logDbData['resultSet'][0]['sf_private_key_value_temp']  
                            
                                                ));
                  * *
                  */
                    return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
                } else {
                    $errorInfo = '23502';   // 23502  not_null_violation
                    $errorInfoColumn = 'pk';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23505';   // 23505  unique_violation
                $errorInfoColumn = 'username';
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
     * info_users tablosundaki kullanıcı kaydı oluşturur  !!
     * @version v 1.0  26.01.2016
     * @param array | null $args
     * @return array
     * @throws PDOException
     */
    public function insertDetail($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');

            $operationIdValue = -1;
            if ((isset($params['operation_type_id']) && $params['operation_type_id'] != "")) {
                $operationIdValue = $params['operation_type_id'];
            } 
            $addSqlinsert = NULL;
            $addSqlValue = NULL;
            $birthday = NULL;
            if (isset($params['birthday']) && $params['birthday'] != "") {
                $addSqlinsert .=" birthday,";
                $birthday = $params['birthday'];
                $addSqlValue .="'" . $birthday . "',";
            }               
            $tcno = NULL;
            if (isset($params['tcno']) && $params['tcno'] != "") {
                $addSqlinsert .=" tcno,";
                $tcno = $params['tcno'];
                $addSqlValue .="'" . $tcno . "',";
            }
                            
            $meslek = NULL;
            if (isset($params['meslek']) && $params['meslek'] != "") {
                $addSqlinsert .=" meslek,";
                $meslek = $params['meslek'];
                $addSqlValue .="'" . $meslek . "',";
            }
            $educationtypeid = NULL;
            if (isset($params['education_type_id']) && $params['education_type_id'] != "") {
                $addSqlinsert .=" education_type_id,";
                $educationtypeid = $params['education_type_id'];
                $addSqlValue .="" . $educationtypeid . ",";
            }
            $id = NULL;
            if (isset($params['id']) && $params['id'] != "") { 
                $id = $params['id']; 
            }
                            
            $sql = " 
                INSERT INTO info_users_detail(  
                            name, 
                            surname, 
                            auth_email,                             
                            act_parent_id,                              
                            language_id,                             
                            root_id, 
                            role_id,
                            op_user_id,
                            " . $addSqlinsert . "
                            password  
                            )
                VALUES (    
                            :name, 
                            :surname, 
                            :auth_email,                             
                            (SELECT IDENT_CURRENT('info_users_detail')),                              
                            :language_id,                             
                            :root_id, 
                            :role_id,
                            :op_user_id ,
                             " . $addSqlValue . "
                            (SELECT password FROM info_users WHERE id = ".$id.")
                           
                    )";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':name', $params['name'], \PDO::PARAM_STR);
            $statement->bindValue(':surname', $params['surname'], \PDO::PARAM_STR);
            $statement->bindValue(':auth_email', $params['auth_email'], \PDO::PARAM_STR);
            //$statement->bindValue(':password', ($params['password']), \PDO::PARAM_STR);
            $statement->bindValue(':language_id', $params['language_id'], \PDO::PARAM_INT);
            $statement->bindValue(':root_id', $params['root_id'], \PDO::PARAM_INT);
            $statement->bindValue(':role_id', $params['role_id'], \PDO::PARAM_INT);
            $statement->bindValue(':op_user_id', $params['op_user_id'], \PDO::PARAM_INT);
          //  echo debugPDO($sql, $params);
            $result = $statement->execute();
            $insertID = $pdo->lastInsertId();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);

            return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * parametre olarak gelen array deki 'id' li kaydın update ini yapar  !!
     * @version v 1.0  26.01.2016     
     * @param array | null $args
     * @param type $params
     * @return array
     * @throws PDOException
     */
    public function update($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $pdo->beginTransaction();            
            $opUserIdParams = array('pk' =>  $params['pk'],);
            $opUserIdArray = $this->slimApp-> getBLLManager()->get('opUserIdBLL');  
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {                
                $kontrol = $this->haveRecords($params);
                if ( \Utill\Dal\Helper::haveRecord($kontrol)) {                    
                    $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                    $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];                 
                    $url = null;
                    if (isset($params['url']) && $params['url'] != "") {
                        $url = $params['url'];
                    }    
                    $m = null;
                    if (isset($params['m']) && $params['m'] != "") {
                        $m = $params['m'];
                    }  
                    $a = null;
                    if (isset($params['a']) && $params['a'] != "") {
                        $a = $params['a'];
                    }  
                    $operationIdValue =  0;
                    $assignDefinitionIdValue = 0;
                    $operationTypeParams = array('url' => $url, 'role_id' => $opUserRoleIdValue, 'm' => $m,'a' => $a,);
                    $operationTypes = $this->slimApp-> getBLLManager()->get('operationsTypesBLL');  
                    $operationTypesValue = $operationTypes->getUpdateOperationId($operationTypeParams);
                    if (\Utill\Dal\Helper::haveRecord($operationTypesValue)) { 
                        $operationIdValue = $operationTypesValue ['resultSet'][0]['id']; 
                        $assignDefinitionIdValue = $operationTypesValue ['resultSet'][0]['assign_definition_id'];                     
                        if ($operationIdValue > 0) {
                            $url = null;
                        }
                    }      
                    
                    $languageIdValue = 647;                    
                            
                    /*
                     * parametre olarak gelen array deki 'id' li kaydın, info_users tablosundaki 
                     * alanlarını update eder !! username update edilmez.  
                     */
                    $this->updateInfoUsers(array('id' => $opUserIdValue,
                        'op_user_id' => $opUserIdValue,
                        'active' => $params['active'],
                        'language_id' => $languageIdValue,
                        'password' => $params['password'],
                        'operation_type_id' => $operationIdValue,
                    ));
                    /*
                     *  parametre olarak gelen array deki 'id' li kaydın, info_users_details tablosundaki 
                     * active = 0 ve deleted = 0 olan kaydın active alanını 1 yapar  !!
                     */
                    $this->setUserDetailsDisables(array('id' => $opUserIdValue));
                    $sql = " 
                    INSERT INTO info_users_detail(
                           profile_public,
                           operation_type_id,
                           name,
                           surname,
                           auth_email,                            
                           language_id,
                           op_user_id,      
                           root_id,
                           role_id,
                           act_parent_id,
                           password,
                           auth_allow_id                           
                           ) 
                           SELECT 
                                " . intval($params['profile_public']) . " AS profile_public,
                                " . intval($operationIdValue) . " AS operation_type_id,
                                '" . $params['name'] . "' AS name, 
                                '" . $params['surname'] . "' AS surname,
                                '" . $params['auth_email'] . "' AS auth_email,   
                                " . intval($languageIdValue). " AS language_id,   
                                " . intval($opUserIdValue) . " AS user_id,
                                a.root_id AS root_id,
                                a.role_id,
                                a.act_parent_id,
                                '" . md5($params['password']) . "' AS password ,
                                CASE
                                    (CASE 
                                        (SELECT (z.auth_email = '" . $params['auth_email'] . "') FROM info_users_detail z WHERE z.id = a.id)    
                                         WHEN true THEN 1
                                         ELSE 0  
                                         END ) 
                                     WHEN 1 THEN a.auth_allow_id
                                ELSE 0 END AS auth_allow_id
                            FROM info_users_detail a
                            WHERE a.root_id  =" . intval($params['id']) . " AND
                                a.active =1 AND a.deleted =0 AND 
                                a.c_date = (SELECT MAX(b.c_date)  
						FROM info_users_detail b WHERE b.root_id =a.root_id
						AND b.active =1 AND b.deleted =0)  
                    ";
                    $statementActInsert = $pdo->prepare($sql);
                    //   echo debugPDO($sql, $params);
                    $insertAct = $statementActInsert->execute();
                    $affectedRows = $statementActInsert->rowCount();
                    $insertID = $pdo->lastInsertId('info_users_detail_id_seq');                                      
                    $errorInfo = $statementActInsert->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);

                    /*
                    * ufak bir trik var. 
                    * işlem update oldugunda update işlemini yapan kişinin dil bilgisini kullanıcaz. 
                    * ancak delete işlemi oldugunda delete işlemini yapan user in dil bilgisini değil 
                    * silinen kaydı yapan kişinin dil bilgisini alıcaz.
                    */
                                
                    $consIdAndLanguageId = SysOperationTypes::getConsIdAndLanguageId(
                                  array('operation_type_id' =>$operationIdValue, 'id' => $params['id'],));
                    if (\Utill\Dal\Helper::haveRecord($consIdAndLanguageId)) {
                        $ConsultantId = $consIdAndLanguageId ['resultSet'][0]['consultant_id'];                       
                        // $languageIdValue = $consIdAndLanguageId ['resultSet'][0]['language_id'];                       
                    } 
                                
                    $consultantProcessSendParams = array(
                                'op_user_id' => intval($opUserIdValue),
                                'operation_type_id' => intval($operationIdValue),
                                'table_column_id' => intval($insertID),
                                'cons_id' => intval($ConsultantId),
                                'preferred_language_id' => intval($languageIdValue),
                                'url' => $url, 
                                'assign_definition_id' => $assignDefinitionIdValue, // operasyon atama tipi
                        );
                    $setConsultantProcessSend = $this->slimApp-> getBLLManager()->get('consultantProcessSendBLL');  
                    $setConsultantProcessSendArray= $setConsultantProcessSend->insert($consultantProcessSendParams);
                    if ($setConsultantProcessSendArray['errorInfo'][0] != "00000" &&
                            $setConsultantProcessSendArray['errorInfo'][1] != NULL &&
                            $setConsultantProcessSendArray['errorInfo'][2] != NULL)
                        throw new \PDOException($setConsultantProcessSendArray['errorInfo']);   
                    
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows, "newId" => $insertID);
                } else {
                    $errorInfo = '23505';  // 23505  unique_violation 
                    $pdo->rollback();
                    $result = $kontrol;
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
                }
            } else {
                $errorInfo = '23502';  /// 23502 user_id not_null_violation
                $pdo->rollback();
                $result = $kontrol;
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

                            
    /**
     * @author Okan CIRAN
     * @ Tüm sınav kayıtlarını döndürür !!
     * @version v 1.0  06.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillTempUserLists($params = array()) {
        try {
            if (isset($params['page']) && $params['page'] != "" && isset($params['rows']) && $params['rows'] != "") {
                $offset = ((intval($params['page']) - 1) * intval($params['rows']));
                $limit = intval($params['rows']);
            } else {
                $limit = 10;
                $offset = 0;
            }

            $sortArr = array();
            $orderArr = array();
            $addSql = NULL;
            if (isset($params['sort']) && $params['sort'] != "") {
                $sort = trim($params['sort']);
                $sortArr = explode(",", $sort);
                if (count($sortArr) === 1)
                    $sort = trim($params['sort']);
            } else {
                $sort = "  ad.name, ad.surname  ";
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
            if (isset($params['filterRules']) && $params['filterRules'] != "") {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND ad.name" . $sorguExpression . ' ';

                                break;
                            case 'surname':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND ad.surname" . $sorguExpression . ' ';

                                break;
                            case 'username':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.username" . $sorguExpression . ' ';

                                break;
                            case 'auth_email':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.auth_email" . $sorguExpression . ' ';

                                break;

                            case 'education_type':
                              $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                              $sorguStr.=" AND sd30.description" . $sorguExpression . ' ';

                              break;
                            case 'nick':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.nick" . $sorguExpression . ' ';

                                break;
                            case 'tcno':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.tcno" . $sorguExpression . ' ';

                                break;
                            case 'iban':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.iban" . $sorguExpression . ' ';

                                break;
                            case 'meslek':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.meslek" . $sorguExpression . ' ';

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


            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            //     $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            //    if (\Utill\Dal\Helper::haveRecord($opUserId)) {
            $sql = NULL;

            $sql = "   
                    SELECT
                        a.id,         
                        a.s_date, 
                        a.c_date, 
                        ad.name, 
                        ad.surname, 
                        a.username,  
                        ad.auth_email,         
                        a.active,                         
                        sd16.description AS state_active,  
                        a.op_user_id,
                        u.username AS op_user_name,
                        ad.act_parent_id, 
                        ad.auth_allow_id,                         
                        sd13.description AS auth_alow,  
                        ad.root_id ,
                        sar.name_tr as role ,
                        ad.birthday,ad.nick, ad.tcno, ad.iban, ad.meslek,
			ad.education_type_id,
			sd30.description AS education_type 
                    FROM info_users a  
                    INNER JOIN info_users_detail ad ON ad.deleted =0 AND ad.active =0 AND ad.root_id = a.id AND ad.language_parent_id = 0 
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd13 ON sd13.main_group = 13 AND ad.auth_allow_id = sd13.first_group AND sd13.deleted =0 AND sd13.active =0 AND sd13.language_parent_id =0
		    inner join  sys_acl_roles sar on sar.id = a.role_id and sar.active =0 and sar.deleted =0 and sar.id> 1 
                    INNER JOIN info_users u ON u.id = a.op_user_id   
                    LEFT JOIN sys_specific_definitions sd30 ON sd30.main_group =30 AND ad.education_type_id = sd30.first_group AND sd30.deleted =0 AND sd30.active =0 AND sd30.language_parent_id =0
                    WHERE   a.deleted =0   
                    " . $addSql . "
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
            //   echo debugPDO($sql, $parameters);                
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            $affectedRows = $statement->rowCount();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            /* } else {
              $errorInfo = '23502';   // 23502  user_id not_null_violation
              $errorInfoColumn = 'pk';
              return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
              }
             * 
             */
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }
    
    /**
     * @author Okan CIRAN
     * @ Tüm sınav kayıtlarını döndürür !!
     * @version v 1.0  06.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillTempUserListsRTC($params = array()) {
        try { 
            $sortArr = array();
            $orderArr = array();
            $addSql = NULL; 
            $sorguStr = null;
            if (isset($params['filterRules']) && $params['filterRules'] != "") {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND ad.name" . $sorguExpression . ' ';

                                break;
                            case 'surname':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND ad.surname" . $sorguExpression . ' ';

                                break;
                            case 'username':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.username" . $sorguExpression . ' ';

                                break;
                            case 'auth_email':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.auth_email" . $sorguExpression . ' ';

                                break;

                            case 'education_type':
                              $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                              $sorguStr.=" AND sd30.description" . $sorguExpression . ' ';

                              break;
                            case 'nick':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.nick" . $sorguExpression . ' ';

                                break;
                            case 'tcno':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.tcno" . $sorguExpression . ' ';

                                break;
                            case 'iban':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.iban" . $sorguExpression . ' ';

                                break;
                            case 'meslek':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND ad.meslek" . $sorguExpression . ' ';

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


            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            //     $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            //    if (\Utill\Dal\Helper::haveRecord($opUserId)) {
            $sql = NULL;

            $sql = "   SELECT count(id) as COUNT FROM ( 
                    SELECT
                        a.id,         
                        a.s_date, 
                        a.c_date, 
                        ad.name, 
                        ad.surname, 
                        a.username,  
                        ad.auth_email,         
                        a.active,                         
                        sd16.description AS state_active,  
                        a.op_user_id,
                        u.username AS op_user_name,
                        ad.act_parent_id, 
                        ad.auth_allow_id,                         
                        sd13.description AS auth_alow,  
                        ad.root_id ,
                        sar.name_tr as role ,
                        ad.birthday,ad.nick, ad.tcno, ad.iban, ad.meslek,
			ad.education_type_id,
			sd30.description AS education_type 
                    FROM info_users a  
                    INNER JOIN info_users_detail ad ON ad.deleted =0 AND ad.active =0 AND ad.root_id = a.id AND ad.language_parent_id = 0 
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd13 ON sd13.main_group = 13 AND ad.auth_allow_id = sd13.first_group AND sd13.deleted =0 AND sd13.active =0 AND sd13.language_parent_id =0
		    inner join  sys_acl_roles sar on sar.id = a.role_id and sar.active =0 and sar.deleted =0 and sar.id> 1 
                    INNER JOIN info_users u ON u.id = a.op_user_id   
                    LEFT JOIN sys_specific_definitions sd30 ON sd30.main_group =30 AND ad.education_type_id = sd30.first_group AND sd30.deleted =0 AND sd30.active =0 AND sd30.language_parent_id =0
                    WHERE   a.deleted =0   
                    " . $addSql . "
                    " . $sorguStr . " 
                    ) as ddsssddsds  "; 
            $statement = $pdo->prepare($sql);
            //  echo debugPDO($sql, $params);                
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            $affectedRows = $statement->rowCount();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            /* } else {
              $errorInfo = '23502';   // 23502  user_id not_null_violation
              $errorInfoColumn = 'pk';
              return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
              }
             * 
             */
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

    /**
     * @author Okan CIRAN    
     * parametre olarak gelen array deki 'id' li kaydın, info_users_details tablosundaki 
     * active = 0 ve deleted = 0 olan kaydın active alanını 1 yapar  !!     
     * @version v 1.0  29.01.2016
     * @param array $params 
     * @return array
     * @throws \PDOException
     */
    public function setUserDetailsDisables($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            // $pdo->beginTransaction();           
            $sql = "
                UPDATE info_users_detail
                    SET
                        c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) , 
                        active = 1 
                    WHERE root_id = :id AND active = 0 AND deleted = 0 
                    ";
             $statement = $pdo->prepare($sql); 
            $statement->bindValue(':id', $params['id'], \PDO::PARAM_INT);
         //  echo debugPDO($sql, $params);
            $update = $statement->execute();
            $affectedRows = $statement->rowCount();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);           
            return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
        } catch (\PDOException $e /* Exception $e */) {         
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /** 
     * @author Okan CIRAN
     * parametre olarak gelen array deki 'id' li kaydın, info_users tablosundaki 
     * alanlarını update eder !! username update edilmez.      
     * @version v 1.0  29.01.2016
     * @param array $params 
     * @return array
     * @throws \PDOException
     */
    public function updateInfoUsers($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory'); 
            $operationIdValue = -2;
            if ((isset($params['operation_type_id']) && $params['operation_type_id'] != "")) {
                $operationIdValue = $params['operation_type_id'];
            } 
            $languageId = NULL;
            if ((isset($params['language_id']) && $params['language_id'] != "")) {
                $operationIdValue = $params['language_id'];
                $addsql = " language_id =". intval($languageId).","; 
            } 
            $addsql =NULL;
            if ((isset($params['auth_allow_id']) && $params['auth_allow_id'] != "")) {
                $authAllowId = $params['auth_allow_id'];
                $addsql = " auth_allow_id =". intval($authAllowId).","; 
            } 
            
            $statement = $pdo->prepare("
                UPDATE info_users
                    SET
                        c_date = timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,                         
                        operation_type_id = :operation_type_id,
                        password = :password,                                       
                        op_user_id = :op_user_id ,
                        ".$addsql." 
                        active = :active
                    WHERE id = :id  
                    ");
            $statement->bindValue(':id', $params['id'], \PDO::PARAM_INT);            
            $statement->bindValue(':active', $params['active'], \PDO::PARAM_INT);                        
            $statement->bindValue(':operation_type_id', $operationIdValue, \PDO::PARAM_INT);
            $statement->bindValue(':password', $params['password'], \PDO::PARAM_STR);            
            $statement->bindValue(':op_user_id', $params['op_user_id'], \PDO::PARAM_INT);
            $update = $statement->execute();
            $affectedRows = $statement->rowCount();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);         
            return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
        } catch (\PDOException $e /* Exception $e */) {         
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @param array | null $args
     * @return Array
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
        $whereSql = "" ;
        if (isset($args['sort']) && $args['sort'] != "") {
            $sort = trim($args['sort']);
            $sortArr = explode(",", $sort);
            if (count($sortArr) === 1)
                $sort = trim($args['sort']);
        } else {
            $sort = "ad.name, ad.surname";
        }

        if (isset($args['order']) && $args['order'] != "") {
            $order = trim($args['order']);
            $orderArr = explode(",", $order);
            //print_r($orderArr);
            if (count($orderArr) === 1)
                $order = trim($args['order']);
        } else {
            $order = "ASC";
        }
        
        $languageCode = 'tr';
        $languageIdValue = 647;
        if (isset($args['language_code']) && $args['language_code'] != "") {
            $languageCode = $args['language_code'];
        }       
        $languageCodeParams = array('language_code' => $languageCode,);
        $languageId = $this->slimApp-> getBLLManager()->get('languageIdBLL');  
        $languageIdsArray= $languageId->getLanguageId($languageCodeParams);
        if (\Utill\Dal\Helper::haveRecord($languageIdsArray)) { 
             $languageIdValue = $languageIdsArray ['resultSet'][0]['id']; 
        }  
         
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $sql = "    
                    SELECT
                        a.id, 
                        ad.profile_public,                  
                        a.s_date, 
                        a.c_date, 
                        a.operation_type_id,                        
                        COALESCE(NULLIF(opx.operation_name, ''), op.operation_name_eng) AS operation_name,
                        ad.name, 
                        ad.surname, 
                        a.username, 
                        a.password, 
                        ad.auth_email,                   
                        ad.language_code, 
                        ad.language_id, 
                        l.language_eng as user_language,
			COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name,                        
                        a.active,                         
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                        ad.deleted,
                        COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,  			
                        a.op_user_id,
                        u.username AS op_user_name,
                        ad.act_parent_id, 
                        ad.auth_allow_id,                         
                        COALESCE(NULLIF(sd13x.description, ''), sd13.description_eng) AS auth_alow, 
                        ad.cons_allow_id,                        
                        COALESCE(NULLIF(sd14x.description, ''), sd14.description_eng) AS cons_allow,                   
                        ad.root_id,
                        a.consultant_id,
                        cons.name AS cons_name, 
                        cons.surname AS cons_surname,			 
                        COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS state_profile_public                        
                    FROM info_users a
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0                     
                    LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0                     
                    INNER JOIN info_users_detail ad ON ad.deleted =0 AND ad.active =0 AND ad.root_id = a.id AND ad.language_parent_id = 0 
                    LEFT JOIN sys_operation_types op ON op.id = a.operation_type_id AND op.deleted =0 AND op.active =0 AND op.language_parent_id =0
                    LEFT JOIN sys_operation_types opx ON (opx.id = a.operation_type_id OR opx.language_parent_id = a.operation_type_id) and opx.language_id =lx.id  AND opx.deleted =0 AND opx.active =0 
		    
		    INNER JOIN sys_specific_definitions sd13 ON sd13.main_group = 13 AND ad.auth_allow_id = sd13.first_group AND sd13.deleted =0 AND sd13.active =0 AND sd13.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd14 ON sd14.main_group = 14 AND ad.cons_allow_id = sd14.first_group AND sd14.deleted =0 AND sd14.active =0 AND sd14.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= ad.profile_public AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_parent_id =0

                    LEFT JOIN sys_specific_definitions sd13x ON sd13x.main_group = 13 AND sd13x.language_id = lx.id AND (sd13x.id = sd13.id OR sd13x.language_parent_id = sd13.id) AND sd13x.deleted =0 AND sd13x.active =0
                    LEFT JOIN sys_specific_definitions sd14x ON sd14x.main_group = 14 AND sd14x.language_id = lx.id AND (sd14x.id = sd14.id OR sd14x.language_parent_id = sd14.id) AND sd14x.deleted =0 AND sd14x.active =0
                    LEFT JOIN sys_specific_definitions sd15x ON sd15x.main_group = 15 AND sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0 
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.main_group = 16 AND sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    LEFT JOIN sys_specific_definitions sd19x ON sd19x.main_group = 19 AND sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
                    
                    INNER JOIN info_users u ON u.id = a.op_user_id                        
                    LEFT JOIN info_users_detail cons ON cons.root_id = a.consultant_id AND cons.cons_allow_id =2 
                 
                    WHERE a.deleted =0  
                    ".$whereSql."                   
                    ORDER BY  " . $sort . " "
                    . "" . $order . " "
                    . "LIMIT " . $pdo->quote($limit) . " "
                    . "OFFSET " . $pdo->quote($offset) . " ";
             
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
     * @param array | null $params
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');    
             
            $sql = "
                   SELECT 
                        count(a.id) as count                                 
                    FROM info_users a
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0                                         
                    INNER JOIN info_users_detail ad ON ad.deleted =0 AND ad.active =0 AND ad.root_id = a.id AND ad.language_parent_id = 0 
                    INNER JOIN sys_specific_definitions sd13 ON sd13.main_group = 13 AND ad.auth_allow_id = sd13.first_group AND sd13.deleted =0 AND sd13.active =0 AND sd13.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd14 ON sd14.main_group = 14 AND ad.cons_allow_id = sd14.first_group AND sd14.deleted =0 AND sd14.active =0 AND sd14.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= ad.profile_public AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_parent_id =0
                    INNER JOIN info_users u ON u.id = a.op_user_id                      
                    WHERE a.deleted = 0 
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
     * @param type $id
     * @param type $params
     * @return array
     * @throws PDOException
     */
    public function deletedAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $pdo->beginTransaction();
            $opUserIdParams = array('pk' =>  $params['pk'],);
            $opUserIdArray = $this->slimApp-> getBLLManager()->get('opUserIdBLL');  
            $opUserId = $opUserIdArray->getUserId($opUserIdParams); 
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];                 
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];
                            
                $url = null;
                if (isset($params['url']) && $params['url'] != "") {
                    $url = $params['url'];
                }    
                $m = null;
                if (isset($params['m']) && $params['m'] != "") {
                    $m = $params['m'];
                }  
                $a = null;
                if (isset($params['a']) && $params['a'] != "") {
                    $a = $params['a'];
                }  
                $operationIdValue =  0;
                $assignDefinitionIdValue = 0;
                $operationTypeParams = array('url' => $url, 'role_id' => $opUserRoleIdValue, 'm' => $m,'a' => $a,);
                $operationTypes = $this->slimApp-> getBLLManager()->get('operationsTypesBLL');  
                $operationTypesValue = $operationTypes->getDeleteOperationId($operationTypeParams);
                if (\Utill\Dal\Helper::haveRecord($operationTypesValue)) { 
                    $operationIdValue = $operationTypesValue ['resultSet'][0]['id']; 
                    $assignDefinitionIdValue = $operationTypesValue ['resultSet'][0]['assign_definition_id'];
                    if ($operationIdValue > 0) {
                            $url = null;
                        }
                }  
                $this->setUserDetailsDisables(array('id' => $opUserIdValue));
                $this->makeUserDeleted(array('id' => $opUserIdValue));
                $sql = " 
                    INSERT INTO info_users_detail(
                           profile_public,
                           s_date,
                           c_date,
                           operation_type_id, 
                           name,
                           surname,                                                                        
                           auth_email,  
                           act_parent_id,
                           auth_allow_id,
                           cons_allow_id,                           
                           language_id,
                           root_id,
                           role_id,
                           op_user_id,
                           language_id,
                           password,                           
                           active,
                           deleted,
                            ) 
                           SELECT 
                                profile_public,
                                s_date,
                                timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) , 
                                " . intval($operationIdValue) . " AS operation_type_id,
                                name,
                                surname,
                                auth_email,  
                                act_parent_id,
                                auth_allow_id,
                                cons_allow_id,                                
                                language_id,
                                root_id,
                                role_id,
                                " . intval($opUserIdValue) . " AS op_user_id,
                                language_id,
                                password,   
                               1,
                               1
                            FROM info_users_detail 
                            WHERE root_id  =" . intval($opUserIdValue) . " 
                                AND active =0 AND deleted =0  
                    "; 
                $statement_act_insert = $pdo->prepare($sql);   
                $insert_act_insert = $statement_act_insert->execute();
                $affectedRows = $statement_act_insert->rowCount();
                $insertID = $pdo->lastInsertId('info_users_detail_id_seq');
                $errorInfo = $statement_act_insert->errorInfo();
                /*
                 * ufak bir trik var. 
                 * işlem update oldugunda update işlemini yapan kişinin dil bilgisini kullanıcaz. 
                 * ancak delete işlemi oldugunda delete işlemini yapan user in dil bilgisini değil 
                 * silinen kaydı yapan kişinin dil bilgisini alıcaz.
                 */
                $consIdAndLanguageId = SysOperationTypes::getConsIdAndLanguageId(
                                  array('operation_type_id' =>$operationIdValue, 'id' => $params['id'],));
                if (\Utill\Dal\Helper::haveRecord($consIdAndLanguageId)) {
                    $ConsultantId = $consIdAndLanguageId ['resultSet'][0]['consultant_id'];
                    $languageIdValue = $consIdAndLanguageId ['resultSet'][0]['language_id'];                    
                }
                                
                $consultantProcessSendParams = array(
                                'op_user_id' => intval($opUserIdValue),
                                'operation_type_id' => intval($operationIdValue),
                                'table_column_id' => intval($insertID),
                                'cons_id' => intval($ConsultantId),
                                'preferred_language_id' => intval($languageIdValue),
                                'url' => $url, 
                                'assign_definition_id' => $assignDefinitionIdValue, // operasyon atama tipi
                        );
                    $setConsultantProcessSend = $this->slimApp-> getBLLManager()->get('consultantProcessSendBLL');  
                    $setConsultantProcessSendArray= $setConsultantProcessSend->insert($consultantProcessSendParams);
                    if ($setConsultantProcessSendArray['errorInfo'][0] != "00000" &&
                            $setConsultantProcessSendArray['errorInfo'][1] != NULL &&
                            $setConsultantProcessSendArray['errorInfo'][2] != NULL)
                        throw new \PDOException($setConsultantProcessSendArray['errorInfo']);   
                    
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
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
     * parametre olarak gelen array deki 'id' li kaydın, info_users tablosundaki private key ve value değerlerini oluşturur  !!     
     * @version v 1.0  26.01.2016
     * @param array $params 
     * @return array
     * @throws \PDOException
     */
    public function setPrivateKey($params = array()) {
        try { 
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $username = null;
                if (isset($params['username']) && $params['username'] != "") {
                    $username = $params['username'];
            } 
            $password = null;
                if (isset($params['password']) && $params['password'] != "") {
                    $password = $params['password'];
            }  
            
            $oid = mt_rand (); 
             /*
              
            select Pgp_sym_decrypt (dearmor('-----BEGIN PGP MESSAGE-----

ww0EBAMCrQNQgy4sj79u0jMBBaFI4e5KphUFOoHdxmPK2ZK174x+OSZzVETK6ZdpOrgMA4vweb58
XAbSoF1qhMj0SEI=
=KSLY
-----END PGP MESSAGE-----
')
, '1513821173', 'compress-algo=1, cipher-algo=bf')
   
              */               
            $sql= "
                WITH keyuret AS (
                select armor( pgp_sym_encrypt ('".$username."' , '".$oid."', 'compress-algo=1, cipher-algo=bf')) as sf_private_keyx,
                armor( pgp_sym_encrypt ('".$password."' , '".$oid."', 'compress-algo=1, cipher-algo=bf')) as passwordx    
                )                

                SELECT          
                    sf_private_keyx AS sf_private_keyz,
                    substring(sf_private_keyx,40,length( trim( sf_private_keyx))-140) AS sf_private_key_valuez,
                    passwordx AS passwordz,
                    true AS control
                FROM keyuret
                 " ;
            $statement = $pdo->prepare($sql);
            //  echo debugPDO($sql, $params);
            $statement->execute();
            $keyresult = $statement->fetchAll(\PDO::FETCH_ASSOC); 
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            
            $keyresult = array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $keyresult);
            $sf_private_keyz = NULL;
            $sf_private_key_valuez =NULL;
            $passwordz=NULL;
            if (\Utill\Dal\Helper::haveRecord($keyresult)) { 
                $sf_private_keyz= $keyresult ['resultSet'][0]['sf_private_keyz'];
                $sf_private_key_valuez= $keyresult ['resultSet'][0]['sf_private_key_valuez'];
                $passwordz= $keyresult ['resultSet'][0]['passwordz'];
            }
            if ($sf_private_keyz == NULL && $sf_private_keyz == "") {
                    $errorInfo[0] != "99999" ; /// private key uretilemedi.
                     throw new \PDOException($errorInfo[0]);
            } 
                            
            
            $pdoDevamsizlik = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');                    
            $sql= "
                UPDATE info_users
                SET
                    sf_private_key = '".$sf_private_keyz."', 
                    sf_private_key_value ='".$sf_private_key_valuez."',
                    password ='".$passwordz."',
                    oid  = '".$oid."'
                WHERE
                    id = :id" ;
             $statement = $pdoDevamsizlik->prepare($sql);
            // echo debugPDO($sql, $params);
            $statement->bindValue(':id', $params['id'], \PDO::PARAM_INT);
            $update = $statement->execute();
            $affectedRows = $statement->rowCount();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
                            
            return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
        } catch (\PDOException $e /* Exception $e */) {         
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

                            
    /**
     * @author Okan CIRAN
     * parametre olarak gelen array deki 'pk' nın, info_users tablosundaki user_id si değerini döndürür !!
     * param olarak  gelen pk in act_session tablosunda olması zorunludur.
     * firma tablosu codebase de olmadıgından simdilik orayı kapattım. firma tablosu yada benzer bir talo olursa modifiye edilecek.
     * @version v 1.0  26.01.2016
     * @param array $params 
     * @return array
     * @throws \PDOException
     */
    public function getUserId($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            /* 
            $addSql= NULL;
            if ((isset($params['firm_id']) && $params['firm_id'] != "")) {
                $addSql = " AND bb.firm_id = " . intval($params['firm_id']);
            } 
             */
            /*
                SELECT id AS user_id, 1=1 AS control, NULL AS user_firm_id FROM (
                    SELECT a.id , 	
                        CRYPT(a.sf_private_key_value,CONCAT('_J9..',REPLACE('" . $params['pk'] . "','*','/'))) = CONCAT('_J9..',REPLACE('" . $params['pk'] . "','*','/')) AS pkey,
                        COALESCE(NULLIF( bb.firm_id, NULL ), -95) AS user_firm_id
                    FROM info_users a
                    LEFT JOIN info_firm_users bb ON bb.user_id = a.id AND bb.deleted=0 ".$addSql."
                    WHERE a.active =0 AND a.deleted =0) AS logintable
                WHERE pkey = TRUE   
             */
            
            $sql = 
             /*    "   
                SELECT id AS user_id, 1=1 AS control ,role_id,language_id, NULL AS user_firm_id
                FROM (
                        SELECT a.id, a.role_id, a.language_id,
                        CRYPT(a.sf_private_key_value,CONCAT('_J9..',REPLACE('" . $params['pk'] . "','*','/'))) = CONCAT('_J9..',REPLACE(acts.public_key,'*','/')) AS pkey
                        FROM info_users a
                        INNER JOIN act_session acts ON acts.public_key is not null AND  
                            CRYPT(a.sf_private_key_value,CONCAT('_J9..',REPLACE('" . $params['pk'] . "','*','/'))) = CONCAT('_J9..',REPLACE(acts.public_key,'*','/'))
                        WHERE 
                            a.active =0 AND 
                            a.deleted =0 LIMIT 1) AS logintable 
                WHERE pkey = TRUE 
                "   ;
              * 
              */
                            
               /* "  
                SELECT  id AS user_id, 1=1 AS control ,role_id,language_id, NULL AS user_firm_id FROM (
                            SELECT  id, role_id, language_id,	
                                CRYPT(sf_private_key_value,CONCAT('_J9..',REPLACE('" . $params['pk'] . "','*','/'))) = CONCAT('_J9..',REPLACE('" . $params['pk'] . "','*','/')) as pkey                                
                            FROM info_users WHERE active =0 AND deleted =0) AS logintable
                        WHERE pkey = TRUE 
                ";*/ 
                    
               " SELECT top 1
                        act.public_key,
                        act.usid as user_id,
                        iu.sf_private_key_value,
                        iu.oid,
                        iu.role_id,
                        iu.language_id, 
                        NULL AS user_firm_id,
                        1 AS control
                    FROM  act_session act
                    INNER join info_users iu ON iu.active=0 AND iu.deleted =0 AND iu.id = act.usid
                    WHERE public_key ='".$params['pk']."'    
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
     * log databasine yeni kullanıcı için kayıt ekler                
     * @version v 1.0  09.03.2016
     * @param array $params 
     * @return array
     * @throws \PDOException
     */
    public function insertLogUser($params = array()) {
        try {         
            $pdoLog = $this->slimApp->getServiceManager()->get('pgConnectLogFactory');
            $pdoLog->beginTransaction();
                $sql = " 
                    INSERT INTO info_users(
                        username, 
                        sf_private_key_value, 
                        sf_private_key_value_temp,
                        oid
                        ) 
                    VALUES (
                        :username, 
                        :sf_private_key_value, 
                        :sf_private_key_value_temp,
                        :oid
                        )     
                    "; 
                $statement_log_insert = $pdoLog->prepare($sql);  
                $statement_log_insert->bindValue(':username', $params['username'], \PDO::PARAM_STR);
                $statement_log_insert->bindValue(':sf_private_key_value', $params['sf_private_key_value'], \PDO::PARAM_STR);
                $statement_log_insert->bindValue(':sf_private_key_value_temp', $params['sf_private_key_value_temp'], \PDO::PARAM_STR);
                $statement_log_insert->bindValue(':oid', $params['oid'], \PDO::PARAM_INT);
               //  echo debugPDO($sql, $params);
                $insert_log = $statement_log_insert->execute();
                $affectedRows = $statement_log_insert->rowCount();
                $errorInfo = $statement_log_insert->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdoLog->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
           
        } catch (\PDOException $e /* Exception $e */) {
            $pdoLog->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    /**
     * @author Okan CIRAN
     * parametre olarak gelen array deki 'id' li kaydın, info_users tablosundaki username ve private key değerlerini döndürür !!
     * @version v 1.0  09.03.2016
     * @param array $params 
     * @return array
     * @throws \PDOException
     */
    public function getUsernamePrivateKey($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $sql = " 
                SELECT 
                    id,
                    username, 
                    sf_private_key_value, 
                    null as sf_private_key_value_temp
                FROM info_users 
                WHERE 
                     id =" .intval( $params['id']) . "
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
     * @ userin firm id sini döndürür döndürür !!
     * codebase de firma bilgileri olmadıgı  için sablon olarak duruyor.
     * su an için sadece 1 firması varmış senaryosu için gecerli. 
     * @version v 1.0  29.02.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getUserFirmId($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            if (isset($params['user_id'])) {
                $userIdValue = $params['user_id'];
                $sql = " 
                SELECT                    
                   a.firm_id,
                   1=1 control
                FROM info_firm_users a 
                WHERE a.deleted =0 AND 
                      a.active =0 AND
                      a.user_id = " . intval($userIdValue) . " AND
                      a.language_parent_id = 0 AND 
                      a.language_parent_id =0 
                limit 1
                                 ";
                $statement = $pdo->prepare($sql);
                // echo debugPDO($sql, $params);
                $statement->execute();
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            } else {
                $errorInfo = '23502';   // 23502  user_id not_null_violation
                $errorInfoColumn = 'pk';
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
                            
    
    /**
     * @author Okan CIRAN
     * parametre olarak gelen 'id' li kaydın password unu update yapar  !!     
     * @version v 1.0  02.09.2016     
     * @param array | null $args
     * @param type $params
     * @return array
     * @throws PDOException
     */
    public function setPersonPassword($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $pdo->beginTransaction();
            $opUserId = $this->getUserId(array('pk' => $params['key']));
                            
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
              
                $languageIdValue = $opUserId ['resultSet'][0]['language_id']; 
                            
                /*
                 * kullanıcı için gerekli olan private key ve value değerleri yaratılılacak.                       
                 */
                $xcDeletedOnTheLink = InfoUsersSendingMail::setDeletedOnTheLinks(array('key' => $params['key']));
                if ($xcDeletedOnTheLink['errorInfo'][0] != "00000" && $xcDeletedOnTheLink['errorInfo'][1] != NULL && $xcDeletedOnTheLink['errorInfo'][2] != NULL)
                    throw new \PDOException($xcDeletedOnTheLink['errorInfo']);

                $affectedRows = $xcDeletedOnTheLink ['affectedRowsCount'];
                                
                if ($affectedRows == 1) {                                
                    $active = 0;    
                    $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];         
                    $url = null;
                    if (isset($params['url']) && $params['url'] != "") {
                        $url = $params['url'];
                    }    
                    $m = null;
                    if (isset($params['m']) && $params['m'] != "") {
                        $m = $params['m'];
                    }  
                    $a = null;
                    if (isset($params['a']) && $params['a'] != "") {
                        $a = $params['a'];
                    }  
                    $operationIdValue =  0;
                    $assignDefinitionIdValue = 0;
                    $operationTypeParams = array('url' => $url, 'role_id' => $opUserRoleIdValue, 'm' => $m,'a' => $a,);
                    $operationTypes = $this->slimApp->getServiceManager()->get('operationsTypesBLL');
                    $operationTypesValue = $operationTypes->getUpdateOperationId($operationTypeParams);
                    if (\Utill\Dal\Helper::haveRecord($operationTypesValue)) { 
                        $operationIdValue = $operationTypesValue ['resultSet'][0]['id']; 
                        $assignDefinitionIdValue = $operationTypesValue ['resultSet'][0]['assign_definition_id'];
                        if ($operationIdValue > 0) {
                            $url = null;
                        }
                    }       

                    /*
                     * parametre olarak gelen array deki 'id' li kaydın, info_users tablosundaki 
                     * alanlarını update eder !! username update edilmez.  
                     */
                    $this->updateInfoUsers(array('id' => $opUserIdValue,
                        'op_user_id' => $opUserIdValue,
                        'active' => $active,
                        'operation_type_id' => $operationIdValue,
                        'language_id' => $languageIdValue,
                        'auth_allow_id'=> 1,
                        'password' => $params['password'],
                    ));

                    /*
                     *  parametre olarak gelen array deki 'id' li kaydın, info_users_details tablosundaki 
                     * active = 0 ve deleted = 0 olan kaydın active alanını 1 yapar  !!
                     */
                    $this->setUserDetailsDisables(array('id' => $opUserIdValue));

                    $operationIdValueDetail = $operationIdValue; 
                    $sql = " 
                    INSERT INTO info_users_detail(
                           profile_public,  
                           operation_type_id,
                           active,
                           name,
                           surname,
                           auth_email,
                           language_id,
                           op_user_id,
                           root_id,
                           act_parent_id,
                           auth_allow_id,
                           password 
                            ) 
                           SELECT 
                                0 AS profile_public, 
                                " . intval($operationIdValueDetail) . " AS operation_type_id,
                                " . intval($active) . " AS active, 
                                name, 
                                surname,
                                auth_email,   
                                language_id,   
                                " . intval($opUserIdValue) . " AS op_user_id,
                                a.root_id,
                                a.act_parent_id,
                                1,
                                md5('" . $params['password'] . "') AS password
                            FROM info_users_detail a
                            WHERE root_id  =" . intval($opUserIdValue) . "                               
                                AND active =1 AND deleted =0 AND 
                                c_date = (SELECT MAX(c_date)  
						FROM info_users_detail WHERE root_id =a.root_id
						AND active =1 AND deleted =0)  
                    ";
                    $statementActInsert = $pdo->prepare($sql);
                    //  echo debugPDO($sql, $params);                                
                    $insertAct = $statementActInsert->execute();
                    $affectedRows = $statementActInsert->rowCount();
                    $insertID = $pdo->lastInsertId('info_users_detail_id_seq');
                    $errorInfo = $statementActInsert->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);

                    /*
                     * ufak bir trik var. 
                     * işlem update oldugunda update işlemini yapan kişinin dil bilgisini kullanıcaz. 
                     * ancak delete işlemi oldugunda delete işlemini yapan user in dil bilgisini değil 
                     * silinen kaydı yapan kişinin dil bilgisini alıcaz.
                     */
                    $consIdAndLanguageId = SysOperationTypes::getConsIdAndLanguageId(
                                      array('operation_type_id' =>$operationIdValue, 'id' => $params['id'],));
                    if (\Utill\Dal\Helper::haveRecord($consIdAndLanguageId)) {
                        $ConsultantId = $consIdAndLanguageId ['resultSet'][0]['consultant_id'];                       
                        // $languageIdValue = $consIdAndLanguageId ['resultSet'][0]['language_id'];                       
                    }
                                
                    $consultantProcessSendParams = array(
                                'op_user_id' => intval($opUserIdValue),
                                'operation_type_id' => intval($operationIdValue),
                                'table_column_id' => intval($insertID),
                                'cons_id' => intval($ConsultantId),
                                'preferred_language_id' => intval($languageIdValue),
                                'url' => $url, 
                                'assign_definition_id' => $assignDefinitionIdValue, // operasyon atama tipi
                        );
                    $setConsultantProcessSend = $this->slimApp-> getBLLManager()->get('consultantProcessSendBLL');  
                    $setConsultantProcessSendArray= $setConsultantProcessSend->insert($consultantProcessSendParams);
                    if ($setConsultantProcessSendArray['errorInfo'][0] != "00000" &&
                            $setConsultantProcessSendArray['errorInfo'][1] != NULL &&
                            $setConsultantProcessSendArray['errorInfo'][2] != NULL)
                        throw new \PDOException($setConsultantProcessSendArray['errorInfo']);   
                    
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
                } else {
                    $errorInfo = '23502';  /// 23502 user_id not_null_violation
                    $errorInfoColumn = 'key';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';  /// 23502 user_id not_null_violation
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
     * sesionId si gelen user in act_session tablosundaki kaydında bulunan 
     * public_key alanına pktemp i yazar.   !!
     * @version v 1.0  31.08.2016
     * @param array | null $args
     * @return array
     * @throws PDOException
     */
    public function updatePktempForSesionId($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');             
            $sql = " 
                UPDATE act_session
                SET public_key = :pktemp
                WHERE id = :sesionId
                    ";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':sesionId', $params['sesionId'], \PDO::PARAM_STR);
            $statement->bindValue(':pktemp', $params['pktemp'], \PDO::PARAM_STR);     
           // echo debugPDO($sql, $params);  
            $update = $statement->execute();
            $affectedRows = $statement->rowCount();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
        } catch (\PDOException $e /* Exception $e */) {   
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
     /**  
     * @author Okan CIRAN
     * @ userın (kısa) bilgisini döndürür  !!
     * @version v 1.0  10.01.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getUserShortInformation($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];    
                
                $sql = "  
                    SELECT  
                        unpk,
                        CAST(registration_date AS date) AS registration_date, 
                        name, 
                        surname,
                        auth_email,                      
                        language_code,                      
			language_id,
                        user_picture,
                        mem_type_id,
			COALESCE(NULLIF(mem_type, ''), mem_typez) AS mem_type,
                        mem_logo,
                        cons_allow
                    FROM ( 
                        SELECT
                            a.network_key AS unpk,
                            a.s_date AS registration_date, 
                            ad.name, 
                            ad.surname,
                            ad.auth_email,
                            COALESCE(NULLIF(lx.language_main_code, ''), 'en') AS language_code,  
                            COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
                            CASE COALESCE(NULLIF(TRIM(ad.picture), ''),'-')
                                    WHEN '-' THEN NULL
                                    ELSE CONCAT(COALESCE(NULLIF(concat(sps.folder_road,'/'), '/'),''),sps.members_folder,'/' , TRIM(ad.picture))
                            END AS user_picture,
                            COALESCE(NULLIF(smt.id, NULL), smtz.id) AS mem_type_id,			
                            COALESCE(NULLIF(smtx.mem_type, ''), smt.mem_type_eng) AS mem_type,
                            COALESCE(NULLIF(smtzx.mem_type, ''), smtz.mem_type_eng) AS mem_typez ,                            
                            CASE COALESCE(NULLIF(COALESCE(NULLIF(smt.id, NULL), smtz.id), NULL),4)
				WHEN 4 THEN CONCAT(COALESCE(NULLIF(concat(sps.folder_road,'/'), '/'),''),sps.logos_folder,'/membership/'  ,'standard.png')
				ELSE CONCAT(COALESCE(NULLIF(concat(sps.folder_road,'/'), '/'),''),sps.logos_folder,'/membership/' , smt.logo)
                            END AS mem_logo ,
                            a.cons_allow_id =2 AS cons_allow
                        FROM info_users a
                        INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0                                                    
                        INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0                        
                        LEFT JOIN sys_language lx ON (lx.id = l.id OR lx.language_parent_id = l.id) AND lx.deleted =0 AND lx.active =0
                        LEFT JOIN info_firm_users ifu ON ifu.user_id = a.id and ifu.active =0 and ifu.deleted =0 		        
                        INNER JOIN info_users_detail ad ON ad.deleted =0 AND ad.active =0 AND ad.root_id = a.id AND ad.language_parent_id = 0
                        LEFT JOIN info_users_detail adx ON adx.deleted =0 AND adx.active =0 AND (adx.id = ad.id OR adx.language_parent_id = ad.id) AND adx.active =0 AND adx.deleted =0 
                        LEFT JOIN info_users_membership_types iumt ON iumt.user_id = a.id AND iumt.active =0 AND iumt.deleted =0 
                        LEFT JOIN sys_membership_periods smp ON smp.id=iumt.membership_periods_id AND smp.active =0 AND iumt.deleted =0 
                        LEFT JOIN sys_membership_types smt ON smt.id = smp.mems_type_id AND smt.active =0 AND smt.deleted =0 
                        LEFT JOIN sys_membership_types smtx ON (smtx.id = smt.id OR smtx.language_parent_id = smt.id) AND smtx.active =0 AND smtx.deleted =0 AND smtx.language_id = COALESCE(NULLIF(lx.id, NULL), 385) 
                        LEFT JOIN sys_membership_types smtz ON smtz.id = 4 AND smtz.active =0 AND smtz.deleted =0 AND smtz.language_parent_id = 0
                        LEFT JOIN sys_membership_types smtzx ON (smtzx.id = smtz.id OR smtzx.language_parent_id = smtz.id) AND smtzx.active =0 AND smtzx.deleted =0 AND smtzx.language_id = COALESCE(NULLIF(lx.id, NULL), 385)                     
                        WHERE a.deleted =0 AND
                              a.id = ".intval($opUserIdValue)."
                        ) as xctable          
                   ";
                $statement = $pdo->prepare($sql);
               //echo debugPDO($sql, $params);
                $statement->execute();
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            } else {
                $errorInfo = '23502';   // 23502  user_id not_null_violation
                $errorInfoColumn = 'pk';
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }
    
    /**
     * @author Okan CIRAN
     * @ user in kayıt bilgilerini  döndürür !!     
     * @version v 1.0  17.01.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillUsersProfileInformation($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];                
                $languageCode = 'tr';
                $languageIdValue = 647;
                if (isset($params['language_code']) && $params['language_code'] != "") {
                    $languageCode = $params['language_code'];
                }       
                $languageCodeParams = array('language_code' => $languageCode,);                
                $languageId = $this->slimApp-> getBLLManager()->get('languageIdBLL');  
                $languageIdsArray= $languageId->getLanguageId($languageCodeParams);
                if (\Utill\Dal\Helper::haveRecord($languageIdsArray)) { 
                     $languageIdValue = $languageIdsArray ['resultSet'][0]['id']; 
                }   
                $addSql =" a.id = " . intval($opUserIdValue) . " ";
                $unpk = "-1";                            
                if ((isset($params['unpk']) && $params['unpk'] != "")) { 
                    $unpk = $params['unpk']; 
                    //$addInner = " INNER JOIN info_users iu ON iu.id = ifu.user_id and iu.active =0 and iu.deleted =0";
                    $addSql =" AND a.network_key = '".$unpk."'" ;                    
                }
                      
                $sql = "
                    SELECT  
			ad.id,
                        a.network_key AS unpk,
                        CAST(a.s_date AS DATE) AS registration_date, 
                        ad.name, 
                        ad.surname,
                        a.auth_allow_id, 
                        COALESCE(NULLIF(sd13x.description, ''), sd13.description_eng) AS auth_alow, 
                        ad.auth_email,  
                        ad.language_id AS preferred_language_id,
                        l.language_eng AS preferred_language_name,
			a.active,
			COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
			ad.profile_public,
			COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS state_profile_public,
			CASE COALESCE(NULLIF(TRIM(ad.picture), ''),'-') 
				WHEN '-' THEN NULL
				ELSE CONCAT(COALESCE(NULLIF(concat(sps.folder_road,'/'), '/'),''),sps.members_folder,'/' ,COALESCE(NULLIF(ad.picture, ''),'image_not_found.png')) END AS picture
                    FROM info_users a
                    INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0  
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    INNER JOIN info_users_detail ad ON ad.deleted =0 AND ad.active =0 AND ad.root_id = a.id AND ad.language_parent_id = 0
                    INNER JOIN sys_specific_definitions sd13 ON sd13.main_group = 13 AND ad.auth_allow_id = sd13.first_group AND sd13.deleted =0 AND sd13.active =0 AND sd13.language_parent_id =0
                    LEFT JOIN sys_specific_definitions sd13x ON (sd13x.id = sd13.id OR sd13.language_parent_id = sd13.id) AND sd13x.language_id = lx.id AND sd13x.deleted =0 AND sd13x.active =0
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= ad.active AND sd16.language_id = ad.language_id AND sd16.deleted = 0 AND sd16.active = 0
		    LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16.language_parent_id = sd16.id) AND sd16x.language_id = lx.id AND sd16x.deleted =0 AND sd16x.active =0
		    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= ad.profile_public AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_parent_id =0
		    LEFT JOIN sys_specific_definitions sd19x ON (sd19x.id = sd19.id OR sd19.language_parent_id = sd19.id) AND sd19x.language_id = lx.id AND sd19x.deleted =0 AND sd19x.active =0
                    LEFT JOIN info_users_detail adx ON adx.deleted =0 AND adx.active =0 AND (adx.root_id = a.id OR adx.language_parent_id = a.id) AND adx.language_id = lx.id
		    WHERE a.deleted =0  
			 ".$addSql."
                    ORDER BY ad.id DESC
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
            } else {
                $errorInfo = '23502';   // 23502  user_id not_null_violation
                $errorInfoColumn = 'pk';
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

                            
                            
    
}
