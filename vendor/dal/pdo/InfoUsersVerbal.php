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
 * @author Okan CİRANĞ
 */
class InfoUsersVerbal extends \DAL\DalSlim {
  
    /**
     * @author Okan CIRAN
     * @ info_users_verbal tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0 23.06.2016
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
                UPDATE info_users_verbal
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
     * @ info_users_verbal tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  23.06.2016   
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
                    FROM info_users_verbal a                     
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN info_users_verbal ax ON (ax.id = a.id OR ax.language_parent_id=a.id) AND ax.active = 0 AND ax.deleted = 0 AND ax.language_id =lx.id  
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
     * @ info_users_verbal tablosunda name sutununda daha önce oluşturulmuş mu? 
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
                a.firm_id AS name , 
                a.firm_id AS value , 
                cast(1 as bit) AS control,
                CONCAT(a.firm_id, ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message                             
            FROM info_users_verbal a             
            WHERE a.firm_id = " . intval($params['firm_id']) . "               
                AND a.active = 0 
                AND a.deleted = 0  
                AND a.language_parent_id =0
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
     * @ info_users_verbal tablosundan parametre olarak  gelen id kaydını aktifliğini 1 = pasif yapar. !!
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
                UPDATE info_users_verbal
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
     * @ info_users_verbal tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  23.06.2016
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
      
                    $kontrol = $this->haveRecords(array('firm_id' => $getFirmId,));
                    if (!\Utill\Dal\Helper::haveRecord($kontrol)) {                          
                        $operationIdValue = -1;
                        $operationId = SysOperationTypes::getTypeIdToGoOperationId(
                                        array('parent_id' => 3, 'main_group' => 3, 'sub_grup_id' => 32, 'type_id' => 1,));
                        if (\Utill\Dal\Helper::haveRecord($operationId)) {
                            $operationIdValue = $operationId ['resultSet'][0]['id'];
                        }

                        $ConsultantId = 1001;
                        $getConsultant = SysOsbConsultants::getConsultantIdForTableName(array('table_name' => 'info_users_verbal' , 'operation_type_id' => $operationIdValue));
                        if (\Utill\Dal\Helper::haveRecord($getConsultant)) {
                            $ConsultantId = $getConsultant ['resultSet'][0]['consultant_id'];
                        }

                        $profilePublic = 0;
                        if ((isset($params['profile_public']) && $params['profile_public'] != "")) {
                            $profilePublic = intval($params['profile_public']);
                        }
                        $countryId = 91;
                        if ((isset($params['country_id']) && $params['country_id'] != "")) {
                            $countryId = intval($params['country_id']);
                        }

                        $languageId = NULL;
                        $languageIdValue = 647;
                        if ((isset($params['language_code']) && $params['language_code'] != "")) {
                            $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                            if (\Utill\Dal\Helper::haveRecord($languageId)) {
                                $languageIdValue = $languageId ['resultSet'][0]['id'];
                            }
                        }
                        
                       
                        $xc= InfoFirmProfile::updateVerbal(array(
                            'op_user_id' => $opUserIdValue, 
                            'firm_id' => $getFirmId,
                            'profile_public' => $profilePublic,
                            'language_id' =>$languageIdValue,                            
                            'foundation_yearx' => $params['foundation_yearx'],
                            'country_id' =>  $countryId,
                            'firm_name' =>  $params['firm_name'],
                            'firm_name_eng' => $params['firm_name_eng'],
                            'firm_name_short' => $params['firm_name_short'],
                            'firm_name_short_eng' => $params['firm_name_short_eng'],
                            'web_address' => $params['web_address'],
                            'tax_office' => $params['tax_office'],
                            'tax_no' => $params['tax_no'],
                            'description' => $params['description'],
                            'description_eng' => $params['description_eng'],
                            'sgk_sicil_no' => $params['sgk_sicil_no'],
                            'duns_number' => $params['duns_number'],
                            'logo' => $params['logo'], 
                            )   
                                );
                        
                        if ($xc['errorInfo'][0] != "00000" && $xc['errorInfo'][1] != NULL && $xc['errorInfo'][2] != NULL)
                            throw new \PDOException($xc['errorInfo']);
                        
                        
                        $sql = " 
                        INSERT INTO info_users_verbal(
                            firm_id, 
                            consultant_id,
                            operation_type_id, 
                            language_id,
                            op_user_id,
                            profile_public,                        
                            act_parent_id, 
                            about,
                            about_eng,
                            verbal1_title, 
                            verbal1, 
                            verbal2_title, 
                            verbal2, 
                            verbal3_title, 
                            verbal3, 
                            verbal1_title_eng, 
                            verbal1_eng, 
                            verbal2_title_eng, 
                            verbal2_eng, 
                            verbal3_title_eng, 
                            verbal3_eng
                            )
                        VALUES (
                            :firm_id, 
                            " . intval($ConsultantId) . ",
                            " . intval($operationIdValue) . ", 
                            " . intval($languageIdValue) . ", 
                            " . intval($opUserIdValue) . ", 
                            " . intval($profilePublic) . ",                         
                            (SELECT last_value FROM info_users_verbal_id_seq),
                            cast(:about AS character varying(3000)),
                            cast(:about_eng AS character varying(3000)),
                            cast(:verbal1_title AS character varying(150)),
                            cast(:verbal1 AS character varying(2000)),
                            cast(:verbal2_title AS character varying(150)),
                            cast(:verbal2 AS character varying(2000)),
                            cast(:verbal3_title AS character varying(150)),
                            cast(:verbal3 AS character varying(2000)),
                            cast(:verbal1_title_eng AS character varying(150)),
                            cast(:verbal1_eng AS character varying(2000)), 
                            cast(:verbal2_title_eng AS character varying(150)),
                            cast(:verbal2_eng AS character varying(2000)),
                            cast(:verbal3_title_eng AS character varying(150)),
                            cast(:verbal3_eng AS character varying(2000))
                             )";
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue(':firm_id', $getFirmId, \PDO::PARAM_INT);
                        $statement->bindValue(':about', $params['about'], \PDO::PARAM_STR);
                        $statement->bindValue(':about_eng', $params['about_eng'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal1_title', $params['verbal1_title'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal1', $params['verbal1'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal2_title', $params['verbal2_title'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal2', $params['verbal2'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal3_title', $params['verbal3_title'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal3', $params['verbal3'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal1_title_eng', $params['verbal1_title_eng'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal1_eng', $params['verbal1_eng'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal2_title_eng', $params['verbal2_title_eng'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal2_eng', $params['verbal2_eng'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal3_title_eng', $params['verbal3_title_eng'], \PDO::PARAM_STR);
                        $statement->bindValue(':verbal3_eng', $params['verbal3_eng'], \PDO::PARAM_STR);
                        //  echo debugPDO($sql, $params);
                        $result = $statement->execute();
                        $insertID = $pdo->lastInsertId('info_users_verbal_id_seq');
                        $errorInfo = $statement->errorInfo();
                        if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                            throw new \PDOException($errorInfo[0]);
                        
                        $xjobs = ActProcessConfirm::insert(array(
                              'op_user_id' => intval($opUserIdValue),
                              'operation_type_id' => intval($operationIdValue),
                              'table_column_id' => intval($insertID),
                              'cons_id' => intval($ConsultantId),
                              'preferred_language_id' => intval($languageIdValue),
                                  )
                        );
                        if ($xjobs['errorInfo'][0] != "00000" && $xjobs['errorInfo'][1] != NULL && $xjobs['errorInfo'][2] != NULL)
                        throw new \PDOException($xjobs['errorInfo']);

                        $pdo->commit();

                        return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
                    } else {
                        $errorInfo = '23502';   // 23502  not_null_violation
                        $errorInfoColumn = 'firm_id';
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
     * info_users_verbal tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  23.06.2016
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
             
                $getFirm = InfoFirmProfile :: getCheckIsThisFirmRegisteredUser(array('cpk' => $params['cpk'],'op_user_id' => $opUserIdValue));
                if (\Utill\Dal\Helper::haveRecord($getFirm)) {
                    $getFirmId = $getFirm ['resultSet'][0]['firm_id'];
              
                    $kontrol = $this->haveRecords(array('id' => $params['id'],'firm_id' => $getFirmId,));
                    if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                        $this->makePassive(array('id' => $params['id']));
                        $operationIdValue = -2;
                        $operationId = SysOperationTypes::getTypeIdToGoOperationId(
                                        array('parent_id' => 3, 'main_group' => 3, 'sub_grup_id' => 32, 'type_id' => 2,));
                        if (\Utill\Dal\Helper::haveRecord($operationId)) {
                            $operationIdValue = $operationId ['resultSet'][0]['id'];
                        }
                        $languageId = NULL;
                        $languageIdValue = 647;
                        if ((isset($params['language_code']) && $params['language_code'] != "")) {
                            $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                            if (\Utill\Dal\Helper::haveRecord($languageId)) {
                                $languageIdValue = $languageId ['resultSet'][0]['id'];
                            }
                        }
                        $profilePublic = 0;
                        if ((isset($params['profile_public']) && $params['profile_public'] != "")) {
                            $profilePublic = intval($params['profile_public']);
                        }
                        $countryId = 91;
                        if ((isset($params['country_id']) && $params['country_id'] != "")) {
                            $countryId = intval($params['country_id']);
                        }
                        $active = 0;
                        if ((isset($params['active']) && $params['active'] != "")) {
                            $active = intval($params['active']);
                        }
                   
                        $xc = InfoFirmProfile::updateVerbal(array(
                                    'op_user_id' => $opUserIdValue,
                                    'firm_id' => $getFirmId,
                                    'profile_public' => $profilePublic,
                                    'language_id' => $languageIdValue,
                                    'foundation_yearx' => $params['foundation_yearx'],
                                    'country_id' => $countryId,
                                    'firm_name' => $params['firm_name'],
                                    'firm_name_eng' => $params['firm_name_eng'],
                                    'firm_name_short' => $params['firm_name_short'],
                                    'firm_name_short_eng' => $params['firm_name_short_eng'],
                                    'web_address' => $params['web_address'],
                                    'tax_office' => $params['tax_office'],
                                    'tax_no' => $params['tax_no'],
                                    'description' => $params['description'],
                                    'description_eng' => $params['description_eng'],
                                    'sgk_sicil_no' => $params['sgk_sicil_no'],
                                    'duns_number' => $params['duns_number'],
                                    'logo' => $params['logo'],
                                        )
                        );
                       

                        if ($xc['errorInfo'][0] != "00000" && $xc['errorInfo'][1] != NULL && $xc['errorInfo'][2] != NULL)
                            throw new \PDOException($xc['errorInfo']);

                        $sql = " 
                        INSERT INTO info_users_verbal(
                            firm_id, 
                            consultant_id,
                            operation_type_id, 
                            language_id,
                            op_user_id,
                            profile_public,                           
                            act_parent_id,  
                            about,
                            about_eng,
                            verbal1_title, 
                            verbal1, 
                            verbal2_title, 
                            verbal2, 
                            verbal3_title, 
                            verbal3, 
                            verbal1_title_eng, 
                            verbal1_eng, 
                            verbal2_title_eng, 
                            verbal2_eng, 
                            verbal3_title_eng, 
                            verbal3_eng,
                            language_parent_id,
                            active
                            )                        
                        SELECT 
                            firm_id, 
                            consultant_id,                            
                            " . intval($operationIdValue) . " AS operation_type_id,
                            " . intval($languageIdValue) . " AS language_id,   
                            " . intval($opUserIdValue) . " AS op_user_id, 
                            " . intval($profilePublic) . " AS profile_public, 
                            act_parent_id,                            
                            CAST('" . $params['about'] . "' AS character varying(3000)) AS about,
                            CAST('" . $params['about_eng'] . "' AS character varying(3000)) AS about_eng,
                            CAST('" . $params['verbal1_title'] . "' AS character varying(150)) AS verbal1_title,
                            CAST('" . $params['verbal1'] . "' AS character varying(2000)) AS verbal1,
                            CAST('" . $params['verbal2_title'] . "' AS character varying(150)) AS verbal2_title,
                            CAST('" . $params['verbal2'] . "' AS character varying(2000)) AS verbal2,
                            CAST('" . $params['verbal3_title'] . "' AS character varying(150)) AS verbal3_title,
                            CAST('" . $params['verbal3'] . "' AS character varying(2000)) AS verbal3,
                            CAST('" . $params['verbal1_title_eng'] . "' AS character varying(150)) AS verbal1_title_eng,
                            CAST('" . $params['verbal1_eng'] . "' AS character varying(2000)) AS verbal1_eng,
                            CAST('" . $params['verbal2_title_eng'] . "' AS character varying(150)) AS verbal2_title_eng,
                            CAST('" . $params['verbal2_eng'] . "' AS character varying(2000)) AS verbal2_eng,
                            CAST('" . $params['verbal3_title_eng'] . "' AS character varying(150)) AS verbal3_title_eng,
                            CAST('" . $params['verbal3_eng'] . "' AS character varying(2000)) AS verbal3_eng,                            
                            language_parent_id,
                            " . intval($active) . " AS active
                        FROM info_users_verbal 
                        WHERE id =  " . intval($params['id']) . " 
                        ";
                        $statement_act_insert = $pdo->prepare($sql);
                   // echo debugPDO($sql, $params);
                        $insert_act_insert = $statement_act_insert->execute();
                        $affectedRows = $statement_act_insert->rowCount();                                         
                        $insertID = $pdo->lastInsertId('info_users_verbal_id_seq');                               
                        $errorInfo = $insert_act_insert->errorInfo();
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

                        $xjobs = ActProcessConfirm::insert(array(
                                    'op_user_id' => intval($opUserIdValue), // işlemi yapan user
                                    'operation_type_id' => intval($operationIdValue), // operasyon 
                                    'table_column_id' => intval($insertID), // işlem yapılan tablo id si
                                    'cons_id' => intval($ConsultantId), // atanmış olan danısman 
                                    'preferred_language_id' => intval($languageIdValue), // dil bilgisi
                                        )
                        );

                        if ($xjobs['errorInfo'][0] != "00000" && $xjobs['errorInfo'][1] != NULL && $xjobs['errorInfo'][2] != NULL)
                           throw new \PDOException($xjobs['errorInfo']);                 
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
                    $errorInfoColumn = 'cpk';
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
     * @ Gridi doldurmak için info_users_verbal tablosundan kayıtları döndürür !!
     * @version v 1.0  23.06.2016
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
                    FROM info_users_verbal a                     
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN info_users_verbal ax ON (ax.id = a.id OR ax.language_parent_id=a.id) AND ax.active = 0 AND ax.deleted = 0 AND ax.language_id =lx.id  
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
     * @ Gridi doldurmak için info_users_verbal tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  23.06.2016
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
                FROM info_users_verbal a
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
     * @version 23.06.2016 
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
                $getFirm = InfoFirmProfile :: getCheckIsThisFirmRegisteredUser(array('cpk' => $params['cpk'], 'op_user_id' => $opUserIdValue));
                if (\Utill\Dal\Helper::haveRecord($getFirm)) {
                    $getFirmId = $getFirm ['resultSet'][0]['firm_id'];
                    //$kontrol = $this->haveRecords($params);
                    $kontrol = $this->haveRecords(array('id' => $params['id'],'firm_id' => $getFirmId,));                    
                    if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                        $this->makePassive(array('id' => $params['id']));
                        $operationIdValue = -3;
                        $operationId = SysOperationTypes::getTypeIdToGoOperationId(
                                        array('parent_id' => 3, 'main_group' => 3, 'sub_grup_id' => 32, 'type_id' => 3,));
                        if (\Utill\Dal\Helper::haveRecord($operationId)) {
                            $operationIdValue = $operationId ['resultSet'][0]['id'];
                        }
                        $languageId = NULL;
                        $languageIdValue = 647;
                        if ((isset($params['language_code']) && $params['language_code'] != "")) {
                            $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                            if (\Utill\Dal\Helper::haveRecord($languageId)) {
                                $languageIdValue = $languageId ['resultSet'][0]['id'];
                            }
                        }

                        $profilePublic = 0;
                        if ((isset($params['profile_public']) && $params['profile_public'] != "")) {
                            $profilePublic = intval($params['profile_public']);
                        }

                        $sql = " 
                        INSERT INTO info_users_verbal(
                            firm_id, 
                            consultant_id,
                            operation_type_id, 
                            language_id,
                            op_user_id,
                            profile_public,                           
                            act_parent_id,  
                            about,
                            about_eng,
                            verbal1_title, 
                            verbal1, 
                            verbal2_title, 
                            verbal2, 
                            verbal3_title, 
                            verbal3, 
                            verbal1_title_eng, 
                            verbal1_eng, 
                            verbal2_title_eng, 
                            verbal2_eng, 
                            verbal3_title_eng, 
                            verbal3_eng,
                            consultant_confirm_type_id, 
                            confirm_id,
                            language_parent_id,
                            cons_allow_id,
                            active,
                            deleted
                            )                        
                        SELECT 
                            firm_id, 
                            consultant_id,                            
                            " . intval($operationIdValue) . " AS operation_type_id,
                            language_id,   
                            " . intval($opUserIdValue) . " AS op_user_id, 
                            profile_public, 
                            act_parent_id,                            
                            about,
                            about_eng,
                            verbal1_title,
                            verbal1,
                            verbal2_title,
                            verbal2,
                            verbal3_title,
                            verbal3,
                            verbal1_title_eng,
                            verbal1_eng,
                            verbal2_title_eng,
                            verbal2_eng,
                            verbal3_title_eng,
                            verbal3_eng,                             
                            consultant_confirm_type_id, 
                            confirm_id,
                            language_parent_id,
                            cons_allow_id,
                            1,
                            1
                        FROM info_users_verbal 
                        WHERE id =  " . intval($params['id']) . " 
                        ";
                        $statement_act_insert = $pdo->prepare($sql);
                        
                        $insert_act_insert = $statement_act_insert->execute();
                        // echo debugPDO($sql, $params);
                        $affectedRows = $statement_act_insert->rowCount();
                        $insertID = $pdo->lastInsertId('info_users_verbal_id_seq');
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

                        $xjobs = ActProcessConfirm::insert(array(
                                    'op_user_id' => intval($opUserIdValue), // işlemi yapan user
                                    'operation_type_id' => intval($operationIdValue), // operasyon 
                                    'table_column_id' => intval($insertID), // işlem yapılan tablo id si
                                    'cons_id' => intval($ConsultantId), // atanmış olan danısman 
                                    'preferred_language_id' => intval($languageIdValue), // dil bilgisi
                                        )
                        );

                        if ($xjobs['errorInfo'][0] != "00000" && $xjobs['errorInfo'][1] != NULL && $xjobs['errorInfo'][2] != NULL)
                            throw new \PDOException($xjobs['errorInfo']);
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
                    $errorInfoColumn = 'cpk';
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
     * @ userin sözel kayıtlarını döndürür !!
     * @version v 1.0  23.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillUsersVerbalNpk($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {    
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $addSql = "";
                $languageId = NULL;
                $languageIdValue = 647;
                if ((isset($params['language_code']) && $params['language_code'] != "")) {                
                    $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                    if (\Utill\Dal\Helper::haveRecord($languageId)) {
                        $languageIdValue = $languageId ['resultSet'][0]['id'];                    
                    }
                }  

                $networkKey = "-1";
                if ((isset($params['network_key']) && $params['network_key'] != "")) {                
                    $networkKey = $params['network_key'] ;
                }                                
                
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
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name,  
			CASE COALESCE(NULLIF(iud.picture, ''),'-')
				WHEN '-' THEN CONCAT(COALESCE(NULLIF(concat(sps.folder_road,'/'), '/'),''),sps.members_folder,'/'  ,'image_not_found.png')
				ELSE CONCAT(sps.folder_road ,'/',sps.members_folder,'/' ,COALESCE(NULLIF(iud.picture, ''),'image_not_found.png')) END AS picture,			
                        a.user_id = u.id AS userb,
                        uu.network_key AS unpk
                    FROM info_users_verbal a
                    INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN info_users_verbal ax ON (ax.id = a.id OR ax.language_parent_id=a.id) AND ax.active = 0 AND ax.deleted = 0 AND ax.language_id =lx.id AND ax.cons_allow_id =2
                    INNER JOIN info_users uu ON uu.id = a.user_id AND a.user_id = a.user_id
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.deleted =0 AND iud.active =0 AND iud.language_parent_id =0
                    LEFT JOIN info_users_detail iudx ON (iudx.id = iud.id OR iudx.language_parent_id = iud.id) AND iudx.deleted =0 AND iudx.active =0 AND iudx.language_id =lx.id
                    INNER JOIN info_users u ON u.id = " . intval($opUserIdValue) . "                     
                    WHERE 
                        a.cons_allow_id=2 AND 
                        a.language_parent_id =0 AND
                        a.profile_public =0 AND 
                        uu.network_key = '" . $params['network_key'] . "'
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
     * @ guest in sectiği firmanın sözel kayıtlarını döndürür !!
     * @version v 1.0  23.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillUsersVerbalNpkGuest($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');            
                $addSql = "";
                $languageId = NULL;
                $languageIdValue = 647;
                if ((isset($params['language_code']) && $params['language_code'] != "")) {                
                    $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                    if (\Utill\Dal\Helper::haveRecord($languageId)) {
                        $languageIdValue = $languageId ['resultSet'][0]['id'];                    
                    }
                }  

                $networkKey = "-1";
                if ((isset($params['network_key']) && $params['network_key'] != "")) {                
                    $networkKey = $params['network_key'] ;
                }                               
                
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
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name,  
			CASE COALESCE(NULLIF(iud.picture, ''),'-')
				WHEN '-' THEN CONCAT(COALESCE(NULLIF(concat(sps.folder_road,'/'), '/'),''),sps.members_folder,'/'  ,'image_not_found.png')
				ELSE CONCAT(sps.folder_road ,'/',sps.members_folder,'/' ,COALESCE(NULLIF(iud.picture, ''),'image_not_found.png')) END AS picture,			
                        false AS userb,
                        uu.network_key
                    FROM info_users_verbal a
                    INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN info_users_verbal ax ON (ax.id = a.id OR ax.language_parent_id=a.id) AND ax.active = 0 AND ax.deleted = 0 AND ax.language_id =lx.id AND ax.cons_allow_id =2
                    INNER JOIN info_users uu ON uu.id = a.user_id AND a.user_id = a.user_id
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.deleted =0 AND iud.active =0 AND iud.language_parent_id =0
                    LEFT JOIN info_users_detail iudx ON (iudx.id = iud.id OR iudx.language_parent_id = iud.id) AND iudx.deleted =0 AND iudx.active =0 AND iudx.language_id =lx.id                  
                    WHERE 
                        a.cons_allow_id=2 AND 
                        a.language_parent_id =0 AND
                        a.profile_public =0 AND 
                        uu.network_key = '" . $params['network_key'] . "'
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
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    /**
     * @author Okan CIRAN
     * @ user in sözel verilerinin danısman bilgisini döndürür !!
     * @version v 1.0  23.05.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getUserVerbalConsultant($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
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

                $sql = "
                SELECT DISTINCT 
                    u.network_key AS cons_npk,
                    iud.name, 
                    iud.surname,
                    iud.auth_email,              
		    CASE COALESCE(NULLIF(TRIM(iud.picture), ''),'-') 
                        WHEN '-' THEN CONCAT(COALESCE(NULLIF(CONCAT(sps.folder_road,'/'), '/'),''),sps.members_folder,'/' ,'image_not_found.png')
                        ELSE CONCAT(COALESCE(NULLIF(CONCAT(sps.folder_road,'/'), '/'),''),sps.members_folder,'/' ,TRIM(iud.picture)) END AS cons_picture 
                FROM info_users a
                INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0                 
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0 
                LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND l.deleted =0 AND l.active =0 
		INNER JOIN info_users_verbal ifv ON ifv.user_id = a.id AND ifv.deleted = 0 AND ifv.active =0 AND ifv.language_parent_id=0
                INNER JOIN info_users u ON u.id = ifv.consultant_id AND u.role_id in (1,2,6)
                INNER JOIN info_users_detail iud ON iud.root_id = u.id AND iud.cons_allow_id = 2
                WHERE 
                   ifv.user_id =  " . intval($opUserIdValue) . "  
                ORDER BY  iud.name, iud.surname 
                ";
                $statement = $pdo->prepare($sql);
               //   echo debugPDO($sql, $params);                
                $statement->execute();
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);             
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }
    
 
 
}
