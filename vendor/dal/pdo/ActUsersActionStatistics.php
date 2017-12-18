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
class ActUsersActionStatistics extends \DAL\DalSlim {

    /**    
     * @author Okan CIRAN
     * @ act_users_action_statistics tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  15.12.2016
     * @param array | null $args
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
     * @ act_users_action_statistics tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  15.12.2016
     * @param array | null $args  
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $statement = $pdo->prepare("
                    SELECT 
                        a.id,                        
                        a.s_datetime,  
                        a.s_date,
                        a.operation_type_id,
                        op.operation_name,                         
			a.language_id, 			
                        a.language_code, 
                        COALESCE(NULLIF(l.language_eng, ''), l.language) AS language_name,                                                
                        a.op_user_id,
                        u.username,
                        acl.name as role_name,
                        a.service_name,                         
                        a.table_name,
                        a.about_id
                    FROM act_users_action_statistics a    
                    INNER JOIN sys_operation_types op ON op.id = a.operation_type_id AND op.deleted =0 AND op.active =0
                    INNER JOIN sys_language l ON l.language_main_code = a.language_code AND l.deleted =0 AND l.active =0 
                    INNER JOIN info_users u ON u.id = a.op_user_id                      
                    INNER JOIN sys_acl_roles acl ON acl.id = u.role_id   
                    ORDER BY a.s_date desc ,op.operation_name  
                          ");            
            $statement->execute();
            $result = $statement->fetcAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
           // $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /** 
     * @author Okan CIRAN
     * @ act_users_action_statistics tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  15.12.2016
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');

            $getFirmId = NULL;
           /* 
            if ((isset($params['npk']) && $params['npk'] != "")) {
                $getFirm = InfoFirmProfile :: getFirmIdsForNetworkKey(array('network_key' => $params['npk']));
                if (\Utill\Dal\Helper::haveRecord($getFirm)) {
                    $getFirmId = $getFirm ['resultSet'][0]['firm_id'];
                }
            }

*/
            $getUserId = NULL;
            if ((isset($params['unpk']) && $params['unpk'] != "")) {
                $getUser = InfoUsers:: getUserIdsForNetworkKey(array('network_key' => $params['unpk']));                
                if (\Utill\Dal\Helper::haveRecord($getUser)) {
                    $getUserId = $getUser ['resultSet'][0]['user_id'];
                }
            }

            if ($getFirmId != NULL OR $getUserId != NULL) {

                $opUserIdValue = -99;
                if ((isset($params['opUserIdValue']) && $params['opUserIdValue'] != "")) {
                    $opUserIdValue = intval($params['opUserIdValue']);
                }

                $opUserFirmIdValue = NULL;
                if ((isset($params['opUserFirmIdValue']) && $params['opUserFirmIdValue'] != "")) {
                    $opUserFirmIdValue = intval($params['opUserFirmIdValue']);
                }

                $languageIdValue = 647;
                if ((isset($params['language_id']) && $params['language_id'] != "")) {
                    $languageIdValue = intval($params['language_id']);
                }

                $sysAclRestserviceIdValue = -1;
                if ((isset($params['url']) && $params['url'] != "")) {
                    $sysAclRestserviceId = SysNotificationRestservices::getNotificationRestserviceId(array('url' => $params['url']));
                    if (\Utill\Dal\Helper::haveRecord($sysAclRestserviceId)) {
                        $sysAclRestserviceIdValue = $sysAclRestserviceId ['resultSet'][0]['id'];
                    }
                }
 
                $sql = "
                INSERT INTO act_users_action_statistics(
                        op_user_id, 
                        firm_id, 
                        user_id, 
                        notification_restservices_id, 
                        language_id,
                        op_user_firm_id
                        )
             
               VALUES (
                        " . intval($opUserIdValue) . ",
                        " . intval($getFirmId) . ",
                        " . intval($getUserId) . ",
                        " . intval($sysAclRestserviceIdValue) . ",
                        " . intval($languageIdValue) . " ,
                        " . intval($opUserFirmIdValue) . "    
                                               ) ";
                $statement = $pdo->prepare($sql);               
                $result = $statement->execute();
                $insertID = $pdo->lastInsertId('act_users_action_statistics_id_seq');
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);

