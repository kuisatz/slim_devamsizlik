<?php

/**
 *  Framework 
 *
 * @link   
 * @copyright Copyright (c) 2016
 * @license   
 */

namespace DAL\PDO;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CİRANĞ
 */
class BlLoginLogout extends \DAL\DalSlim {

    /**     
     * @author Okan CIRAN
     * @ info_users tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  30.12.2015
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
     * @ info_users tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  30.12.2015  
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
                        a.profile_public, 
                        a.f_check, 
                        a.s_date, 
                        a.c_date, 
                        a.operation_type_id,
                        op.operation_name, 
                        a.firm_name, 
                        a.web_address,                     
                        a.tax_office, 
                        a.tax_no, 
                        a.sgk_sicil_no,
			a.bagkur_sicil_no,
			a.ownership_status_id,
                        sd4.description AS owner_ship,
			a.foundation_year,
			a.language_id, 
			a.act_parent_id,  
                        a.language_code, 
                        COALESCE(NULLIF(l.language_eng, ''), l.language) AS language_name,                        
                        a.active, 
                        sd3.description AS state_active,  
                        a.deleted,
			sd2.description AS state_deleted, 
                        a.op_user_id,
                        u.username,                    
                        a.auth_allow_id, 
                        sd.description AS auth_alow ,
                        a.cons_allow_id,
                        sd1.description AS cons_allow,
                        a.language_parent_id,
                        a.root_id
                    FROM info_users a    
                    INNER JOIN sys_operation_types op ON op.id = a.operation_type_id and  op.language_code = a.language_code  AND op.deleted =0 AND op.active =0
                    INNER JOIN sys_specific_definitions sd ON sd.main_group = 13 AND sd.language_code = a.language_code AND a.auth_allow_id = sd.first_group  AND sd.deleted =0 AND sd.active =0
                    INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 14 AND  sd1.language_code = a.language_code AND a.cons_allow_id = sd1.first_group  AND sd1.deleted =0 AND sd1.active =0
                    INNER JOIN sys_specific_definitions sd2 ON sd2.main_group = 15 AND sd2.first_group= a.deleted AND sd2.language_code = a.language_code AND sd2.deleted =0 AND sd2.active =0 
                    INNER JOIN sys_specific_definitions sd3 ON sd3.main_group = 16 AND sd3.first_group= a.active AND sd3.language_code = a.language_code AND sd3.deleted = 0 AND sd3.active = 0
                    LEFT JOIN sys_specific_definitions sd4 ON sd4.main_group = 1 AND sd4.first_group= a.active AND sd4.language_code = a.language_code AND sd4.deleted = 0 AND sd4.active = 0
                    INNER JOIN sys_language l ON l.language_main_code = a.language_code AND l.deleted =0 AND l.active =0 
                    INNER JOIN info_users u ON u.id = a.op_user_id  
                    WHERE a.deleted =0 AND language_code = :language_code 
                    ORDER BY a.firm_name   
                          ");
            $statement->bindValue(':language_code', $params['language_code'], \PDO::PARAM_STR); 
            $statement->execute();
            $result = $statement->fetcAll(\PDO::FETCH_ASSOC);
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
     * @ info_users tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  30.12.2015
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            /**
             * table names and column names will be changed for specific use
             */
            $statement = $pdo->prepare("
                INSERT INTO info_users(
                        name, name_eng, language_code, language_parent_id, 
                        op_user_id, flag_icon_road, country_code3, priority   )
                VALUES (
                        :name,
                        :name_eng, 
                        :language_code,
                        :language_parent_id,
                        :user_id,
                        :flag_icon_road,                       
                        :country_code3,
                        :priority 
                                                ");
            $statement->bindValue(':name', $params['name'], \PDO::PARAM_STR);
            $statement->bindValue(':name_eng', $params['name_eng'], \PDO::PARAM_STR);
            $statement->bindValue(':language_code', $params['language_code'], \PDO::PARAM_STR);
            $statement->bindValue(':language_parent_id', $params['language_parent_id'], \PDO::PARAM_INT);
            $statement->bindValue(':user_id', $params['user_id'], \PDO::PARAM_INT);
            $statement->bindValue(':flag_icon_road', $params['flag_icon_road'], \PDO::PARAM_STR);
            $statement->bindValue(':country_code3', $params['country_code3'], \PDO::PARAM_STR);
            $statement->bindValue(':priority', $params['priority'], \PDO::PARAM_INT);

            $result = $statement->execute();

            $insertID = $pdo->lastInsertId('info_users_id_seq');

            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            $pdo->commit();
            return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * info_users tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  30.12.2015
     * @param array | null $args  
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();           
            $statement = $pdo->prepare("
                UPDATE info_users
                SET              
                    name = :name, 
                    name_eng = :name_eng, 
                    language_code = :language_code,                    
                    language_parent_id = :language_parent_id,
                    op_user_id = :user_id,
                    flag_icon_road = :flag_icon_road,                       
                    country_code3 = :country_code3,
                    priority = :priority 
                WHERE id = :id");
            $statement->bindValue(':id', $params['id'], \PDO::PARAM_INT);
            $statement->bindValue(':name', $params['name'], \PDO::PARAM_STR);
            $statement->bindValue(':name_eng', $params['name_eng'], \PDO::PARAM_STR);
            $statement->bindValue(':language_code', $params['language_code'], \PDO::PARAM_STR);
            $statement->bindValue(':language_parent_id', $params['language_parent_id'], \PDO::PARAM_INT);
            $statement->bindValue(':user_id', $params['user_id'], \PDO::PARAM_INT);
            $statement->bindValue(':flag_icon_road', $params['flag_icon_road'], \PDO::PARAM_STR);
            $statement->bindValue(':country_code3', $params['country_code3'], \PDO::PARAM_STR);
            $statement->bindValue(':priority', $params['priority'], \PDO::PARAM_INT);
            $update = $statement->execute();
            $affectedRows = $statement->rowCount();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            $pdo->commit();
            return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    /**
     * 
     * @author Okan CIRAN
     * @ public key e ait bir private key li kullanıcı varsa True değeri döndürür.  !!
     * @version v 1.0  31.12.2015
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function pkTempControl($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');            
            $sql = "     
                        SELECT id,pkey,sf_private_key_value_temp ,root_id FROM (
                            SELECT id, 	
                                CRYPT(sf_private_key_value_temp,CONCAT('_J9..',REPLACE('".$params['pktemp']."','*','/'))) = CONCAT('_J9..',REPLACE('".$params['pktemp']."','*','/')) AS pkey,	                                
                                sf_private_key_value_temp , root_id
                            FROM info_users WHERE active=0 AND deleted=0) AS logintable
                        WHERE pkey = TRUE
                    ";  
            $statement = $pdo->prepare($sql);
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
     * 
     * @author Okan CIRAN
     * @ public key e ait bir private key li kullanıcı varsa True değeri döndürür.  !!
     * @version v 1.0  31.12.2015
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function pkControl($params = array()) {
        try {
            $pdo_devamsizlik = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
               
             $publicKey = 'CCCCCCCCCC';
            if (isset($params['pk']) && $params['pk'] != "") {
                $publicKey = $params['pk'];
            }   
            
            $sql = "              
                    SELECT 
                        act.public_key,
                        act.usid,
                        iu.sf_private_key_value,
                        iu.oid,
                        1 AS control
                    FROM  act_session act
                    INNER join info_users iu ON iu.active=0 AND iu.deleted =0 AND iu.id = act.usid
                    WHERE public_key ='".$publicKey."' 
                    ";  
          // print_r($sql); 
            $statement_devamsizlik = $pdo_devamsizlik->prepare($sql);  
            $statement_devamsizlik->execute();
            $result = $statement_devamsizlik->fetchAll(\PDO::FETCH_ASSOC);
          
            $sfprivatekeyvalue =  'DDDDDDDDD';
            $oid = 'EEEEEEEEE'; 
            $usid = -1; 
             if ($result[0]['control']==1) {
                    $sfprivatekeyvalue = $result[0]['sf_private_key_value'];
                    $oid = $result[0]['oid']; 
                    $usid = $result[0]['usid']; 
                }
             
            $errorInfo = $statement_devamsizlik->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
         //   return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            
             
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            
              $sql = "              
                    SELECT ".$usid.",pkey, sf_private_key_value FROM (
                            SELECT   	
                                CRYPT('".$sfprivatekeyvalue."',CONCAT('_J9..',REPLACE('".$publicKey."','*','/'))) = CONCAT('_J9..',REPLACE('".$publicKey."','*','/')) as pkey,	                                
                                '".$sfprivatekeyvalue."' as sf_private_key_value
                           ) AS logintable
                        WHERE pkey = TRUE

                    "; 
            
           // print_r($sql);
            
            $statement = $pdo->prepare($sql);  
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
          // print_r($result);

            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            
             
            
           /* $sql = "              
                    SELECT id,pkey,sf_private_key_value FROM (
                            SELECT COALESCE(NULLIF(root_id, 0),id) AS id, 	
                                CRYPT(sf_private_key_value,CONCAT('_J9..',REPLACE('".$params['pk']."','*','/'))) = CONCAT('_J9..',REPLACE('".$params['pk']."','*','/')) AS pkey,	                                
                                sf_private_key_value
                            FROM info_users WHERE active=0 AND deleted=0) AS logintable
                        WHERE pkey = TRUE
                    "; 
            * *
            */
            
        } catch (\PDOException $e /* Exception $e */) {       
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ login için info_users tablosundan çekilen kayıtları döndürür   !!
     * bu fonksiyon aktif olarak kullanılmıyor. ihtiyaç a göre aktifleştirilecek. public key algoritması farklı. 
     * @version v 1.0  31.12.2015
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function pkLoginControl($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $sql = "          
                SELECT 
                    a.id,
                    a.name, 
                    a.surname, 
                    a.username, 
                    a.auth_email, 
                    a.gender_id, 
                    sd4.description AS gender, 
                    a.active, 
                    a.auth_allow_id, 
                    sd.description AS auth_alow,
                    a.cons_allow_id,
                    sd1.description AS cons_allow,
                    a.language_code AS user_language,  
                    COALESCE(NULLIF(l.language_main_code,''),'en') AS language_main_code,
                    a.sf_private_key_value,
                    COALESCE(NULLIF( 
                    (SELECT CAST(MIN(bz.parent) AS varchar(5))
                            FROM sys_acl_roles az 
                            LEFT JOIN sys_acl_roles bz ON bz.parent = az.id   
                            WHERE az.id= sarmap.role_id),''), CAST(sar.parent AS varchar(5))) AS Menu_type,
                    root_id
                FROM info_users a              
                LEFT JOIN sys_language l ON l.language_main_code = a.language_code AND l.deleted =0 AND l.active =0 
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 13 AND sd.language_code = COALESCE(NULLIF(l.language_main_code, ''), 'en') AND a.auth_allow_id = sd.first_group 
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 14 AND sd1.language_code = COALESCE(NULLIF(l.language_main_code, ''), 'en') AND a.cons_allow_id = sd1.first_group 
                INNER JOIN sys_specific_definitions sd3 ON sd3.main_group = 16 AND sd3.first_group= a.active AND sd3.language_code = COALESCE(NULLIF(l.language_main_code, ''),'en') AND sd3.deleted = 0 AND sd3.active = 0
                INNER JOIN sys_specific_definitions sd4 ON sd4.main_group = 3 AND sd4.first_group= a.active AND sd4.language_code = COALESCE(NULLIF(l.language_main_code, ''),'en') AND sd4.deleted = 0 AND sd4.active = 0
                
                
                INNER JOIN sys_acl_roles sar ON sar.id = a.role_id AND sar.active=0 AND sar.deleted=0 
                WHERE  
                    CRYPT(a.sf_private_key_value,CONCAT('_J9..',REPLACE('".$params['pk']."','*','/'))) = CONCAT('_J9..',REPLACE('".$params['pk']."','*','/')) 
                    ";
            $statement = $pdo->prepare($sql);            
      //      echo debugPDO($sql, $parameters);
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
     * @ info_users tablosundan public key i döndürür   !!
     * @version v 1.0  31.12.2015
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getPK($params = array()) {
        try {
            $pdoDevamsizlik = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            $username = '-2';
            if (isset($params['username']) && $params['username'] != "") {
                $username = $params['username'];
            }
            $password = '-1';
            if (isset($params['password']) && $params['password'] != "") {
                $password = $params['password'];
            } 

            $sql = "  
                SELECT 
                    a.id, 
                    a.username,
                    a.password,
                    a.oid,
                    a.sf_private_key,
                    a.sf_private_key_value,
                    a.sf_private_key_value_temp,
                    a.role_id,
                    a.language_id,
                    concat(b.name,' ',b.surname) as adsoyad
                FROM BILSANET_DEVAMSIZLIK.dbo.info_users a
                INNER JOIN BILSANET_DEVAMSIZLIK.dbo.info_users_detail b on a.id = b.root_id
                where a.username = '" . $username . "' and  
                    a.active =0 and
                    a.deleted =0 and 
                    a.role_id !=5
                ";

            $statementDevamsizlik = $pdoDevamsizlik->prepare($sql);
            // echo debugPDO($sql, $params);
            $statementDevamsizlik->execute();
            $resultDevamsizlik = $statementDevamsizlik->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statementDevamsizlik->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);

            $userid = NULL;
            if (isset($resultDevamsizlik[0]['id']) && $resultDevamsizlik[0]['id'] != "") {
                $userid = $resultDevamsizlik[0]['id'];
            }
            $oid = NULL;
            if (isset($resultDevamsizlik[0]['oid']) && $resultDevamsizlik[0]['oid'] != "") {
                $oid = $resultDevamsizlik[0]['oid'];
            }
            $xpassword = NULL;
            if (isset($resultDevamsizlik[0]['password']) && $resultDevamsizlik[0]['password'] != "") {
                $xpassword = $resultDevamsizlik[0]['password'];
            }
            $privateKeyValue = NULL;
            if (isset($resultDevamsizlik[0]['sf_private_key_value']) && $resultDevamsizlik[0]['sf_private_key_value'] != "") {
                $privateKeyValue = $resultDevamsizlik[0]['sf_private_key_value'];
            }

            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "  
                WITH pascontrol AS (
                SELECT   /* ARMOR(pgp_sym_encrypt ('" . $password . "' , '" . $oid . "', 'compress-algo=1, cipher-algo=bf')) passwordx  */ 
                   Pgp_sym_decrypt (dearmor('" . $xpassword . "'), '" . $oid . "', 'compress-algo=1, cipher-algo=bf')   as xpasword    
                )   
                SELECT 
                    1 AS success ,
                    REPLACE(TRIM(SUBSTRING(crypt('" . $privateKeyValue . "',gen_salt('xdes')),6,20)),'/','*') as public_key,
                    '".$resultDevamsizlik[0]['adsoyad']."' as adsoyad
                FROM pascontrol
                WHERE xpasword = '" . $password . "'  ;  

            ";

            $statement = $pdo->prepare($sql);
            // echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $control = NULL;
            if (isset($result[0]['success']) && $result[0]['success'] != "") {
                $control = $result[0]['success'];
            }
            $publickey = null;
            if (isset($result[0]['public_key']) && $result[0]['public_key'] != "") {
                $publickey = $result[0]['public_key'];
            }
            $sessionManager = $serviceLocator
                               ->get('SessionManagerDefault');
            $sessionID = $sessionManager->getId();
            //print_r('----'.$sessionID);  
          //  $sessionID =session_create_id($publickey);
            if (isset($params['sessionID']) && $params['sessionID'] != "") {
                $sessionID = $params['sessionID'];
            }
            if ($control === 1) {
                $pdoDevamsizlik->beginTransaction();
                $sql = "    
                    INSERT INTO [BILSANET_DEVAMSIZLIK].[dbo].[act_session]
                        (id,
                        name,
                        data,
                        public_key,
                        usid,
                        acl  )
                        Values (
                        '".$sessionID."','Devamsizlik','','".$publickey."', ".$userid." ,'') 
                       
                ";
                $statementact = $pdoDevamsizlik->prepare($sql);
                //echo debugPDO($sql, $params);
                $resultx = $statementact->execute();

                $errorInfo = $statementact->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL) {
                    $pdoDevamsizlik->rollback();
                    throw new \PDOException($errorInfo[0]);
                }
                $pdoDevamsizlik->commit();

               /* $result = array( 
                    "success"=> $control,
                    "adsoyad" => $resultDevamsizlik[0]['adsoyad'],
                    "public_key"=> $publickey,  
                );
                * *
                */
            } else {
                $errorInfoColumn = 'Sesion';
                $errorInfo[1] = '-99999';
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn,"sessionID" => $sessionID);
            }


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
     * @ public key e ait bir private key li kullanıcı varsa True değeri döndürür.  !!
     * @version v 1.0  31.12.2015
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function pkSessionControl($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
            /*
            $sql = "          
                SELECT * FROM (
                    SELECT id, sf_private_key_value = 
                    Pgp_sym_decrypt( 
                     DEARMOR(CONCAT( '-----BEGIN PGP MESSAGE-----

                    ',:pk,'
                    -----END PGP MESSAGE-----
                    '))
                    , 'Bahram Lotfi Sadigh', 'compress-algo=1, cipher-algo=bf') AS pkey
                    FROM info_users) AS logintable
                WHERE pkey = TRUE                
                                 ";             
             */
            $sql = "    
                    SELECT 
                        a.id, 
                        a.name, 
                        a.data, 
                        a.lifetime, 
                        a.c_date, 
                        a.modified, 
                        a.public_key, 
                        b.name AS u_name, 
                        b.surname AS u_surname, 
                        b.username,
                        b.sf_private_key_value,
                        b.root_id  ,
                        b.id as user_id
                    FROM act_session a 
                    INNER JOIN info_users b ON b.id = a.usid
                        AND b.active = 0 AND b.deleted = 0
                    WHERE a.public_key = :public_key 
                    ";  
            
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':public_key', $params['pk'], \PDO::PARAM_STR);
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
     * 
     * @author Okan CIRAN
     * @ public key varsa True değeri döndürür.  !!
     * @version v 1.0  06.01.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function pkIsThere($params = array()) {
        try { 
             
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectDevamsizlikFactory');
       
            $sql = "              
                    /*  SELECT a.public_key =  '".$params['pk']."' */ 
                    SELECT cast(1 as bit) as kontrol
                    FROM act_session a                  
                    WHERE a.public_key =   '".$params['pk']."'
                        
                    ";   
         //   print_r($sql);
            $statement = $pdo->prepare($sql);            
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
     * get company public key due to user public key
     * @param type $publicKey
     * @return type
     * @throws \PDOException
     * @author Okan CIRAN
     * @since 10/06/2016
     */
    public function isUserBelongToCompany($requestHeaderParams, $params) {
        try {
            $resultSet = $this->pkControl(array('pk' =>$requestHeaderParams['X-Public']));
            
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');  
            
            $sql = "              
                    SELECT firm_id AS firm_id, 1=1 AS control FROM (
                            SELECT a.firm_id ,
                             CRYPT(sf_private_key_value,CONCAT('_J9..',REPLACE('".$params['cpk']."','*','/'))) = CONCAT('_J9..',REPLACE('".$params['cpk']."','*','/')) as cpk 
                            FROM info_firm_keys a                                                        
                INNER JOIN info_firm_profile ifp ON ifp.active =0 AND ifp.deleted =0 AND ifp.language_parent_id =0 AND a.firm_id = ifp.act_parent_id     
                INNER JOIN info_firm_users ifu ON ifu.user_id = " . intval($resultSet['resultSet'][0]['id']) . " AND ifu.language_parent_id =0 AND a.firm_id = ifu.firm_id
                ) AS xtable WHERE cpk = TRUE  limit 1
                    "; 
            
           // print_r($sql);
            
            $statement = $pdo->prepare($sql);  
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
           // print_r($result);

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
     * 
     * @author Okan CIRAN
     * @ parametre olarak gelen public key in private key inden üretilmiş aktif tüm public key leri döndürür.  !!     
     * @version v 1.0  06.01.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function pkAllPkGeneratedFromPrivate($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');          
            $sql = "  
                    SELECT ax.id, ax.name,ax.data,ax.lifetime,ax.c_date,ax.public_key FROM act_session ax 
                    WHERE 
                        CRYPT((SELECT b.sf_private_key_value
                                            FROM act_session a 
                                            INNER JOIN info_users b ON CRYPT(b.sf_private_key_value,CONCAT('_J9..',REPLACE(a.public_key,'*','/'))) = CONCAT('_J9..',REPLACE(a.public_key,'*','/'))
                                                AND b.active = 0 AND b.deleted = 0
                                            WHERE a.public_key = '".$params['pk']."'
                        ),CONCAT('_J9..',REPLACE(ax.public_key,'*','/'))) = CONCAT('_J9..',REPLACE(ax.public_key,'*','/')) 
                    ";                       
            $statement = $pdo->prepare($sql);                        
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
