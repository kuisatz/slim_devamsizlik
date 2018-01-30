<?php

/**
 *Framework 
 *
 * @link 
 * @copyright Copyright (c) 2017
 * @license   
 */
use  \Utill\Mail\PhpMailer\MailWrapper as sanalmail;
namespace DAL\PDO;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CIRAN
 */
class InfoDuyuru extends \DAL\DalSlim {
  
    /**
     * @author Okan CIRAN
     * @ info_duyuru tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0 22.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $userId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($userId)) {
                $userIdValue = $userId ['resultSet'][0]['user_id'];
                $statement = $pdo->prepare(" 
                UPDATE info_duyuru
                SET  deleted= 1 , active = 1 ,
                     op_user_id = " . $userIdValue . "     
                WHERE id = :id");
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
     * @ info_duyuru tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  22.10.2017   
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
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
            $statement = $pdo->prepare("
                    SELECT 
                        a.id,
                        a.user_id,
                        COALESCE(NULLIF(iudx.name, ''), iud.name) AS name,
                        COALESCE(NULLIF(iudx.surname, ''), iud.surname) AS surname,                                                
			COALESCE(NULLIF(ax.about, ''), a.about_eng) AS about,
			a.about_eng,
			COALESCE(NULLIF(ax.verbal1_title, ''), a.verbal1_title_eng) AS verbal1_title,
			a.verbal1_title_eng,
			COALESCE(NULLIF(ax.verbal1, ''), a.verbal1_eng) AS verbal1,
			a.verbal1_eng,
			COALESCE(NULLIF(ax.verbal2_title, ''), a.verbal2_title_eng) AS verbal2_title,
			a.verbal2_title_eng,
			COALESCE(NULLIF(ax.verbal2, ''), a.verbal2_eng) AS verbal2,
			a.verbal2_eng,
			COALESCE(NULLIF(ax.verbal3_title, ''), a.verbal3_title_eng) AS verbal3_title,
			a.verbal3_title_eng,
			COALESCE(NULLIF(ax.verbal3, ''), a.verbal3_eng) AS verbal3,
			a.verbal3_eng, 
			a.s_date,
                        a.c_date,
                        a.profile_public,
                        COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS state_profile_public,
                        a.operation_type_id,
                        COALESCE(NULLIF(opx.operation_name, ''), op.operation_name_eng) AS operation_name,
			a.act_parent_id,
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name,		                                                                     
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
                        a.deleted,
			COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,
                        a.op_user_id,
                        u.username AS op_user,
                        a.cons_allow_id,
                        COALESCE(NULLIF(sd14x.description, ''), sd14.description_eng) AS cons_allow,
                        a.language_parent_id,
                        uu.network_key AS npk
                    FROM info_duyuru a                     
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN info_duyuru ax ON (ax.id = a.id OR ax.language_parent_id=a.id) AND ax.active = 0 AND ax.deleted = 0 AND ax.language_id =lx.id  
                    INNER JOIN info_users uu ON uu.id = a.user_id                    
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.deleted =0 AND iud.active =0 AND iud.language_parent_id =0  
                    LEFT JOIN info_users_detail iudx ON (iudx.id = iud.id OR iudx.language_parent_id = iud.id) AND iudx.deleted =0 AND iudx.active =0  AND iudx.language_id =lx.id                                          
                    INNER JOIN info_users u ON u.id = a.op_user_id
                    
                    INNER JOIN sys_operation_types op ON op.id = a.operation_type_id AND op.language_id =l.id  AND op.deleted =0 AND op.active =0
                    LEFT JOIN sys_operation_types opx ON (opx.id = a.operation_type_id OR opx.language_parent_id = a.operation_type_id) and opx.language_id =lx.id  AND opx.deleted =0 AND opx.active =0
                    
		    INNER JOIN sys_specific_definitions sd14 ON sd14.main_group = 14 AND sd14.language_id = l.id  AND a.cons_allow_id = sd14.first_group  AND sd14.deleted =0 AND sd14.active =0
		    INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = l.id AND sd15.deleted =0 AND sd15.active =0 
		    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = l.id AND sd16.deleted = 0 AND sd16.active = 0
		    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.profile_public AND sd19.language_id = l.id AND sd19.deleted = 0 AND sd19.active = 0
                    
                    LEFT JOIN sys_specific_definitions sd14x ON sd14x.language_id = lx.id AND (sd14x.id = sd14.id OR sd14x.language_parent_id = sd14.id) AND sd14x.deleted =0 AND sd14x.active =0
                    LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id = lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0 
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
		    WHERE                          
                        a.language_parent_id =0
		    ORDER BY name,surname
                          ");
            $statement->execute();
            $result = $statement->fetcAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_duyuru tablosunda name sutununda daha önce oluşturulmuş mu? 
     * @version v 1.0 15.01.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $addSql = " AND a.deleted =0  ";
            if (isset($params['id'])) {
                $addSql .= " AND a.id != " . intval($params['id']);
            }
            $sql = " 
            SELECT  
                a.baslik AS name , 
                a.baslik AS value , 
                a.baslik = " .  ($params['baslik']) . " AS control,
                CONCAT(a.baslik, ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message                             
            FROM info_duyuru a             
            WHERE a.baslik = " .  ($params['baslik']) . "               
                AND a.active = 0 
                AND a.deleted = 0   
                   " . $addSql . "                  
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

    /**
     * @author Okan CIRAN
     * @ info_duyuru tablosundan parametre olarak  gelen id kaydını aktifliğini 1 = pasif yapar. !!
     * @version v 1.0  09.02.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            //$pdo->beginTransaction();
            $statement = $pdo->prepare(" 
                UPDATE info_duyuru
                SET                         
                    c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,                     
                    active = 1                    
                WHERE id = " .intval($params['id']) );            
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
     * @ info_duyuru tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  22.10.2017
     * @param array | null $args
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
                    
                $subjectId = 0;
                if ((isset($params['subject_id']) && $params['subject_id'] != "")) {
                    $subjectId = intval($params['subject_id']);
                }
                $examId = 0;
                if ((isset($params['exam_id']) && $params['exam_id'] != "")) {
                    $examId = intval($params['exam_id']);
                }
           
                $sql = "
                INSERT INTO info_dynk(  
                        op_user_id, 
                        subject_id, 
                        exam_id, 
                        act_parent_id, 
                        baslik, 
                        ozet, 
                        detay, 
                        description, 
                        bitis_tarihi
                       )
                VALUES ( 
                        " . intval($opUserIdValue) . ", 
                        " . intval($subjectId) . ",
                        " . intval($examId) . " ,
                        (SELECT last_value FROM info_dynk_id_seq),
                        '" . ($params['baslik']) . "',
                        '" . ($params['ozet']) . "',
                        '" . ($params['detay']) . "',
                        '" . ($params['description']) . "',
                        '" . ($params['bitis_tarihi']) . "' ,
                            )   ";
                    $statement = $pdo->prepare($sql);
                    // echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $insertID = $pdo->lastInsertId('info_dynk_id_seq');
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
                } else {
                    $errorInfo = '23505';
                    $errorInfoColumn = 'question';
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
     * info_duyuru tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  22.10.2017
     * @param array | null $args
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
              
                    $kontrol = $this->haveRecords( $params );
                    if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                        $this->makePassive(array('id' => $params['id']));
                        $operationIdValue = -2; 
                       
                        $subjectId = 0;
                        if ((isset($params['subject_id']) && $params['subject_id'] != "")) {
                            $subjectId = intval($params['subject_id']);
                        }
                        $examId = 0;
                        if ((isset($params['exam_id']) && $params['exam_id'] != "")) {
                            $examId = intval($params['exam_id']);
                        }
                     
                        $sql = " 
                        INSERT INTO info_duyuru(
                                op_user_id, 
                                subject_id, 
                                exam_id, 
                                act_parent_id, 
                                baslik, 
                                ozet, 
                                detay, 
                                description, 
                                bitis_tarihi
                            )                        
                        SELECT   
                            " . intval($opUserIdValue) . " AS op_user_id, 
                            " . intval($subjectId) . " AS subject_id, 
                            " . intval($examId) . " AS exam_id, 
                            act_parent_id,                            
                            CAST('" . $params['baslik'] . "' AS character varying(3000)) AS baslik,
                            CAST('" . $params['ozet'] . "' AS character varying(3000)) AS ozet,
                            CAST('" . $params['detay'] . "' AS character varying(150)) AS detay,
                            CAST('" . $params['description'] . "' AS character varying(2000)) AS description,
                            '" .  $params['bitis_tarihi'] . "' AS bitis_tarihi 
                        FROM info_duyuru 
                        WHERE id =  " . intval($params['id']) . " 
                        ";
                        $statement_act_insert = $pdo->prepare($sql);
                   // echo debugPDO($sql, $params);
                        $insert_act_insert = $statement_act_insert->execute();
                        $affectedRows = $statement_act_insert->rowCount();                                         
                        $insertID = $pdo->lastInsertId('info_duyuru_id_seq');                               
                        $errorInfo = $insert_act_insert->errorInfo();
                        if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                            throw new \PDOException($errorInfo[0]); 
               
                        $pdo->commit();
                        return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
                    } else {
                        // 23505  unique_violation
                        $errorInfo = '23505';
                        $pdo->rollback();
                        $result = $kontrol;
                        return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
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
     * @ Gridi doldurmak için info_duyuru tablosundan kayıtları döndürür !!
     * @version v 1.0  22.10.2017
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
        $whereSql = "";
        if (isset($args['sort']) && $args['sort'] != "") {
            $sort = trim($args['sort']);
            $sortArr = explode(",", $sort);
            if (count($sortArr) === 1)
                $sort = trim($args['sort']);
        } else {
            $sort = "name,surname";
        }

        if (isset($args['order']) && $args['order'] != "") {
            $order = trim($args['order']);
            $orderArr = explode(",", $order);
            if (count($orderArr) === 1)
                $order = trim($args['order']);
        } else {
            $order = "ASC";
        }
        $languageId = NULL;
        $languageIdValue = 647;
        if ((isset($args['language_code']) && $args['language_code'] != "")) {
            $languageId = SysLanguage::getLanguageId(array('language_code' => $args['language_code']));
            if (\Utill\Dal\Helper::haveRecord($languageId)) {
                $languageIdValue = $languageId ['resultSet'][0]['id'];
            }
        } 

        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "                      
                    SELECT 
                        a.id,
                        a.user_id,
                        COALESCE(NULLIF(iudx.name, ''), iud.name) AS name,
                        COALESCE(NULLIF(iudx.surname, ''), iud.surname) AS surname,                                                
			COALESCE(NULLIF(ax.about, ''), a.about_eng) AS about,
			a.about_eng,
			COALESCE(NULLIF(ax.verbal1_title, ''), a.verbal1_title_eng) AS verbal1_title,
			a.verbal1_title_eng,
			COALESCE(NULLIF(ax.verbal1, ''), a.verbal1_eng) AS verbal1,
			a.verbal1_eng,
			COALESCE(NULLIF(ax.verbal2_title, ''), a.verbal2_title_eng) AS verbal2_title,
			a.verbal2_title_eng,
			COALESCE(NULLIF(ax.verbal2, ''), a.verbal2_eng) AS verbal2,
			a.verbal2_eng,
			COALESCE(NULLIF(ax.verbal3_title, ''), a.verbal3_title_eng) AS verbal3_title,
			a.verbal3_title_eng,
			COALESCE(NULLIF(ax.verbal3, ''), a.verbal3_eng) AS verbal3,
			a.verbal3_eng, 
			a.s_date,
                        a.c_date,
                        a.profile_public,
                        COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS state_profile_public,
                        a.operation_type_id,
                        COALESCE(NULLIF(opx.operation_name, ''), op.operation_name_eng) AS operation_name,
			a.act_parent_id,
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name,		                                                                     
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
                        a.deleted,
			COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,
                        a.op_user_id,
                        u.username AS op_user,
                        a.cons_allow_id,
                        COALESCE(NULLIF(sd14x.description, ''), sd14.description_eng) AS cons_allow,
                        a.language_parent_id,
                        uu.network_key AS npk
                    FROM info_duyuru a                     
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN info_duyuru ax ON (ax.id = a.id OR ax.language_parent_id=a.id) AND ax.active = 0 AND ax.deleted = 0 AND ax.language_id =lx.id  
                    INNER JOIN info_users uu ON uu.id = a.user_id                    
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.deleted =0 AND iud.active =0 AND iud.language_parent_id =0  
                    LEFT JOIN info_users_detail iudx ON (iudx.id = iud.id OR iudx.language_parent_id = iud.id) AND iudx.deleted =0 AND iudx.active =0  AND iudx.language_id =lx.id                                          
                    INNER JOIN info_users u ON u.id = a.op_user_id
                    
                    INNER JOIN sys_operation_types op ON op.id = a.operation_type_id AND op.language_id =l.id  AND op.deleted =0 AND op.active =0
                    LEFT JOIN sys_operation_types opx ON (opx.id = a.operation_type_id OR opx.language_parent_id = a.operation_type_id) and opx.language_id =lx.id  AND opx.deleted =0 AND opx.active =0
                    
		    INNER JOIN sys_specific_definitions sd14 ON sd14.main_group = 14 AND sd14.language_id = l.id  AND a.cons_allow_id = sd14.first_group  AND sd14.deleted =0 AND sd14.active =0
		    INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = l.id AND sd15.deleted =0 AND sd15.active =0 
		    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = l.id AND sd16.deleted = 0 AND sd16.active = 0
		    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.profile_public AND sd19.language_id = l.id AND sd19.deleted = 0 AND sd19.active = 0
                    
                    LEFT JOIN sys_specific_definitions sd14x ON sd14x.language_id = lx.id AND (sd14x.id = sd14.id OR sd14x.language_parent_id = sd14.id) AND sd14x.deleted =0 AND sd14x.active =0
                    LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id = lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0 
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
		    WHERE  
                        a.deleted =0 AND 
                        a.active =0 AND 
                        a.language_parent_id =0		    
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
     * @ Gridi doldurmak için info_duyuru tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  22.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
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
            $whereSQL = " WHERE a.deleted = 0 AND a.active =0 AND a.language_parent_id =0 "; 

            $sql = "
                SELECT 
                    COUNT(a.id) AS COUNT
                FROM info_duyuru a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                INNER JOIN info_users uu ON uu.id = a.user_id
                INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.deleted =0 AND iud.active =0 AND iud.language_parent_id =0
                INNER JOIN info_users u ON u.id = a.op_user_id                
                INNER JOIN sys_operation_types op ON op.id = a.operation_type_id AND op.language_id =l.id  AND op.deleted =0 AND op.active =0
                INNER JOIN sys_specific_definitions sd14 ON sd14.main_group = 14 AND sd14.language_id = l.id  AND a.cons_allow_id = sd14.first_group  AND sd14.deleted =0 AND sd14.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = l.id AND sd15.deleted =0 AND sd15.active =0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = l.id AND sd16.deleted = 0 AND sd16.active = 0
                INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.profile_public AND sd19.language_id = l.id AND sd19.deleted = 0 AND sd19.active = 0
                " . $whereSQL . "'
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
     * delete olayında önce kaydın active özelliğini pasif e olarak değiştiriyoruz. 
     * daha sonra deleted= 1 ve active = 1 olan kaydı oluşturuyor. 
     * böylece tablo içerisinde loglama mekanizması için gerekli olan kayıt oluşuyor.
     * @version 22.10.2017 
     * @param type $id
     * @param type $params
     * @return array
     * @throws PDOException
     */
    public function deletedAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id']; 
                     