                return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
            }
            return array("found" => false, "errorInfo" => NULL);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**    
     * @author Okan CIRAN
     * act_users_action_statistics tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  15.12.2016
     * @param array | null $args  
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
     * @ kullanıcıların notificationlarını döndürür.  !!
     * @version v 1.0  15.12.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getUsersCompanyNotifications($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $typeId = 0; // guestler için    
            $addSql = NULL;
            $innerSql= NULL;
            $addSql = " snr.type_id  = " . intval($typeId);
            if ((isset($params['pk']) && $params['pk'] != "")) {
                $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
                if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                    //   $opUserIdValue = $opUserId['resultSet'][0]['user_id'];
                    $opUserFirmIdValue = $opUserId ['resultSet'][0]['user_firm_id'];
                    if ($opUserFirmIdValue > 1 ) {                    
                        $typeId = 1;
                        $addSql = " a.firm_id = " . intval($opUserFirmIdValue) . " 
                            AND a.op_user_firm_id != " . intval($opUserFirmIdValue);
                        // bu kısım eger firma ile ilgili  tablo varsa eklenecek. 
                        $innerSql = 
                            " LEFT JOIN info_firm_users ifu ON ifu.user_id = a.op_user_id AND ifu.active=0 AND ifu.deleted=0   
                              LEFT JOIN info_firm_keys ifks ON ifks.firm_id =ifu.firm_id  " ;
                        
                    } ELSE {
                        $addSql = " snr.type_id IN (0) ";
                    }
                }
            }
          //  $addSql .= " AND snr.type_id  = " . intval($typeId);
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
                

            $sql = " 
                SELECT  name, surname, sure%30 AS dayx, notification_type_id,
                        (sure/30)%12 AS monthx, sure/365 AS yearx, 
                        role, notification, 
                        sure1 AS passingtime, s_date AS processingtime ,icon_path, icon_class 
                        FROM (
                        SELECT  name, surname,notification_type_id,
                            CASE
                                WHEN sure_int > 50000 THEN CAST(SUBSTRING(sure FROM 1 FOR POSITION(' ' IN sure )-1 ) AS integer)
                            ELSE 0 
                            END AS sure, role,notification,sure as sure1 ,s_date,icon_path, icon_class
                            from (
                                SELECT 
                                    ud.name , 
                                    ud.surname,
                                    EXTRACT(EPOCH FROM age(a.s_date)) AS sure_int, 
                                    a.*,
                                    COALESCE(NULLIF(snrx.description, ''), snr.description_eng) AS notification,
                                    COALESCE(NULLIF(aclx.name, ''), acl.name) AS role,
                                    CAST(CURRENT_TIMESTAMP - a.s_date AS VARCHAR(20)) AS sure,
                                    snr.notification_type_id, 
                                    snt.icon_path,
                                    snt.icon_class
                                FROM act_users_action_statistics a                  
                                INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0    
                                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0 
                                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0                                
                                INNER JOIN sys_notification_restservices snr ON snr.id = a.notification_restservices_id AND snr.active = 0 and snr.deleted =0 and snr.language_parent_id =0   
                                LEFT JOIN sys_notification_restservices snrx ON (snrx.id = snr.id OR snrx.language_parent_id = snr.id) AND snrx.active = 0 and snrx.deleted =0 AND snrx.language_id = lx.id    
                                INNER JOIN sys_notification_types snt ON snt.id = snr.notification_type_id AND snt.active = 0 and snt.deleted =0 and snt.language_parent_id =0                                   
                                LEFT JOIN info_users u ON u.id = a.op_user_id 
                                LEFT JOIN info_users_detail ud ON ud.root_id = a.op_user_id AND ud.cons_allow_id = 2
                                LEFT JOIN sys_acl_roles acl ON acl.id = u.role_id  
                                LEFT JOIN sys_acl_roles aclx ON aclx.id = acl.id                                 
                                ".$innerSql ."
                                WHERE 
                                    " . $addSql . " 
                                   ORDER BY a.s_date DESC limit 10     
                                ) AS xxtable
                ) AS xxctable
                ORDER BY processingtime DESC
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
            //  $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
    /**    
     * @author Okan CIRAN
     * @ userlar firmanın ziyaretci notifications sayısını döndürür  !! 
     * codebase de  firma bilgisi  olmadıgı için 0 donecektir tum degerler. 
     * @version v 1.0  21.12.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getUsersLeftNotifications($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
            $networkkey ='-11';
            if (isset($params['network_key']) && $params['network_key'] != "") {
                $networkkey =$params['network_key'];
            }
            $addSql = " ifk.network_key = '" . $networkkey . "'";
            $addSql1 = " ifkr.network_key = '" . $networkkey . "'";
            $addSql2 = " ifke.network_key = '" . $networkkey . "'";
        /*    if ((isset($params['pk']) && $params['pk'] != "")) {
                $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
                if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                    //   $opUserIdValue = $opUserId['resultSet'][0]['user_id'];
                    $opUserFirmIdValue = $opUserId ['resultSet'][0]['user_firm_id'];
                }
            }
          */ 
            $sql = "                    
                    SELECT
                        COALESCE(NULLIF(allnotificationscount, NULL), 0) AS allnotificationscount,
                        COALESCE(NULLIF(lasttwelve, NULL), 0) AS lasttwelve,
                        COALESCE(NULLIF(lastsix, NULL), 0) AS lastsix, 
                        COALESCE(NULLIF(allfirmnotificationscount , NULL), 0) AS allfirmnotificationscount,
                        COALESCE(NULLIF(CAST(100 * CAST(lasttwelve AS numeric(15,2)) /allnotificationscount as numeric(5,2)), NULL), 0) AS lasttwelvepercent
                    FROM(   
                        SELECT 
                            COUNT(a.id) AS allfirmnotificationscount, 
                            (SELECT count(ar.id) 
                            FROM act_users_action_statistics ar 
                            INNER JOIN info_firm_keys ifkr ON ar.firm_id = ifkr.firm_id 
                            INNER JOIN sys_notification_restservices snrr ON snrr.id = ar.notification_restservices_id AND snrr.active = 0 and snrr.deleted =0 and snrr.language_parent_id =0                                 
                            INNER JOIN sys_notification_types sntr ON sntr.id = snrr.notification_type_id AND sntr.active = 0 and sntr.deleted =0 and sntr.language_parent_id =0   
                            WHERE 
				".$addSql1."
                                AND ar.op_user_firm_id != ifkr.firm_id 
                                AND date( now()) - CAST(ar.s_date as date) < 181) AS lastsix,
                            (SELECT count(ae.id) 
                            FROM act_users_action_statistics ae 
                            INNER JOIN info_firm_keys ifke ON ae.firm_id = ifke.firm_id 
                            INNER JOIN sys_notification_restservices snre ON snre.id = ae.notification_restservices_id AND snre.active = 0 and snre.deleted =0 and snre.language_parent_id =0                                 
                            INNER JOIN sys_notification_types snte ON snte.id = snre.notification_type_id AND snte.active = 0 and snte.deleted =0 and snte.language_parent_id =0   
                            WHERE  
				".$addSql2."
                                AND ae.op_user_firm_id != ifke.firm_id 
                                AND date( now()) - CAST(ae.s_date AS date) < 366) AS lasttwelve,
                            (SELECT count(aez.id) 
                            FROM act_users_action_statistics aez                             
                            INNER JOIN sys_notification_restservices snrez ON snrez.id = aez.notification_restservices_id AND snrez.active = 0 and snrez.deleted =0 and snrez.language_parent_id =0 		
                            INNER JOIN sys_notification_types sntez ON sntez.id = snrez.notification_type_id AND sntez.active = 0 and sntez.deleted =0 and sntez.language_parent_id =0   
                            WHERE 1=1 ) AS allnotificationscount
                        FROM act_users_action_statistics a 
                        INNER JOIN info_firm_keys ifk ON a.firm_id = ifk.firm_id 
                        INNER JOIN sys_notification_restservices snr ON snr.id = a.notification_restservices_id AND snr.active = 0 and snr.deleted =0 and snr.language_parent_id =0                      
                        INNER JOIN sys_notification_types snt ON snt.id = snr.notification_type_id AND snt.active = 0 and snt.deleted =0 and snt.language_parent_id =0   
                        WHERE                     
                            ".$addSql."                           
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
            //  $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**    
     * @author Okan CIRAN
     * @ userlar firmanın makina bilgilerini döndürür  !! 
     * codebase sisteminde makina tablosu olmadıgı için sablon olarak  burada. 
     * @version v 1.0  22.12.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getUsersRightNotifications($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
            $networkkey ='-11';
            if (isset($params['network_key']) && $params['network_key'] != "") {
                $networkkey =$params['network_key'];
            }
            $addSql = " ifk.network_key = '" . $networkkey . "'";
            $addSql1 = " ifkr.network_key = '" . $networkkey . "'";
            $addSql2 = " ifke.network_key = '" . $networkkey . "'";
        /*    if ((isset($params['pk']) && $params['pk'] != "")) {
                $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
                if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                    //   $opUserIdValue = $opUserId['resultSet'][0]['user_id'];
                    $opUserFirmIdValue = $opUserId ['resultSet'][0]['user_firm_id'];
                }
            }
          */ 
            $sql = "                    
                    SELECT 
                        COALESCE(NULLIF(allfirmmachinetotal, NULL), 0) AS allfirmmachinetotal,
                        COALESCE(NULLIF(firmmachinetotal, NULL), 0) AS firmmachinetotal,
                        COALESCE(NULLIF(lasttwelve, NULL), 0) AS lasttwelve,
                        COALESCE(NULLIF(lastsix, NULL), 0) AS lastsix,                        
                        COALESCE(NULLIF(CAST(100 * CAST(lasttwelve AS numeric(15,2)) /firmmachinetotal AS numeric(5,2)), NULL), 0) AS lasttwelvepercent
                    FROM(   
                        SELECT 
                            SUM(a.total) AS firmmachinetotal, 
                            (SELECT SUM(ar.total)  
                            FROM info_firm_machine_tool ar 
                            inner join info_firm_keys ifkr on ar.firm_id = ifkr.firm_id 
                            WHERE 
				".$addSql1." AND
                                ar.active =0 AND 
				ar.deleted =0 AND 
				ar.language_parent_id =0 AND
				ar.cons_allow_id =2 AND 
                                date( now()) - CAST(ar.s_date as date) < 181) AS lastsix,
                            (SELECT SUM(ae.total) 
                            FROM info_firm_machine_tool ae 
                            inner join info_firm_keys ifke on ae.firm_id = ifke.firm_id                             
                            WHERE  
				".$addSql2." AND
                                ae.active =0 AND 
				ae.deleted =0 AND 
				ae.language_parent_id =0 AND
				ae.cons_allow_id =2 AND 
                                date( now()) - CAST(ae.s_date as date) < 366) AS lasttwelve,			   
                            (SELECT sum(aez.total) 
                            FROM info_firm_machine_tool aez
                            WHERE 
				aez.active =0 AND 
				aez.language_parent_id =0 AND
                                aez.cons_allow_id =2 AND 
				aez.deleted =0 ) AS allfirmmachinetotal
                        FROM info_firm_machine_tool a 
                        inner join info_firm_keys ifk on a.firm_id = ifk.firm_id                         
                        WHERE 
                            a.active =0 AND 
                            a.deleted =0 AND 
                            a.language_parent_id =0 AND
                            a.cons_allow_id =2 AND 
                            ".$addSql."
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
            //  $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**   
     * @author Okan CIRAN
     * @ firma history döndürür.  !!
     *  codebase de firma ile ilgili  tablolar olmadıgı için calısmaz.  sadece  sablon olarak burada. 
     * @version v 1.0  27.12.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getFirmHistoryV1($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');           
            
            $networkkey ='-11';
            if (isset($params['network_key']) && $params['network_key'] != "") {
                $networkkey =$params['network_key'];
            }
       
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
                

            $sql = " 
                SELECT xtable.s_date, xtable.op_user_id, xtable.operation_type_id,xtable.picture,
                    iud.name , iud.surname ,xtable.description, sar.name AS statu,
                    COALESCE(NULLIF(opx.operation_name, ''), op.operation_name_eng) AS operation_name,
                    COALESCE(NULLIF(soccx.category, ''), socc.category_eng) AS category,
                    op.category_id,
                    CASE COALESCE(NULLIF(TRIM(iud.picture), ''),'-') 
                        WHEN '-' THEN NULL
                        ELSE CONCAT(COALESCE(NULLIF(concat(sps.folder_road,'/'), '/'),''),sps.members_folder,'/' ,COALESCE(NULLIF(iud.picture, ''),'image_not_found.png')) END AS op_user_picture
                FROM ( 
			(SELECT a1.s_date, a1.op_user_id, a1.operation_type_id,
			    CASE COALESCE(NULLIF(a1.logo, ''),'-') 
				WHEN '-' THEN '/onyuz/standard/assets/img/sfClients/logos/image_not_found.png'
				ELSE CONCAT('/onyuz/standard/assets/img/sfClients/',ifk1.folder_name ,'/',ifk1.logos_folder,'/' ,COALESCE(NULLIF(a1.logo, ''),'image_not_found.png')) END AS picture,				
			    a1.firm_name AS description
			    FROM info_firm_profile a1
			    INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0
			    INNER JOIN info_firm_keys ifk1 ON a1.act_parent_id = ifk1.firm_id			    
			    WHERE ifk1.network_key = '".$networkkey."'
                            ORDER BY a1.id DESC     
                            LIMIT 10)
			    UNION
			    (
                            SELECT a2.s_date, a2.op_user_id, a2.operation_type_id ,concat('/onyuz/standard/assets/img/sfSystem/Logos/social-media/png/',sm.logo) AS picture,
                            '' AS description
                            FROM info_firm_socialmedia a2
                            INNER JOIN info_firm_keys ifk2 ON a2.firm_id = ifk2.firm_id                            
                            INNER JOIN sys_socialmedia sm ON sm.id = a2.sys_socialmedia_id AND sm.deleted =0 AND sm.active =0 
			    WHERE ifk2.network_key = '".$networkkey."'
			    ORDER BY a2.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a3.s_date, a3.op_user_id, a3.operation_type_id ,'' AS picture,
                            '' AS description
                            FROM info_firm_address a3
                            INNER JOIN info_firm_keys ifk3 ON a3.firm_id = ifk3.firm_id
			    WHERE ifk3.network_key = '".$networkkey."'   
			    ORDER BY a3.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a4.s_date, a4.user_id, a4.operation_type_id ,'' AS picture,
                            '' AS description
                            FROM info_firm_building a4 
                            INNER JOIN info_firm_keys ifk4 ON a4.firm_id = ifk4.firm_id
			    WHERE ifk4.network_key = '".$networkkey."'
			    ORDER BY a4.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a5.s_date, a5.op_user_id, a5.operation_type_id , sc.logo  AS picture,
                            '' AS description
                            FROM info_firm_certificate a5
                            INNER JOIN info_firm_keys ifk5 ON a5.firm_id = ifk5.firm_id
                            INNER JOIN sys_certifications sc ON sc.id = a5.certificate_id AND sc.deleted =0 AND sc.active =0 AND sc.language_parent_id =0
			    WHERE ifk5.network_key = '".$networkkey."'
			    ORDER BY a5.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a6.s_date, a6.op_user_id, a6.operation_type_id ,'' AS picture,
                                CONCAT(so.name , ' - ' , soc.name ) AS description
                            FROM info_firm_clusters a6
                            INNER JOIN info_firm_keys ifk6 ON a6.firm_id = ifk6.firm_id
                            INNER JOIN sys_osb_clusters soc ON soc.id = a6.osb_cluster_id AND soc.active =0 AND soc.deleted =0 
                            INNER JOIN sys_osb so ON so.id = soc.osb_id AND so.active =0 AND so.deleted =0 
			    WHERE ifk6.network_key = '".$networkkey."'
                            ORDER BY a6.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a8.s_date, a8.op_user_id, a8.operation_type_id  ,'' AS picture,
                            '' AS description
                            FROM info_firm_communications a8
                            INNER JOIN info_firm_keys ifk8 ON a8.firm_id = ifk8.firm_id
			    WHERE ifk8.network_key = '".$networkkey."'
                            ORDER BY a8.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a9.s_date, a9.op_user_id, a9.operation_type_id  ,'' AS picture,
                            a9.customer_name AS description
                            FROM info_firm_customers a9
                            INNER JOIN info_firm_keys ifk9 ON a9.firm_id = ifk9.firm_id
			    WHERE ifk9.network_key = '".$networkkey."'
                            ORDER BY a9.id DESC
                            LIMIT 10)
                            UNION
                            (
                            SELECT a10.s_date, a10.op_user_id, a10.operation_type_id  ,'' AS picture,
                            '' AS description
                            FROM info_firm_fair a10
                            INNER JOIN info_firm_keys ifk10 ON a10.firm_id = ifk10.firm_id
			    WHERE ifk10.network_key = '".$networkkey."'
                            ORDER BY a10.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a11.s_date, a11.op_user_id, a11.operation_type_id  ,'' AS picture,
                            '' AS description
                            FROM info_firm_language_info a11
                            INNER JOIN info_firm_keys ifk11 ON a11.firm_id = ifk11.firm_id
			    WHERE ifk11.network_key = '".$networkkey."'
                            ORDER BY a11.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a12.s_date, a12.op_user_id, a12.operation_type_id,
                                CASE smt.picture_upload 
                                    WHEN false  THEN CONCAT('/onyuz/standard/assets/img/sfClients/machines/' ,COALESCE(NULLIF(sm.machine_not_found_picture, ''),'image_not_found.png'))
                                    ELSE CONCAT('/onyuz/standard/assets/img/sfClients/machines/' ,COALESCE(NULLIF(smt.picture, ''),'image_not_found.png')) END 
                                    AS picture,
                                    CONCAT(UPPER(sm.name), ' - ',LOWER(smt.machine_tool_name)) AS description
                            FROM info_firm_machine_tool a12
                            INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0
                            INNER JOIN info_firm_keys ifk12 ON a12.firm_id = ifk12.firm_id
                            INNER JOIN sys_machine_tools smt ON smt.id = a12.sys_machine_tool_id AND smt.active =0 AND smt.deleted = 0 AND smt.language_parent_id = 0
                            INNER JOIN sys_manufacturer sm ON sm.id = smt.manufactuer_id AND sm.active =0 and sm.deleted =0 
			    WHERE ifk12.network_key = '".$networkkey."'
                            ORDER BY a12.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a13.s_date, a13.op_user_id, a13.operation_type_id  ,'' AS picture,
                            '' AS description
                            FROM info_firm_membership a13
                            INNER JOIN info_firm_keys ifk13 ON a13.firm_id = ifk13.firm_id
			    WHERE ifk13.network_key = '".$networkkey."'
                            ORDER BY a13.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a14.s_date, a14.op_user_id, a14.operation_type_id  ,'' AS picture,
                            '' AS description
                            FROM info_firm_personnel_info a14
                            INNER JOIN info_firm_keys ifk14 ON a14.firm_id = ifk14.firm_id
			    WHERE ifk14.network_key = '".$networkkey."'
                            ORDER BY a14.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a15.s_date, a15.op_user_id, a15.operation_type_id,
                                CASE COALESCE(NULLIF(a15.product_picture, ''),'-') 
                                    WHEN '-' THEN '/onyuz/standard/assets/img/sfClients/products/image_not_found.png'
                                    ELSE CONCAT('/onyuz/standard/assets/img/sfClients/',ifk15.folder_name ,'/',ifk15.products_folder,'/' ,COALESCE(NULLIF(a15.product_picture, ''),'image_not_found.png')) END AS picture,
                                concat(a15.product_name ,'  :  ', a15.product_description)   AS description
                            FROM info_firm_products a15
                            INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0
                            INNER JOIN info_firm_keys ifk15 ON a15.firm_id = ifk15.firm_id                            
			    WHERE ifk15.network_key = '".$networkkey."'
                            ORDER BY a15.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a16.s_date, a16.op_user_id, a16.operation_type_id  ,'' AS picture,
                            '' AS description
                            FROM info_firm_raw_materials a16
                            INNER JOIN info_firm_keys ifk16 ON a16.firm_id = ifk16.firm_id
			    WHERE ifk16.network_key = '".$networkkey."'
                            ORDER BY a16.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a17.s_date, a17.op_user_id, a17.operation_type_id,
				CASE COALESCE(NULLIF(fpref.logo, ''),'-') 
					WHEN '-' THEN '/onyuz/standard/assets/img/sfClients/image_not_found.png'
					ELSE CONCAT('/onyuz/standard/assets/img/sfClients/',ifk17.folder_name ,'/',ifk17.logos_folder,'/' ,COALESCE(NULLIF(fpref.logo, ''),'image_not_found.png')) END AS picture,
				'' AS description                            
                            FROM info_firm_references a17
                            INNER JOIN info_firm_keys ifk17 ON ifk17.firm_id = a17.ref_firm_id
                            INNER JOIN info_firm_keys ifkm ON ifkm.firm_id = a17.firm_id
                            INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0
                            LEFT JOIN info_firm_profile fpref ON fpref.act_parent_id = a17.ref_firm_id AND fpref.cons_allow_id =2 AND fpref.language_parent_id = 0                             
			    WHERE ifkm.network_key = '".$networkkey."'
                            ORDER BY a17.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a18.s_date, a18.op_user_id, a18.operation_type_id  ,'' AS picture,
				ss.name AS description
                            FROM info_firm_sectoral a18
                            INNER JOIN info_firm_keys ifk18 ON a18.firm_id = ifk18.firm_id
                            INNER JOIN sys_sectors ss ON ss.id = a18.sector_id AND ss.active =0 AND ss.deleted =0 
			    WHERE ifk18.network_key = '".$networkkey."'
                            ORDER BY a18.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a19.s_date, a19.op_user_id, a19.operation_type_id  ,'' AS picture,
				'' AS description
                            FROM info_firm_user_desc_for_company a19
                            INNER JOIN info_firm_keys ifk19 ON a19.firm_id = ifk19.firm_id
			    WHERE ifk19.network_key = '".$networkkey."'
                            ORDER BY a19.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT  a20.s_date, a20.op_user_id, a20.operation_type_id  ,
				CASE COALESCE(NULLIF(ud20.picture, ''),'-')
				WHEN '-' THEN NULL
				ELSE CONCAT('/onyuz/standard/assets/img/sfClients/',ifk20.folder_name ,'/',ifk20.members_folder,'/' ,COALESCE(NULLIF(ud20.picture, ''),'image_not_found.png')) END AS picture,
				concat(a20.title ,' ' , ud20.name ,' ', ud20.surname) AS description				
                            FROM info_firm_users a20
                            INNER JOIN sys_project_settings sps20 ON sps20.op_project_id = 1 AND sps20.active =0 AND sps20.deleted =0
                            INNER JOIN info_users_detail ud20 ON ud20.root_id = a20.user_id AND ud20.deleted =0 AND ud20.active =0 
                            INNER JOIN info_firm_keys ifk20 ON a20.firm_id = ifk20.firm_id                            
			    WHERE ifk20.network_key = '".$networkkey."'
                            ORDER BY a20.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a21.s_date, a21.op_user_id, a21.operation_type_id  ,'' AS picture,
				'' AS description
                            FROM info_firm_verbal a21
                            INNER JOIN info_firm_keys ifk21 ON a21.firm_id = ifk21.firm_id
			    WHERE ifk21.network_key = '".$networkkey."'
                            ORDER BY a21.id DESC     
                            LIMIT 10)
                            UNION
                            (
                            SELECT a22.s_date, a22.op_user_id, a22.operation_type_id,
				'' AS picture,
				CONCAT(snc22.nace_code, '- ',COALESCE(NULLIF(sncx22.description, ''), snc22.description_eng)) AS description
                            FROM info_firm_nace a22
                            INNER JOIN info_firm_keys ifk22 ON a22.firm_id = ifk22.firm_id                                                        
			    LEFT JOIN sys_language lx22 ON lx22.id = " . intval($languageIdValue) . " AND lx22.deleted =0 AND lx22.active =0
                            INNER JOIN sys_nace_codes snc22 ON snc22.id = a22.nacecode_id AND snc22.active =0 AND snc22.deleted =0 AND snc22.language_parent_id =0 
                            LEFT JOIN sys_nace_codes sncx22 ON (sncx22.id = snc22.id OR sncx22.language_parent_id =snc22.id) AND sncx22.language_id = lx22.id AND sncx22.deleted =0 AND sncx22.active =0
			    WHERE ifk22.network_key = '".$networkkey."'
                            ORDER BY a22.id DESC     
                            LIMIT 10)
                ) AS xtable 
                INNER JOIN info_users u ON u.id = xtable.op_user_id
                INNER JOIN info_users_detail iud ON iud.root_id = u.id AND iud.cons_allow_id = 2
                INNER JOIN sys_project_settings sps ON sps.op_project_id = 1 AND sps.active =0 AND sps.deleted =0
                INNER JOIN sys_acl_roles sar ON sar.id = u.role_id AND sar.active =0 AND sar.deleted=0 
                INNER JOIN sys_operation_types op ON op.id = xtable.operation_type_id AND op.language_parent_id =0 AND op.deleted =0 AND op.active =0
                INNER JOIN sys_language l ON l.id = op.language_id AND l.deleted =0 AND l.active =0
                LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0 
                LEFT JOIN sys_operation_types opx ON (opx.id = op.id OR opx.language_parent_id =op.id) AND opx.language_id = lx.id AND opx.deleted =0 AND opx.active =0
                INNER JOIN sys_osb_consultant_categories socc ON socc.id= op.category_id AND socc.active =0 AND socc.deleted = 0 AND socc.language_parent_id =0 AND l.id = socc.language_id 
                LEFT JOIN sys_osb_consultant_categories soccx ON (soccx.id = socc.id OR soccx.language_parent_id = socc.id) AND soccx.deleted =0 AND soccx.active =0 AND lx.id = soccx.language_id

                WHERE  xtable.S_DATE IS NOT NULL
                order by xtable.S_DATE desc 
           
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
            //  $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    
    
}