                $this->makePassive(array('id' => $params['id']));
                $operationIdValue = -3;

                $sql = " 
                 INSERT INTO info_duyuru(
                        op_user_id, 
                        subject_id, 
                        exam_id, 
                        act_parent_id, 
                        baslik, 
                        ozet, 
                        detay, 
                        description, 
                        bitis_tarihi,
                        active,
                        deleted
                    )                        
                SELECT   
                    " . intval($opUserIdValue) . " AS op_user_id, 
                    subject_id, 
                    exam_id, 
                    act_parent_id,                            
                    baslik,
                    ozet,
                    detay,
                    description,
                    bitis_tarihi ,
                    1,1
                FROM info_duyuru 
                WHERE id =  " . intval($params['id']) . " 
                ";
                $statement_act_insert = $pdo->prepare($sql);

                $insert_act_insert = $statement_act_insert->execute();
                // echo debugPDO($sql, $params);
                $affectedRows = $statement_act_insert->rowCount();
                $insertID = $pdo->lastInsertId('info_duyuru_id_seq');

                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]); 
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
                    
                 
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
     * @ duyuruları döndürür !!
     * @version v 1.0  22.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillNotice($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
           
           
             
            $sql = "     
                SELECT  distinct id, baslik ,
                                ozet,
                                detay ,
                                subject_id ,
                                exam_id    FROM  
		((
                            SELECT  
                                CAST(random()*100-1 AS int) AS ccc,  
                                id, 
				baslik ,
                                ozet,
                                detay ,
                                subject_id ,
                                exam_id  
                            FROM info_duyuru  
                            WHERE  
				subject_id = 0 and exam_id =0 
                            ORDER BY ccc DESC
                            limit 5   
                        )  
                union 
                        (
                            SELECT 
                                CAST(random()*100-1 AS int) AS ccc,
                                a.id, 
                                a.baslik ,
                                a.ozet,
                                a.detay ,
                                a.subject_id ,
                                a.exam_id  
                            FROM info_duyuru a
                            where
                               ( a.subject_id > 0 or exam_id >0) and 
                                 current_date < bitis_tarihi
                            ORDER BY ccc DESC 
			limit 5 
                        ) 
                    ) AS DDS
                ORDER BY subject_id,exam_id  DESC 
                limit 5
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

  
 
}
