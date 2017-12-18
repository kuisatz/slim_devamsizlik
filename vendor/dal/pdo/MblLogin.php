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
class MblLogin extends \DAL\DalSlim {

    /**     
     * @author Okan CIRAN 
     * @version v 1.0  25.10.2017
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
     * @version v 1.0  25.10.2017  
     * @param array | null $args  
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
     * @version v 1.0  25.10.2017
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
     * @version v 1.0  25.10.2017
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
     * 
     * @author Okan CIRAN 
     * @version v 1.0  25.10.2017
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
          //  $statement->execute();
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
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function pkControl($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "              
                    SELECT id,pkey,sf_private_key_value FROM (
                            SELECT COALESCE(NULLIF(root_id, 0),id) AS id, 	
                                CRYPT(sf_private_key_value,CONCAT('_J9..',REPLACE('".$params['pk']."','*','/'))) = CONCAT('_J9..',REPLACE('".$params['pk']."','*','/')) AS pkey,	                                
                                sf_private_key_value
                            FROM info_users WHERE active=0 AND deleted=0) AS logintable
                        WHERE pkey = TRUE
                    "; 
            $statement = $pdo->prepare($sql);            
        //    $statement->execute();
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
     * @ login için mebkodunu döndürür   !! 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKullaniciMebKoduFindByTcKimlikNo($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            }  
            $dbConfigValue = 'pgConnectFactory';
         //   $dbConfig =  MobilSetDbConfigx::mobilDBConfig($params);
         //   if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
        //        $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
        //    }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $tc = '011111111110';
            if ((isset($params['tc']) && $params['tc'] != "")) {
                $tc = $params['tc'];
            } 
            
          /*  $sql = "          
                exec PRC_GNL_KullaniciMebKodu_FindByTcKimlikNo @TcKimlikNo=  '".$tc."'
                 ";
            */ 
            
            $sql = "  
            SET NOCOUNT ON;     
            IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc.";  
            IF OBJECT_ID('tempdb..##okiMEBKodu".$tc."') IS NOT NULL DROP TABLE ##okiMEBKodu".$tc."; 
            DECLARE @name nvarchar(200)=''  collate SQL_Latin1_General_CP1254_CI_AS;
            declare @database_id int;
            declare @tc nvarchar(11) =''  collate SQL_Latin1_General_CP1254_CI_AS;
            DECLARE @sqlx nvarchar(2000)=''  collate SQL_Latin1_General_CP1254_CI_AS; 
            DECLARE @sqlxx nvarchar(2000)= '' collate SQL_Latin1_General_CP1254_CI_AS;
            declare @MEBKodu int;   
            set @tc =  ".$tc."; 
            CREATE TABLE #okidbname".$tc." (database_id int , name  nvarchar(200) , sqlx nvarchar(2000),MEBKodu int ); 
            CREATE TABLE ##okiMEBKodu".$tc." ( MEBKodu int ); 
 
            
            DECLARE db_cursor CURSOR FOR  
            SELECT distinct sss.database_id, sss.name FROM Sys.databases sss
                INNER JOIN [BILSANET_MOBILE].[dbo].[Mobile_tcdb] tcdbb on  sss.database_id = tcdbb.dbID AND tcdbb.active = 0 AND tcdbb.deleted =0   
                INNER JOIN [BILSANET_MOBILE].[dbo].[Mobile_tc] tcc ON tcdbb.tcID = tcc.id AND tcc.active = 0 AND tcc.deleted =0                
                WHERE 
                    sss.state = 0 and 
                    tcc.[tc]= @tc and 
                    banTarihi is null  ;

            OPEN db_cursor   
            FETCH NEXT FROM db_cursor INTO  @database_id , @name 
            WHILE @@FETCH_STATUS = 0   
            BEGIN   

            INSERT INTO #okidbname".$tc." ( database_id , name , sqlx ) VALUES
                            (@database_id, CAST(@name AS nvarchar(200)) ,   'select '+ cast(@database_id as varchar(10))+'; exec ['+@name+'].[dbo].PRC_GNL_KullaniciMebKodu_FindByTcKimlikNo @TcKimlikNo= '+@tc  );


            SET @sqlxx = ' INSERT ##okiMEBKodu".$tc."  exec ['+@name+'].[dbo].PRC_GNL_KullaniciMebKodu_FindByTcKimlikNo @TcKimlikNo= '+@tc ; 
            EXEC sp_executesql @sqlxx; 

            update  #okidbname".$tc." 
                set MEBKodu = (select * from ##okiMEBKodu".$tc." WHERE MEBKodu IS NOT NULL)
            where database_id =  @database_id;
            
            /*  delete from  ##okiMEBKodu".$tc." ; */
        
            FETCH NEXT FROM db_cursor INTO @database_id,  @name;
            END   

            CLOSE db_cursor;
            DEALLOCATE db_cursor ;

            select top 1 database_id , name , MEBKodu  from #okidbname".$tc." 
            where MEBKodu is not null  ;  

            IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc."; 
            IF OBJECT_ID('tempdb..##okiMEBKodu".$tc."') IS NOT NULL DROP TABLE ##okiMEBKodu".$tc."; 
            SET NOCOUNT OFF;
                ";
             
            
            /*
             * 
               UPDATE
                GNL_Kullanicilar
                SET
                 Sifre='1YTr63O9Mdeg54DZefZg16g=='
             * 
             */
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
     * @ login için user id döndürür   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKullaniciFindForLoginByTcKimlikNo_eskisi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid, ));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $mebKoduValue = NULL;
            $dbnameValue = NULL;
            $mebKodu = $this->gnlKullaniciMebKoduFindByTcKimlikNo(array('tc' => $params['tc']));
            if ((isset($mebKodu['resultSet'][0]['MEBKodu']) && $mebKodu['resultSet'][0]['MEBKodu'] != "")) {                                    
                    $mebKoduValue = $mebKodu['resultSet'][0]['MEBKodu'];
                    $dbnameValue = $mebKodu['resultSet'][0]['name'].'.';
            }  
                    
            if ((isset($params['sifre']) && $params['sifre'] != "")) {            
                $wsdlValue = NULL; 
                $wsdl =  MobilSettings::mobilwsdlEncryptPassword(array('PswrD' => $params['sifre']));  
                if ((isset($wsdl['resultSet'] ) && $wsdl['resultSet']  != "")) {                                    
                    $wsdlValue = $wsdl['resultSet'] ; 
                }   
                
                $languageIdValue = 647;
                if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                    $languageIdValue = $params['LanguageID'];
                }
                $sifre =$wsdlValue ;  
                
               // $deviceid = NULL;
                if ((isset($params['DeviceID']) && $params['DeviceID'] != "")) {
                   // $deviceid = $params['DeviceID']; 
                    $MobilSettingsaddDevice = $this->slimApp-> getBLLManager()->get('mobilSettingsBLL');  
                    $MobilSettingsaddDeviceArray= $MobilSettingsaddDevice->addDevice($params);
                
                    if ($MobilSettingsaddDeviceArray['errorInfo'][0] != "00000" &&
                            $MobilSettingsaddDeviceArray['errorInfo'][1] != NULL &&
                            $MobilSettingsaddDeviceArray['errorInfo'][2] != NULL)
                        throw new \PDOException($MobilSettingsaddDeviceArray['errorInfo']);
  
                }    
                
            }
            /////////////////////////////////////////////////
            $tc = '011111111110';
            if ((isset($params['tc']) && $params['tc'] != "")) {
                $tc = $params['tc'];
            } 
            if ($sifre == NULL){
                $tc = '00000000000';
                $sifre = '00000000000';
            }
            $sql = "    
            DECLARE @KisiID uniqueidentifier ; 

            EXEC ".$dbnameValue."[dbo].[PRC_GNL_Kullanici_Find_For_Login_ByTcKimlikNo]
		@KisiID = @KisiID OUTPUT,
		@MEBKodu = ".intval($mebKoduValue).",
		@TcKimlikNo = '".$tc."',
		@Sifre = N'".$sifre."' ;  
            
            SELECT 
                @KisiID as KisiID,   
                concat(kk.[Adi] collate SQL_Latin1_General_CP1254_CI_AS,' ' ,kk.[Soyadi] collate SQL_Latin1_General_CP1254_CI_AS ) as adsoyad,   
                kk.[TCKimlikNo] ,
                ff.Fotograf,
                kk.CinsiyetID
            FROM  ".$dbnameValue."[dbo].[GNL_Kisiler] kk 
            LEFT JOIN ".$dbnameValue."dbo.GNL_Fotograflar ff on ff.KisiID =kk.[KisiID] 
            where  kk.[KisiID] = @KisiID    ; 
             ";
            
            /*
             * 
               UPDATE
                GNL_Kullanicilar
                SET
                 Sifre='1YTr63O9Mdeg54DZefZg16g=='
             * 
             */
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
     * @ login için user id döndürür   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */    
    public function gnlKullaniciFindForLoginByTcKimlikNo($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            } 
            $dbConfigValue = 'pgConnectFactoryMobil';
         /*   $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }  
          * 
          */ 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            /*
            $mebKoduValue = NULL;
            $dbnameValue = NULL;
            $mebKodu = $this->gnlKullaniciMebKoduFindByTcKimlikNo(array('tc' => $params['tc']));
            if ((isset($mebKodu['resultSet'][0]['MEBKodu']) && $mebKodu['resultSet'][0]['MEBKodu'] != "")) {                                    
                    $mebKoduValue = $mebKodu['resultSet'][0]['MEBKodu'];
                    $dbnameValue = $mebKodu['resultSet'][0]['name'].'.';
            }  
            */
            
            if ((isset($params['sifre']) && $params['sifre'] != "")) {            
                $wsdlValue = NULL; 
                $wsdl =  MobilSettings::mobilwsdlEncryptPassword(array('PswrD' => $params['sifre']));  
                if ((isset($wsdl['resultSet'] ) && $wsdl['resultSet']  != "")) {                                    
                    $wsdlValue = $wsdl['resultSet'] ; 
                }   
                
                $languageIdValue = 647;
                if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                    $languageIdValue = $params['LanguageID'];
                }
                $sifre =$wsdlValue ;  
                
               // $deviceid = NULL;
                if ((isset($params['DeviceID']) && $params['DeviceID'] != "")) {
                   // $deviceid = $params['DeviceID']; 
                    $MobilSettingsaddDevice = $this->slimApp-> getBLLManager()->get('mobilSettingsBLL');  
                    $MobilSettingsaddDeviceArray= $MobilSettingsaddDevice->addDevice($params);
                
                    if ($MobilSettingsaddDeviceArray['errorInfo'][0] != "00000" &&
                            $MobilSettingsaddDeviceArray['errorInfo'][1] != NULL &&
                            $MobilSettingsaddDeviceArray['errorInfo'][2] != NULL)
                        throw new \PDOException($MobilSettingsaddDeviceArray['errorInfo']);
  
                }    
                
            }
            /////////////////////////////////////////////////
            $tc = '011111111110';
            if ((isset($params['tc']) && $params['tc'] != "")) {
                $tc = $params['tc'];
            } 
            if ($sifre == NULL){
                $tc = '00000000000';
                $sifre = '00000000000';
            }
            $sql = "  
            SET NOCOUNT ON;     
            IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc.";  
            IF OBJECT_ID('tempdb..##okidetaydata".$tc."') IS NOT NULL DROP TABLE ##okidetaydata".$tc."; 
           
            DECLARE @name nvarchar(200) =''  collate SQL_Latin1_General_CP1254_CI_AS;
            DECLARE @database_id int, @MEBKodu int ; 
            DECLARE @tc nvarchar(11)  =''  collate SQL_Latin1_General_CP1254_CI_AS,
                    @sifre nvarchar(50) =''  collate SQL_Latin1_General_CP1254_CI_AS;
            DECLARE @sqlx nvarchar(2000) =''  collate SQL_Latin1_General_CP1254_CI_AS; 
            DECLARE @sqlxx nvarchar(2000) =''  collate SQL_Latin1_General_CP1254_CI_AS;
            DECLARE @KurumID uniqueidentifier, @KisiID uniqueidentifier ; 
              
            CREATE TABLE #okidbname".$tc."(database_id int , name  nvarchar(200) collate SQL_Latin1_General_CP1254_CI_AS , sqlx nvarchar(2000)  collate SQL_Latin1_General_CP1254_CI_AS,MEBKodu int );  
            CREATE TABLE ##okidetaydata".$tc." (KisiID uniqueidentifier, adsoyad  nvarchar(200)  collate SQL_Latin1_General_CP1254_CI_AS ,  TCKimlikNo nvarchar(11)  collate SQL_Latin1_General_CP1254_CI_AS, Fotograf varbinary, CinsiyetID int );

            set @tc = ".$tc.";  
            set @sifre = N'".$sifre."';
            
            DECLARE db_cursor CURSOR FOR  
                SELECT sss.database_id, sss.name, tcdbb.KisiID, tcdbb.KurumID FROM Sys.databases sss
                INNER JOIN [BILSANET_MOBILE].[dbo].[Mobile_tcdb] tcdbb on  sss.database_id = tcdbb.dbID AND tcdbb.active = 0 AND tcdbb.deleted =0   
                INNER JOIN [BILSANET_MOBILE].[dbo].[Mobile_tc] tcc ON tcdbb.tcID = tcc.id AND tcc.active = 0 AND tcc.deleted =0
                WHERE 
                    sss.state = 0 AND 
                    tcc.[tc]= @tc AND 
                    tcdbb.[sifre]  collate SQL_Latin1_General_CP1254_CI_AS = @sifre  collate SQL_Latin1_General_CP1254_CI_AS AND 
                    banTarihi is null; 

            OPEN db_cursor   
            FETCH NEXT FROM db_cursor INTO  @database_id , @name ,@KisiID , @KurumID
            WHILE @@FETCH_STATUS = 0   
            BEGIN   
                IF OBJECT_ID(@name+'..GNL_Kisiler' ) IS NOT NULL
                begin 
                    SET @sqlxx =   ' 
                        INSERT into  ##okidetaydata".$tc." (KisiID, adsoyad, TCKimlikNo, /*Fotograf, */ CinsiyetID)
                         SELECT 
                                kk.[KisiID],   
                                concat(kk.[Adi]  collate SQL_Latin1_General_CP1254_CI_AS ,'' '' ,kk.[Soyadi]  collate SQL_Latin1_General_CP1254_CI_AS ) as adsoyad,   
                                kk.[TCKimlikNo] ,
                               /* ff.Fotograf,*/
                                kk.CinsiyetID
                        FROM  ['+@name+'].[dbo].[GNL_Kisiler] kk 
                        LEFT JOIN ['+@name+'].dbo.GNL_Fotograflar ff on ff.KisiID =kk.[KisiID] 
                        WHERE kk.[KisiID] = '''+cast(@KisiID as nvarchar(50))+'''' ; 
                     EXEC sp_executesql @sqlxx; 
                END
          
            FETCH NEXT FROM db_cursor INTO @database_id,  @name , @KisiID , @KurumID;
            END   

            CLOSE db_cursor;
            DEALLOCATE db_cursor ;
   
            SELECT TOP 1 * FROM  ##okidetaydata".$tc."
            order by Fotograf

            IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc.";  
            IF OBJECT_ID('tempdb..##okidetaydata".$tc."') IS NOT NULL DROP TABLE ##okidetaydata".$tc."; 

            SET NOCOUNT OFF; 
 
             ";
            
            /*
             * 
               UPDATE
                GNL_Kullanicilar
                SET
                 Sifre='1YTr63O9Mdeg54DZefZg16g=='
             * 
             */
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
     * @author Okan CIRAN  -- kullanılmıyor
     * @ login olan userin rol bilgileri ve okul id leri   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKisiTumRollerFindByID($params = array()) {
        try {
            
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
             
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }    
            
            $sql = "   
                DECLARE @return_value int;

                EXEC @return_value = [dbo].[PRC_GNL_Kisi_TumRoller_FindByID]
                    @KisiID =  '".$kisiId."' ;

                SELECT  
                    null as KisiID , 
                    'LUTFEN SEÇİNİZ...' AS adsoyad,
                    '' AS [TCKimlikNo]   
                UNION 
                SELECT 'Return Value' = @return_value;
 
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
     * @ login olan userin okul bilgileri ve okul id leri   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function mobilfirstdata_eskisi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
          
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }    
            $sql = "  
                    set nocount on;
                    IF OBJECT_ID('tempdb..#okimobilfirstdata') IS NOT NULL DROP TABLE #okimobilfirstdata; 
    
                    CREATE TABLE #okimobilfirstdata
                                    (
                                            [OkulKullaniciID]  [uniqueidentifier],
                                            [OkulID] [uniqueidentifier], 
                                            [KisiID] [uniqueidentifier],
                                            [RolID]  int,
                                            [RolAdi] varchar(100)  
                                    ) ;
                   
                    INSERT #okimobilfirstdata  EXEC  [dbo].[PRC_GNL_Kisi_TumRoller_FindByID]  @KisiID= '".$kisiId."' ;
                      
                        SELECT  
                            null AS OkulKullaniciID ,
                            null AS OkulID,
                            null AS KisiID,
                            -1 AS RolID, 
                            'LÜTFEN SEÇİNİZ...' AS OkulAdi,
                            '' AS MEBKodu,
                            '' AS ePosta,
                             null AS DersYiliID,
                            '' AS EgitimYilID, 
                            '' AS EgitimYili,
                            0 AS DonemID 

                        UNION  	 

                        SELECT  
                            sss.[OkulKullaniciID] ,
                            sss.[OkulID],
                            sss.[KisiID],
                            sss.[RolID], 
                            upper(concat(oo.[OkulAdi], ' / (',rr.[RolAdi],')' )) as OkulAdi,
                            oo.[MEBKodu],
                            oo.[ePosta],
                            DY.DersYiliID,
                            DY.EgitimYilID, 
                            EY.EgitimYili,
                            DY.DonemID 
                        FROM #okimobilfirstdata sss
                        inner join [dbo].[GNL_Okullar] oo ON oo.[OkulID] = sss.[OkulID] 
                        inner join GNL_DersYillari DY ON DY.OkulID = sss.OkulID and DY.AktifMi =1 
                        inner join GNL_EgitimYillari EY ON EY.EgitimYilID = DY.EgitimYilID AND DY.AktifMi = 1
                        inner join [GNL_Roller] rr ON rr.[RolID] =  sss.[RolID];

                    IF OBJECT_ID('tempdb..#okimobilfirstdata') IS NOT NULL DROP TABLE #okimobilfirstdata; 
                    SET NOCOUNT OFF;

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
     * @ login olan userin okul bilgileri ve okul id leri   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function mobilfirstdata($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbConfigValue = 'pgConnectFactoryMobil';
         /*   $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass'];
                // $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['dbname'];
            }   
            */
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $tc = '01111111110';
            if ((isset($params['tcno']) && $params['tcno'] != "")) {
                $tc = $params['tcno'];
            }    
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            $sql = "  
            SET NOCOUNT ON;     
            IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc.";  
            IF OBJECT_ID('tempdb..##okidetaydata".$tc."') IS NOT NULL DROP TABLE ##okidetaydata".$tc."; 
            IF OBJECT_ID('tempdb..##okimobilfirstdata".$tc."') IS NOT NULL DROP TABLE ##okimobilfirstdata".$tc."; 
            IF OBJECT_ID('tempdb..##okimobilseconddata".$tc."') IS NOT NULL DROP TABLE ##okimobilseconddata".$tc."; 

            DECLARE @name nvarchar(200) = ''  collate SQL_Latin1_General_CP1254_CI_AS;
            declare @database_id int;
            declare @tc bigint;
            DECLARE @sqlx nvarchar(4000)= ''  collate SQL_Latin1_General_CP1254_CI_AS; 
            DECLARE @sqlxx nvarchar(4000)= ''  collate SQL_Latin1_General_CP1254_CI_AS;
            declare @MEBKodu int;   
		 
            CREATE TABLE #okidbname".$tc."(database_id int , name  nvarchar(200)  collate SQL_Latin1_General_CP1254_CI_AS, sqlx nvarchar(2000)  collate SQL_Latin1_General_CP1254_CI_AS,MEBKodu int );  
            CREATE TABLE ##okidetaydata".$tc."  (dbnamex  nvarchar(200)  collate SQL_Latin1_General_CP1254_CI_AS, KisiID uniqueidentifier, KurumID uniqueidentifier, MEBKodu integer ,database_id int  );

            set @tc = ".$tc.";  
            
            DECLARE db_cursor CURSOR FOR  
            SELECT sss.database_id, sss.name  collate SQL_Latin1_General_CP1254_CI_AS FROM Sys.databases sss
                INNER JOIN [BILSANET_MOBILE].[dbo].[Mobile_tcdb] tcdbb on  sss.database_id = tcdbb.dbID AND tcdbb.active=0 AND tcdbb.deleted =0
                INNER JOIN [BILSANET_MOBILE].[dbo].[Mobile_tc] tcc ON tcdbb.tcID = tcc.id AND tcc.active=0 AND tcc.deleted =0
                WHERE 
                    sss.state = 0 and 
                    tcc.[tc]= @tc and 
                    banTarihi is null; 

            OPEN db_cursor   
            FETCH NEXT FROM db_cursor INTO  @database_id , @name 
            WHILE @@FETCH_STATUS = 0   
            BEGIN   
                IF OBJECT_ID(@name+'..GNL_Kisiler' )  IS NOT NULL
                begin 
                    INSERT INTO #okidbname".$tc." ( database_id , name , sqlx ) VALUES
                                    (@database_id, CAST(@name AS nvarchar(200)) ,   'select '+ cast(@database_id as varchar(10))+'; exec ['+@name+'].[dbo].PRC_GNL_KullaniciMebKodu_FindByTcKimlikNo @TcKimlikNo= '+cast(@tc as nvarchar(11))  );

                    SET @sqlxx =   ' 
                        INSERT into  ##okidetaydata".$tc."  (dbnamex,KisiID , KurumID, MEBKodu, database_id)
                            SELECT 
                                    '''+@name+''', k.KisiID , k.KurumID, kr.MEBKodu ,'+cast(@database_id as nvarchar(10))+'  
                            FROM ['+@name+'].dbo.GNL_Kullanicilar K
                            INNER JOIN ['+@name+'].dbo.GNL_Kurumlar KR ON K.KurumID = KR.KurumID
                            INNER JOIN ['+@name+'].dbo.GNL_Kisiler KS ON K.KisiID = KS.KisiID
                            WHERE KS.TCKimlikNo =   ' +cast(@tc as nvarchar(11)) ; 
                                /* print(@sqlxx); */ 
                    EXEC sp_executesql @sqlxx; 
                                /* -- select * from #okidbname ; */ 
                    update  #okidbname".$tc." 
                        set MEBKodu = (select MEBKodu from ##okidetaydata".$tc." as xxx where xxx.dbnamex = #okidbname".$tc.".name  )
                    where database_id =  @database_id;
                END
          
            FETCH NEXT FROM db_cursor INTO @database_id,  @name;
            END   

            CLOSE db_cursor;
            DEALLOCATE db_cursor ;

          
                CREATE TABLE ##okimobilfirstdata".$tc."
                    (
                        [OkulKullaniciID]  [uniqueidentifier],
                        [OkulID] [uniqueidentifier], 
                        [KisiID] [uniqueidentifier],
                        [RolID]  int,
                        [RolAdi] varchar(100) collate SQL_Latin1_General_CP1254_CI_AS
                    ) ;

                CREATE TABLE ##okimobilseconddata".$tc."
                    (
                        [OkulKullaniciID]  [uniqueidentifier],
                        [OkulID] [uniqueidentifier], 
                        [KisiID] [uniqueidentifier],
                        [RolID]  int,
                        [RolAdi] varchar(100) collate SQL_Latin1_General_CP1254_CI_AS, 
                        OkulAdi varchar(200) collate SQL_Latin1_General_CP1254_CI_AS,
                        MEBKodu bigint,
                        ePosta varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                        DersYiliID [uniqueidentifier],
                        EgitimYilID int, 
                        EgitimYili varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                        DonemID int,
                        KurumID [uniqueidentifier],
                        dbnamex  nvarchar(200) collate SQL_Latin1_General_CP1254_CI_AS,
                        database_id int
                    ) ;
                declare @dbnamex  nvarchar(200) =''  collate SQL_Latin1_General_CP1254_CI_AS ;
                declare @KisiID  uniqueidentifier;
                declare @KurumID  uniqueidentifier; 

                DECLARE db_cursor CURSOR FOR  
                SELECT distinct dbnamex  ,  KisiID  , KurumID  , MEBKodu , database_id  FROM ##okidetaydata".$tc." 
                WHERE MEBKodu is not null ;

                OPEN db_cursor   
                FETCH NEXT FROM db_cursor INTO  @dbnamex  ,  @KisiID  , @KurumID  , @MEBKodu ,@database_id
                WHILE @@FETCH_STATUS = 0   
                BEGIN   

                SET @sqlxx =  ' 
                          INSERT ##okimobilfirstdata".$tc."  EXEC  ['+@dbnamex+'].[dbo].[PRC_GNL_Kisi_TumRoller_FindByID]  @KisiID=  ''' +  cast(@KisiID as varchar(50))+'''   '; 
 
                /* print(@sqlxx); */
                EXEC sp_executesql @sqlxx; 
                /* select * from #okidbname ;*/
                update  #okidbname".$tc." 
                    set MEBKodu = (select MEBKodu from ##okidetaydata".$tc." as xxx where xxx.dbnamex = #okidbname".$tc.".name  )
                where database_id =  @database_id;

                delete from #okidbname".$tc." where MEBKodu is null ; 

                SET @sqlx = '
                insert into ##okimobilseconddata".$tc."  ( [OkulKullaniciID] ,
                            [OkulID],  [KisiID],  [RolID],[RolAdi],OkulAdi,
                            [MEBKodu],  [ePosta],  DersYiliID,  EgitimYilID,   
                            EgitimYili,   DonemID ,KurumID, dbnamex, database_id )
                SELECT  
                    sss.[OkulKullaniciID] ,
                    sss.[OkulID],
                    sss.[KisiID],
                    sss.[RolID], 
                    COALESCE(NULLIF(COALESCE(NULLIF(rrx.RolAdi collate SQL_Latin1_General_CP1254_CI_AS,''''),rrx.RolAdieng collate SQL_Latin1_General_CP1254_CI_AS),''''),rr.RolAdi) as RolAdi ,
                    upper(concat(golx.[OkulAdi]  collate SQL_Latin1_General_CP1254_CI_AS, ''  ('',rrx.[RolAdi]  collate SQL_Latin1_General_CP1254_CI_AS,'')'' )) as OkulAdi,
                    oo.[MEBKodu],
                    oo.[ePosta] collate SQL_Latin1_General_CP1254_CI_AS,
                    DY.DersYiliID,
                    DY.EgitimYilID, 
                    EY.EgitimYili,
                    DY.DonemID ,
                    oo.KurumID , 
                    '''+@dbnamex+''' as dbnamex,
                    '+cast(@database_id as nvarchar(5))+' as database_id
                FROM ##okimobilfirstdata".$tc." sss
                inner join ['+@dbnamex+'].[dbo].[GNL_Okullar] oo ON oo.[OkulID] = sss.[OkulID] 
                inner join ['+@dbnamex+'].[dbo].GNL_DersYillari DY ON DY.OkulID = sss.OkulID and DY.AktifMi =1 
                inner join ['+@dbnamex+'].[dbo].GNL_EgitimYillari EY ON EY.EgitimYilID = DY.EgitimYilID AND DY.AktifMi = 1
                inner join ['+@dbnamex+'].[dbo].[GNL_Roller] rr ON rr.[RolID] =  sss.[RolID]
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue."  AND lx.deleted =0 AND lx.active =0
                LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Okullar_Lng golx ON golx.OkulID = sss.[OkulID] and golx.language_id = lx.id  
                LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Roller_lng rrx on (rrx.language_parent_id = sss.[RolID] or rrx.RolID = sss.[RolID] ) and  rrx.language_id= lx.id  
                WHERE 
                        cast(getdate() AS date) between cast(dy.Donem1BaslangicTarihi AS date) AND cast(dy.Donem2BitisTarihi AS date)
                    ';
                /* print(@sqlx); */
                EXEC sp_executesql @sqlx;  

                FETCH NEXT FROM db_cursor INTO @dbnamex  ,  @KisiID  , @KurumID  , @MEBKodu ,@database_id ;
                END   

                CLOSE db_cursor;
                DEALLOCATE db_cursor ;
                SET NOCOUNT OFF;

          SET NOCOUNT ON;  
                SELECT     
                    null AS OkulKullaniciID ,
                    null AS OkulID,
                    null AS KisiID,
                    -1 AS RolID, 
                    '' AS  RolAdi, 
                    COALESCE(NULLIF(ax.[description],''),a.[description_eng]) AS OkulAdi,
                    '' AS MEBKodu,
                    '' AS ePosta,
                    null AS DersYiliID,
                    '' AS EgitimYilID, 
                    '' AS EgitimYili,
                    0 AS DonemID ,
                    null as KurumID, 
                    '' AS dbnamex ,
                    0 as database_id,
                    '' as serverproxy ,
                    0 as cid
                FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
                INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or  ax.[id] = a.[id]) and  ax.language_id= lx.id  
                WHERE a.[main_group] = 1 and a.[first_group]  = 1 and 
                    a.language_parent_id =0 

                UNION 
                SELECT * from (
                    SELECT  distinct
                        a.OkulKullaniciID,
                        a.OkulID,
                        a.KisiID,
                        a.RolID,
                        a.RolAdi collate SQL_Latin1_General_CP1254_CI_AS as RolAdi,
                        a.OkulAdi,
                        a.MEBKodu,
                        a.ePosta,
                        a.DersYiliID,
                        a.EgitimYilID,
                        a.EgitimYili,
                        a.DonemID,
                        a.KurumID,
                        a.dbnamex collate SQL_Latin1_General_CP1254_CI_AS as dbnamex,
                        a.database_id,
                        isnull(mss.proxy collate SQL_Latin1_General_CP1254_CI_AS, (SELECT TOP 1 xxz.[proxy] collate SQL_Latin1_General_CP1254_CI_AS FROM [BILSANET_MOBILE].[dbo].[Mobil_Settings] xxz  WHERE xxz.database_id = 57 )) as serverproxy,
                        isnull( mss.id, (SELECT TOP 1 xxz.[id] FROM [BILSANET_MOBILE].[dbo].[Mobil_Settings] xxz  WHERE xxz.database_id = 57 )) as cid
                    FROM  ##okimobilseconddata".$tc."  a
                    LEFT JOIN BILSANET_MOBILE.dbo.[Mobil_Settings] mss ON mss.database_id =a.database_id and mss.configclass is not null
                    WHERE a.RolID in (SELECT distinct zzx.[rolID] FROM [BILSANET_MOBILE].[dbo].[Mobile_MessageRolles] zzx WHERE zzx.[KurumID] ='00000000-0000-0000-0000-000000000000')
                ) as ssdds
                IF OBJECT_ID('tempdb..#okidbname".$tc."') IS NOT NULL DROP TABLE #okidbname".$tc."; 
                IF OBJECT_ID('tempdb..##okimobilfirstdata".$tc."') IS NOT NULL DROP TABLE ##okimobilfirstdata".$tc.";  
                IF OBJECT_ID('tempdb..##okidetaydata".$tc."') IS NOT NULL DROP TABLE ##okidetaydata".$tc."; 
                IF OBJECT_ID('tempdb..##okimobilseconddata".$tc."') IS NOT NULL DROP TABLE ##okimobilseconddata".$tc."; 
                SET NOCOUNT OFF;

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
     * @ login olan userin menusunu dondurur  !!
     * @version v 1.0  27.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function mobilMenu($params = array()) {
        try { 
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
        
            $dbConfigValue = 'pgConnectFactoryMobil';
         
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $RolID = -11;
            if ((isset($params['RolID']) && $params['RolID'] != "")) {
                $RolID = $params['RolID'];
            }    
            $parent=0;
            if ((isset($params['ParentID']) && $params['ParentID'] != "")) {           
                $parent = $params['ParentID'];               
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            }   
            
            $sql = "   
                    SELECT 
                        a.[ID]
                        ,a.[MenuID]
                        ,a.[ParentID],  
                        COALESCE(NULLIF(ax.[MenuAdi],''),a.[MenuAdiEng]) as MenuAdi
                       /* case lx.id  
                            when 647 then a.[MenuAdi] 
                            else COALESCE(isnull(ax.[MenuAdi] ,a.MenuAdiEng) ,a.MenuAdiEng) end as MenuAdi */
                        ,a.[Aciklama]
                        ,a.[URL]
                        ,a.[RolID]
                        ,a.[SubDivision] 
                        ,a.[ImageURL] 
                        ,a.[divid] ,
                        a.iconcolor,
                        a.[iconclass],
                        a.collapse  
                    FROM BILSANET_MOBILE.dbo.[Mobil_Menuleri] a 
                    INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN BILSANET_MOBILE.dbo.[Mobil_Menuleri] ax on (ax.language_parent_id = a.ID or ax.ID = a.ID ) and  ax.language_id= lx.id  
                    WHERE a.active = 0 AND a.deleted = 0 AND 
                        a.RolID = ".intval($RolID)."  AND 
                        a.language_parent_id =0 AND 
                        a.ParentID =0 
                    ORDER BY a.MenuID; 
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
     * @ login olan userin okul bilgileri ve okul id leri   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlKisiOkulListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            
            $sql = "   
                    SELECT DISTINCT  dbo.GNL_Okullar.OkulID, GNL_OKULLAR.OkulAdi   
                    FROM GNL_Kullanicilar 
                    INNER JOIN GNL_OkulKullanicilari ON GNL_Kullanicilar.KisiID = GNL_OkulKullanicilari.KisiID 
                    INNER JOIN GNL_OkulKullaniciRolleri ON GNL_OkulKullanicilari.OkulKullaniciID = GNL_OkulKullaniciRolleri.OkulKullaniciID
                    INNER JOIN GNL_ModulMenuleri ON GNL_OkulKullaniciRolleri.RolID IN (SELECT * FROM dbo.SPLIT(GNL_ModulMenuleri.Roller,','))
                    INNER JOIN GNL_Moduller ON GNL_Moduller.ModulID = GNL_ModulMenuleri.ModulID
                    INNER JOIN dbo.GNL_Okullar ON dbo.GNL_OkulKullanicilari.OkulID = dbo.GNL_Okullar.OkulID
                    WHERE GNL_Kullanicilar.KisiID ='".$params['kisiId']."' 
                    order by GNL_OKULLAR.OkulAdi  
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
     * @ login olan ogretmenin ders programı   !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenDersProgrami($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            } 
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $OkulID = '1CCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }   
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            
            $sql = "  
            set nocount on; 
            
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
            CREATE TABLE #tmpzz ( 
		DersYiliID [uniqueidentifier] ,
		OkulID [uniqueidentifier]  ,
		DonemID  [int] ,
		TedrisatID [int],
		TakdirTesekkurHesapID  [int]    ,
		OnKayitTurID  [int]  ,
                EgitimYilID  [int]  ,
		Donem1BaslangicTarihi [datetime]  ,
		Donem1BitisTarihi [datetime]  ,
		Donem2BaslangicTarihi [datetime]  ,
		Donem2BitisTarihi [datetime]  ,
		Donem1AcikGun [decimal](18, 4)  ,
		Donem2AcikGun [decimal](18, 4)  ,
		YilSonuHesapla [bit] ,
		DevamsizliktanBasarisiz [bit]  ,
		SorumlulukSinavSayisi [tinyint],
		DevamsizlikSabahOgleAyri  [bit] ,
		YilSonuPuanYuvarlansin [bit],
                EgitimYili [varchar](50)  collate SQL_Latin1_General_CP1254_CI_AS,
		OkulDurumPuani [decimal](18, 4),
		YilSonuNotYuvarlansin  [bit],
		YilSonuPuanSinavSonraYuvarlansin  [bit],
		YilSonuNotSinavSonraYuvarlansin  [bit],
		AktifMi [bit]    ); 

            INSERT  INTO #tmpzz
            EXEC ".$dbnamex."[PRC_GNL_DersYili_Find] @OkulID = '".$OkulID."'  
 
            SELECT  
                -1 AS HaftaGunu,
                -1 AS DersSirasi, 
                null AS SinifDersID ,
                null AS DersAdi,
                null AS DersKodu,
                null AS SinifKodu,
                null AS SubeGrupID,
                null AS BaslangicSaati,
                null AS BitisSaati,
                null AS DersBaslangicBitisSaati,
                null AS SinifOgretmenID,
                null AS DersHavuzuID,
                null AS SinifID,
                null AS DersID, 
                null AS Aciklama1,
                COALESCE(NULLIF(ax.[description]  collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng] collate SQL_Latin1_General_CP1254_CI_AS) AS Aciklama,
                null AS DersYiliID,
                null AS DonemID, 
                null AS EgitimYilID   
            FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
            INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
            LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
            WHERE a.[main_group] = 1 and a.[first_group]  = 7 and
                a.language_parent_id =0 
            
            union  

            (SELECT 
                DP.HaftaGunu,
		DP.DersSirasi,
		DP.SinifDersID,
                COALESCE(NULLIF(COALESCE(NULLIF(ax.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,''),ax.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS),''),DRS.DersAdi)  as DersAdi, 
		DH.DersKodu, 
		SNF.SinifKodu,
		SNF.SubeGrupID,
		DS.BaslangicSaati,
		DS.BitisSaati,
		".$dbnamex."GetFormattedTime(BaslangicSaati, 1) + ' - ' + ".$dbnamex."GetFormattedTime(BitisSaati, 1) collate SQL_Latin1_General_CP1254_CI_AS AS DersBaslangicBitisSaati,                    
		SO.SinifOgretmenID,
		DH.DersHavuzuID,
		SNF.SinifID,
		DRS.DersID,
		(CASE WHEN ISNULL(DS.BaslangicSaati,'')<>'' AND ISNULL(DS.BitisSaati,'')<>'' THEN 
				 CAST(DS.DersSirasi AS NVARCHAR(2)) + '. ' + 
				 COALESCE(NULLIF(COALESCE(NULLIF(ax.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,''),ax.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS),''),DRS.DersAdi)  + ' (' + 
				CONVERT(VARCHAR(5),DS.BaslangicSaati,108) + '-' + CONVERT(VARCHAR(5),DS.BitisSaati,108) + ')'
			 ELSE 
				CAST(DP.DersSirasi AS NVARCHAR(2)) + '. ' +  COALESCE(NULLIF(COALESCE(NULLIF(ax.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,''),ax.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS),''),DRS.DersAdi) 
			 END) AS Aciklama1 ,
                         concat(SNF.SinifKodu  collate SQL_Latin1_General_CP1254_CI_AS,' - ',  COALESCE(NULLIF(COALESCE(NULLIF(ax.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,''),ax.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS),''),DRS.DersAdi)  ) as Aciklama,   
			 #tmpzz.DersYiliID,
			 #tmpzz.DonemID,
			 #tmpzz.EgitimYilID
            FROM ".$dbnamex."GNL_DersProgramlari DP
            INNER JOIN ".$dbnamex."GNL_SinifDersleri SD ON  SD.SinifDersID = DP.SinifDersID
            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri SO  ON SO.SinifID = SD.SinifID AND SO.DersHavuzuID = SD.DersHavuzuID 
							AND SO.OgretmenID = '".$kisiId."'
            INNER JOIN ".$dbnamex."GNL_Siniflar SNF ON SD.SinifID = SNF.SinifID  AND SNF.DersYiliID = '".$dersYiliID."'    
            INNER JOIN ".$dbnamex."GNL_DersHavuzlari DH ON SD.DersHavuzuID = DH.DersHavuzuID 
            INNER JOIN ".$dbnamex."GNL_Dersler DRS ON DH.DersID = DRS.DersID
            
            INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
            LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng axx on (axx.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS= DRS.DersAdi collate SQL_Latin1_General_CP1254_CI_AS) and axx.language_id= l.id  
            LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng ax on (ax.DersAdiEng  collate SQL_Latin1_General_CP1254_CI_AS= axx.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS) and ax.language_id= lx.id   
            
            LEFT JOIN ".$dbnamex."GNL_DersSaatleri DS ON DS.DersYiliID = SNF.DersYiliID AND DS.SubeGrupID = SNF.SubeGrupID AND DS.DersSirasi = DP.DersSirasi
            INNER JOIN #tmpzz on #tmpzz.DersYiliID = SNF.DersYiliID and DP.DonemID = #tmpzz.DonemID 
            ) ORDER BY HaftaGunu, BaslangicSaati,DersSirasi, DersAdi ;  
            
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
            SET NOCOUNT OFF;

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
     * @ login olan ogretmenin ders programı   !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenProgramindakiDersler($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            } 
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $OkulID = '1CCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }   
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            }  
            
            $sql = "   
            set nocount on; 
            
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
            CREATE TABLE #tmpzz ( 
		DersYiliID [uniqueidentifier] ,
		OkulID [uniqueidentifier]  ,
		DonemID  [int] ,
		TedrisatID [int],
		TakdirTesekkurHesapID  [int]    ,
		OnKayitTurID  [int]  ,
                EgitimYilID  [int]  ,
		Donem1BaslangicTarihi [datetime]  ,
		Donem1BitisTarihi [datetime]  ,
		Donem2BaslangicTarihi [datetime]  ,
		Donem2BitisTarihi [datetime]  ,
		Donem1AcikGun [decimal](18, 4)  ,
		Donem2AcikGun [decimal](18, 4)  ,
		YilSonuHesapla [bit] ,
		DevamsizliktanBasarisiz [bit]  ,
		SorumlulukSinavSayisi [tinyint],
		DevamsizlikSabahOgleAyri  [bit] ,
		YilSonuPuanYuvarlansin [bit],
                EgitimYili [varchar](50)  collate SQL_Latin1_General_CP1254_CI_AS,
		OkulDurumPuani [decimal](18, 4),
		YilSonuNotYuvarlansin  [bit],
		YilSonuPuanSinavSonraYuvarlansin  [bit],
		YilSonuNotSinavSonraYuvarlansin  [bit],
		AktifMi [bit]    ); 

            INSERT  INTO #tmpzz
            EXEC ".$dbnamex."[PRC_GNL_DersYili_Find] @OkulID = '".$OkulID."'   
 

            SELECT  DISTINCT
                SinifDersID ,
                DersAdi,  
                SinifID, 
                Aciklama
            FROM ( 
                (SELECT   
                    -1 AS DersSirasi, 
                    null AS SinifDersID ,
                    null AS DersAdi,  
                    null AS SinifID, 
                    COALESCE(NULLIF(ax.[description]  collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng] collate SQL_Latin1_General_CP1254_CI_AS) AS Aciklama            
                FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
                INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                WHERE a.[main_group] = 1 and a.[first_group]  = 7 and
                    a.language_parent_id =0 
                )
            union  

                (SELECT DISTINCT  
                    DP.DersSirasi,
                    DP.SinifDersID,
                    COALESCE(NULLIF(COALESCE(NULLIF(ax.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,''),ax.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS),''),DRS.DersAdi)  as DersAdi, 
                    SNF.SinifID, 
                    concat(SNF.SinifKodu  collate SQL_Latin1_General_CP1254_CI_AS,' - ',  COALESCE(NULLIF(COALESCE(NULLIF(ax.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,''),ax.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS),''),DRS.DersAdi)  ) as Aciklama	  
                FROM ".$dbnamex."GNL_DersProgramlari DP
                INNER JOIN ".$dbnamex."GNL_SinifDersleri SD ON  SD.SinifDersID = DP.SinifDersID
                INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri SO  ON SO.SinifID = SD.SinifID AND SO.DersHavuzuID = SD.DersHavuzuID 
                                                            AND SO.OgretmenID = '".$kisiId."'
                INNER JOIN ".$dbnamex."GNL_Siniflar SNF ON SD.SinifID = SNF.SinifID  AND SNF.DersYiliID = '".$dersYiliID."'       
                INNER JOIN ".$dbnamex."GNL_DersHavuzlari DH ON SD.DersHavuzuID = DH.DersHavuzuID 
                INNER JOIN ".$dbnamex."GNL_Dersler DRS ON DH.DersID = DRS.DersID

                INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng axx on (axx.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS= DRS.DersAdi collate SQL_Latin1_General_CP1254_CI_AS) and axx.language_id= l.id  
                LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng ax on (ax.DersAdiEng  collate SQL_Latin1_General_CP1254_CI_AS= axx.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS) and ax.language_id= lx.id   
     
                INNER JOIN #tmpzz on #tmpzz.DersYiliID = SNF.DersYiliID and DP.DonemID = #tmpzz.DonemID 

                )   
            ) as sdasd   
             
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
            SET NOCOUNT OFF;
 
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
     * @ login olan ogretmenin ders saatleri   !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenDersProgramiDersSaatleri($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $sinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['sinifID']) && $params['sinifID'] != "")) {
                $sinifID = $params['sinifID'];
            }   
            $tarih = '1970-01-01';
            if ((isset($params['tarih']) && $params['tarih'] != "")) {
                $tarih = $params['tarih'];
            }   
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "  
            SET NOCOUNT ON;   
            IF OBJECT_ID('tempdb..#ogretmenDersSaatleri') IS NOT NULL DROP TABLE #ogretmenDersSaatleri; 
    
            CREATE TABLE #ogretmenDersSaatleri (
                    BaslangicSaati datetime, 
                    BitisSaati datetime,
                    DersSirasi integer, 
                    DersAdi varchar(100)  collate SQL_Latin1_General_CP1254_CI_AS, 
                    DersKodu varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                    Aciklama varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                    DersID [uniqueidentifier] ,
                    HaftaGunu integer 
                            ) ; 
							 
            INSERT #ogretmenDersSaatleri  exec  ".$dbnamex."PRC_GNL_DersProgrami_Find_forOgretmenDersSaatleri 
                    @OgretmenID='".$kisiId."',
                    @SinifID='".$sinifID."',
                    @Tarih='".$tarih."' ;  
                        
            SELECT     
                null as BaslangicSaati , 
                null as BitisSaati ,
                null as DersSirasi , 
                null as DersAdi , 
                null as DersKodu ,
                COALESCE(NULLIF(ax.[description]  collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS Aciklama,
                null as DersID ,
                -1 as HaftaGunu  
            FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
            LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions] ax on (ax.language_parent_id = a.[id] or  ax.[id] = a.[id]) and ax.language_id= lx.id  
            WHERE a.[main_group] = 1 and a.[first_group] = 8 and 
                a.language_parent_id =0 

            UNION 
 
            SELECT  
                sss.BaslangicSaati , 
                sss.BitisSaati ,
                sss.DersSirasi , 
                sss.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS, 
                sss.DersKodu ,
                sss.Aciklama collate SQL_Latin1_General_CP1254_CI_AS,
                sss.DersID ,
                sss.HaftaGunu 
            FROM #ogretmenDersSaatleri sss;
            IF OBJECT_ID('tempdb..#ogretmenDersSaatleri') IS NOT NULL DROP TABLE #ogretmenDersSaatleri; 
            SET NOCOUNT OFF;  
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
     * @ login olan ogretmenin ders saatlerindeki sınıflardaki ögrenci listesi   !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenDersPrgDersSaatleriOgrencileri($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            /*
            exec dbo.PRC_GNL_OgrenciDevamsizlikSaatleri_Find_SinifDersSaati 
                @SinifID='F4201B97-B073-4DD7-8891-8091C3DC82CF',
                @Tarih='2017-09-29 00:00:00',
                @DersSirasi=1,
                @DersYiliID='fc4675fc-dafb-4af6-a3c2-7acd22622039',
                @OgretmenID='17A68CAA-1A13-460A-BEAA-FB483AC82F7B' 
             
             */ 
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $sinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['sinifID']) && $params['sinifID'] != "")) {
                $sinifID = $params['sinifID'];
            }   
            $tarih = '1970-01-01';
            if ((isset($params['tarih']) && $params['tarih'] != "")) {
                $tarih = $params['tarih'];
            }   
            $dersSirasi = -1;
            if ((isset($params['dersSirasi']) && $params['dersSirasi'] != "")) {
                $dersSirasi = $params['dersSirasi'];
            }   
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            }   
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "  
            SET NOCOUNT ON;   
            IF OBJECT_ID('tempdb..#tmpe') IS NOT NULL DROP TABLE #tmpe; 
            CREATE TABLE #tmpe ( 
				OgrenciID [uniqueidentifier],
				Tarih [datetime],
				DersSirasi  [int],
				DersYiliID [uniqueidentifier],
				Numarasi  [int], 
				Adi [varchar](50) collate SQL_Latin1_General_CP1254_CI_AS,
				Soyadi [varchar](50) collate SQL_Latin1_General_CP1254_CI_AS,  
				TCKimlikNo  [varchar](50) collate SQL_Latin1_General_CP1254_CI_AS, 
				CinsiyetID  [int],
				DevamsizlikKodID [int], 
				Aciklama [varchar](200) collate SQL_Latin1_General_CP1254_CI_AS  
		    );  
		 
                INSERT  INTO #tmpe 
                exec  ".$dbnamex."PRC_GNL_OgrenciDevamsizlikSaatleri_Find_SinifDersSaati 
                    @SinifID='".$sinifID."',
                    @Tarih='".$tarih."' ,
                    @DersSirasi='".$dersSirasi."',
                    @DersYiliID='".$dersYiliID."', 
                    @OgretmenID='".$kisiId."'  ;  
                        

                SELECT 
                    tt.OgrenciID,
                    tt.Tarih,
                    tt.Numarasi  ,   
                    UPPER(concat(tt.Adi collate SQL_Latin1_General_CP1254_CI_AS , ' ', tt.Soyadi collate SQL_Latin1_General_CP1254_CI_AS)) AS adsoyad ,
                    tt.CinsiyetID ,
                    tt.DevamsizlikKodID,
                    tt.Aciklama,
                    tt.DersSirasi,
                    tt.DersYiliID,
                    ff.Fotograf
                FROM #tmpe  tt
                LEFT JOIN  ".$dbnamex."GNL_Fotograflar ff on ff.KisiID =tt.OgrenciID ; 
            IF OBJECT_ID('tempdb..#tmpe') IS NOT NULL DROP TABLE #tmpe; 
            SET NOCOUNT OFF; 
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
     * @ login olan ogretmenin velilerle olan randevu listesi.  !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenVeliRandevulari($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            }  
             
            $sql = "  
            SET NOCOUNT ON;   
                SELECT  
                    vr.VeliRandevuID, 
                    vr.SinifOgretmenID, 
                    vr.VeliID, 
                    vr.BasZamani, 
                    vr.BitZamani, 
                    vr.Aciklama, 
                    vr.Onay, 
                    ogrt.Adi AS Ogretmen_Adi, 
                    ogrt.Soyadi AS Ogretmen_Soyadi, 
                    ogr.Adi AS Ogrenci_Adi, 
                    ogr.Soyadi AS Ogrenci_Soyadi, 
                    Veli.Adi AS Veli_Adi, 
                    Veli.Soyadi AS Veli_Soyadi,
                    d.DersAdi , 
                    '[ ' +COALESCE(NULLIF(golx.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,''),gol.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS) + ' ] ' + ogrt.Adi + ' ' + ogrt.Soyadi As Ders_Ogretmen 
		FROM ".$dbnamex."VLG_VeliRandevu vr
		INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri so ON vr.SinifOgretmenID = so.SinifOgretmenID 
		INNER JOIN ".$dbnamex."GNL_OgrenciYakinlari oy ON vr.VeliID = oy.OgrenciYakinID 
		INNER JOIN ".$dbnamex."GNL_Kisiler AS ogrt ON so.OgretmenID = ogrt.KisiID 
		INNER JOIN ".$dbnamex."GNL_Kisiler AS ogr ON oy.OgrenciID = ogr.KisiID 
		INNER JOIN ".$dbnamex."GNL_Kisiler AS Veli ON oy.YakinID = Veli.KisiID 
		INNER JOIN ".$dbnamex."GNL_DersHavuzlari dh ON so.DersHavuzuID = dh.DersHavuzuID 
		INNER JOIN ".$dbnamex."GNL_Dersler d ON dh.DersID = d.DersID
		LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
		LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng gol ON gol.DersAdi collate SQL_Latin1_General_CP1254_CI_AS = d.DersAdi collate SQL_Latin1_General_CP1254_CI_AS and gol.language_parent_id =0   
                LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng golx ON golx.language_parent_id =  gol.id1 and golx.language_id = lx.id
	  	WHERE ogrt.KisiID = '".$kisiId."'; 

            SET NOCOUNT OFF; 
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
     * @ devamsızlık  kayıt  !!
     * @version v 1.0  05.10.2017
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insertDevamsizlik($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();

            $OgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $DersYiliID = '-2';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $SinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }
            $DersID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersID']) && $params['DersID'] != "")) {
                $DersID = $params['DersID'];
            }
            $SinifDersID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifDersID']) && $params['SinifDersID'] != "")) {
                $SinifDersID = $params['SinifDersID'];
            }
            $DersSirasi = NULL;
            if ((isset($params['DersSirasi']) && $params['DersSirasi'] != "")) {
                $DersSirasi = $params['DersSirasi'];
            } 
            $DonemID = NULL;
            if ((isset($params['DonemID']) && $params['DonemID'] != "")) {
                $DonemID = $params['DonemID'];
            } 
            $OkulOgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulOgretmenID']) && $params['OkulOgretmenID'] != "")) {
                $OkulOgretmenID = $params['OkulOgretmenID'];
            } 
            $Tarih = NULL;
            if ((isset($params['Tarih']) && $params['Tarih'] != "")) {
                $Tarih = $params['Tarih'];
            } 
            $XmlData = ' ';
            $SendXmlData = '';
            $dataValue = NULL;
            $devamsizlikKodID = NULL;
            if ((isset($params['XmlData']) && $params['XmlData'] != "")) {
                $XmlData = $params['XmlData'];
             //    var_dump($XmlData   ); 
                $dataValue =  json_decode($XmlData, true);
               //   print_r( "////////////"); 
              //   var_dump($dataValue[0]['id'] , $dataValue[0]["yok"]  , $dataValue[0]["gec"]); 
             //   var_dump($dataValue   ); 
              // echo($dataValue[0]['id']   ); 
             //     print_r( $dataValue[0]["yok"] ); 
             // print_r( "////////////"); 
           //   $ii =0 ; 
                 // echo( "\\\\\\console\\\\\\"); 
                    foreach ($dataValue as $std) {
                    
                        if ($std  != null) {
  // print_r("<<<<<<<"); 
  //   print_r($std ['id'] );  
  //    print_r(">>>>>>> "); 
    //  $ii +=1;
      $devamsizlikKodID = -1 ; 
   //   print_r($std ['yok']);
                       
                        if ($std ['gec']  == 1) { $devamsizlikKodID = 1 ;}
                        if ($std ['yok']  == 1) { $devamsizlikKodID = 2 ;}
    //   print_r("<<<<<<<"); 
   //  print_r($devamsizlikKodID);  
  //   print_r(">>>>>>> ");            
        //  print_r(htmlentities('<Ogrenci><OgrenciID>').$dataValue[0][0]).htmlentities('</OgrenciID><DevamsizlikKodID>').$dataValue[0][1].htmlentities('</DevamsizlikKodID> ' )  ; 
         //    IF ($devamsizlikKodID >-1)  {  echo( '<Ogrenci><OgrenciID>'.$std ['id'].'</OgrenciID><DevamsizlikKodID>'.$devamsizlikKodID.'</DevamsizlikKodID><Aciklama/></Ogrenci>' ); }
              IF ($devamsizlikKodID >-1)  { $SendXmlData =$SendXmlData.'<Ogrenci><OgrenciID>'.$std ['id'].'</OgrenciID><DevamsizlikKodID>'.$devamsizlikKodID.'</DevamsizlikKodID><Aciklama/></Ogrenci>' ; }
                        }
                    }
                  
               $SendXmlData = '<Table>'.$SendXmlData.'</Table>';
            } 
          // echo($SendXmlData); 
            //  $xml = new SimpleXMLElement('<xml/>'); 
            /*
             * // <Table><Ogrenci><OgrenciID>c6bc540a-1c6e-4ee9-a7f6-3d76eb9027eb</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci><Ogrenci><OgrenciID>4d6ea4f9-8ad9-410e-97f9-930b6b8fe41a</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci><Ogrenci><OgrenciID>c82cc86a-6dde-4213-82a2-812344275720</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci><Ogrenci><OgrenciID>8eae147f-0798-4a77-af17-16972fc10382</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci><Ogrenci><OgrenciID>cf7223bc-4b0c-49c5-bf49-922a4d7f252d</OgrenciID><DevamsizlikKodID>0</DevamsizlikKodID><Aciklama/></Ogrenci></Table>
             $xml = new SimpleXMLElement('<xml/>');
                <tr><td>09:00 - 09:40</td><td>Dersiniz Yok</td><td></td></tr>
                </tbody><tbody><tr><td>09:50 - 10:30</td><td>Dersiniz Yok</td><td></td></tr></tbody><tbody><tr><td>10:40 - 11:20</td><td>Dersiniz Yok</td><td></td></tr></tbody><tbody><tr><td>11:30 - 12:10</td><td>Dersiniz Yok</td><td></td></tr></tbody><tbody><tr><td>12:20 - 13:00</td><td>Dersiniz Yok</td><td></td></tr></tbody><tbody><tr><td>13:50 - 14:30</td><td>Dersiniz Yok</td><td></td></tr></tbody><tbody><tr><td>14:40 - 15:20</td><td>Dersiniz Yok</td><td></td></tr></tbody><tbody><tr><td>15:30 - 16:10</td><td>Dersiniz Yok</td><td></td></tr></tbody><tbody><tr><td>16:20 - 16:40</td><td>Dersiniz Yok</td><td></td></tr>
              
             */
          // echo($SendXmlData); 
              /*
            <Table><Ogrenci><OgrenciID>AEEFE2B7-6653-4776-9343-031155AF6181</OgrenciID><DevamsizlikKodID>2</DevamsizlikKodID><Aciklama/></Ogrenci><Ogrenci><OgrenciID>FA56401D-B693-4292-A726-8784BBB6FF30</OgrenciID><DevamsizlikKodID>2</DevamsizlikKodID><Aciklama/></Ogrenci></Table>
              */
               
              
            $XmlData = $SendXmlData;
         //   print_r($XmlData); 
      //     print_r( '11'); 
            $sql = " 
            declare @XmlD XML;
            set @XmlD = '" . $XmlData . "'  ; 

                exec ".$dbnamex."PRC_GNL_OgrenciDevamsizlikSaatleri_SaveXML 
                    @DersYiliID='" . $DersYiliID . "',
                    @Tarih='" . $Tarih . "', 
                    @DersSirasi=" . intval($DersSirasi) . " ,
                    @XmlData= @XmlD,
                    @SinifDersID='" . $SinifDersID . "' ; 
 ";
            $statement = $pdo->prepare($sql);
         //    print_r( '22'); 
         //     echo debugPDO($sql, $params); 
      //      $result = $statement->execute();
             $insertID =1;
            $errorInfo = $statement->errorInfo(); 
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            
             $sql = " 
                exec ".$dbnamex."PRC_GNL_SaveOgretmenDevamsizlikGirisiLog 
                    @OgretmenID= '" . $OgretmenID . "',
                    @DersYiliID= '" . $DersYiliID . "',
                    @SinifID='" . $SinifID . "',
                    @DersID= '" . $DersID . "',
                    @DersSirasi=" . intval($DersSirasi) . " ; 
                 
  ";
            $statement = $pdo->prepare($sql);
          // echo debugPDO($sql, $params);
       //     print_r( '33'); 
     //       $result = $statement->execute();
             $insertID =1;
            $errorInfo = $statement->errorInfo(); 
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
             $sql = " 
                exec ".$dbnamex."PRC_GNL_OgretmenDevamKontrol_Save 
                    @OgretmenID='" . $OgretmenID . "', 
                    @Tarih='" . $Tarih . "',
                    @DersSirasi=" . intval($DersSirasi) . ",
                    @SinifDersID='" . $SinifDersID . "',
                    @DonemID=" . intval($DersSirasi) . " ; 
 ";
            $statement = $pdo->prepare($sql);
            // echo debugPDO($sql, $params);
       //      print_r( '44'); 
     //       $result = $statement->execute();
             $insertID =1;
            $errorInfo = $statement->errorInfo(); 
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
             $sql = " 
                exec ".$dbnamex."PRC_GNL_SinifDevamsizlikKayitlari_Save 
                    @OkulOgretmenID='" . $OkulOgretmenID . "',
                    @SinifID='" . $SinifID . "',
                    @YoklamaTarihi='" . date("Y-m-d H:i:s") . "',
                    @KayitTarihi='" . date("Y-m-d H:i:s") . "';
 
                    ";
      //        print_r( '55'); 
            $statement = $pdo->prepare($sql);
           // echo debugPDO($sql, $params);
            $result = $statement->execute();
      //      $insertID =1;
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
     * 
     * @author Okan CIRAN
     * @   tablosundan public key i döndürür   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getPK($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
      
            /**
             * @version kapatılmıs olan kısımdaki public key algoritması kullanılmıyor.
             */
            /*      $sql = "          
            SELECT                
                REPLACE(REPLACE(ARMOR(pgp_sym_encrypt(a.sf_private_key_value, 'Bahram Lotfi Sadigh', 'compress-algo=1, cipher-algo=bf'))
	,'-----BEGIN PGP MESSAGE-----

',''),'
-----END PGP MESSAGE-----
','') as public_key1     ,

                substring(ARMOR(pgp_sym_encrypt(a.sf_private_key_value, 'Bahram Lotfi Sadigh', 'compress-algo=1, cipher-algo=bf')),30,length( trim( sf_private_key))-62) as public_key2, 
        */      
            ///crypt(:password, gen_salt('bf', 8)); örnek bf komut
                  $sql = "   
                        
                SELECT       
                     REPLACE(TRIM(SUBSTRING(crypt(sf_private_key_value,gen_salt('xdes')),6,20)),'/','*') AS public_key 
                FROM info_users a              
                INNER JOIN sys_acl_roles sar ON sar.id = a.role_id AND sar.active=0 AND sar.deleted=0 
                WHERE a.username = :username 
                    AND a.password = :password   
                    AND a.deleted = 0 
                    AND a.active = 0 
                
                                 ";

            $statement = $pdo->prepare($sql);
            $statement->bindValue(':username', $params['username'], \PDO::PARAM_STR);
            $statement->bindValue(':password', $params['password'], \PDO::PARAM_STR);
          // echo debugPDO($sql, $parameters);
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
     * @ login olan veli / yakın ın ögrenci listesi   !!
     * @version v 1.0  09.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function veliOgrencileri($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#ogrenciIdBul') IS NOT NULL DROP TABLE #ogrenciIdBul; 
    
            CREATE TABLE #ogrenciIdBul
                (
                        OgrenciID  [uniqueidentifier]  
                ) ;

            INSERT #ogrenciIdBul exec ".$dbnamex."PRC_GNL_OgrenciYakinToOgrenciID_Find @YakinID='".$kisiId."' ; 
            
            SELECT * FROM ( 
                 SELECT 
                    NULL AS OgrenciID,
                    NULL AS SinifID,
                    NULL AS DersYiliID,
                    NULL AS SinifKodu,
                    NULL AS SinifAdi, 
                    NULL AS Numarasi, 
                    NULL AS OgrenciOkulBilgiID,
                    NULL AS KisiID,
                    NULL AS CinsiyetID,
                    NULL AS Adi,
                    NULL AS Soyadi, 
                    COALESCE(NULLIF(ax.[description]  collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS Adi_Soyadi,
                    NULL AS TCKimlikNo,
                    NULL AS ePosta, 
                    NULL AS OkulID,
                    NULL AS OgrenciSeviyeID,
                    NULL AS Fotograf
                    FROM BILSANET_MOBILE.dbo.sys_specific_definitions a 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = a.[id]  or  ax.[id] = a.[id]) and  ax.language_id= lx.id  
                    WHERE a.[main_group] = 1 and a.[first_group] = 5 AND
                        a.language_parent_id =0 
                UNION
                SELECT 
                    GOS.[OgrenciID],
                    SINIF.SinifID,
                    SINIF.DersYiliID,
                    SINIF.SinifKodu,
                    SINIF.SinifAdi, 
                    OOB.[Numarasi], 
                    OOB.OgrenciOkulBilgiID,
                    KISI.[KisiID],
                    KISI.[CinsiyetID],
                    KISI.[Adi],
                    KISI.[Soyadi],
                    KISI.[Adi]  collate SQL_Latin1_General_CP1254_CI_AS+ ' ' + KISI.[Soyadi]  collate SQL_Latin1_General_CP1254_CI_AS AS Adi_Soyadi,
                    KISI.[TCKimlikNo],
                    KISI.[ePosta], 
                    DY.OkulID,
                    GOS.[OgrenciSeviyeID],
                    fo.[Fotograf]		
                FROM   ".$dbnamex."GNL_OgrenciSeviyeleri GOS
                INNER JOIN ".$dbnamex."GNL_Ogrenciler OGR ON (OGR.OgrenciID = GOS.OgrenciID)
                INNER JOIN ".$dbnamex."GNL_Kisiler KISI ON (KISI.KisiID = GOS.OgrenciID)
                INNER JOIN ".$dbnamex."GNL_Siniflar SINIF ON (SINIF.SinifID = GOS.SinifID)
                INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = SINIF.DersYiliID
                INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OgrenciID = OGR.OgrenciID AND OOB.OkulID= DY.OkulID 
                LEFT JOIN ".$dbnamex."GNL_Fotograflar fo on fo.KisiID = GOS.OgrenciID
                WHERE 
                    GOS.OgrenciID in (SELECT distinct OgrenciID FROM #ogrenciIdBul) AND 
                    SINIF.DersYiliID ='".$dersYiliID."'
            ) as assss 
            ORDER BY Numarasi; 
            IF OBJECT_ID('tempdb..#ogrenciIdBul') IS NOT NULL DROP TABLE #ogrenciIdBul; 
            SET NOCOUNT OFF; 
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
     * @ login olan veli / yakın ın ögrenci listesi   !!
     * @version v 1.0  09.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrenciDevamsizlikListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
              
            $sql = "  
            SET NOCOUNT ON;  
             
                SELECT 
                    a.OgrenciDevamsizlikID, 
                    a.DersYiliID,  
                    a.OgrenciID,
                    a.DevamsizlikKodID, 
                    a.DevamsizlikPeriyodID,  
                    a.Tarih, 
                    a.Aciklama, 
                    b.OgrenciseviyeID ,
                    cast(cast(c.OzurluDevamsiz1 AS numeric(10,2)) AS nvarchar(10)) AS OzurluDevamsiz1,
                    cast(cast(c.OzursuzDevamsiz1 AS numeric(10,2)) AS nvarchar(10)) AS OzursuzDevamsiz1,
                    cast(cast(c.OzurluDevamsiz2 AS numeric(10,2)) AS nvarchar(10)) AS OzurluDevamsiz2,
                    cast(cast(c.OzursuzDevamsiz2 AS numeric(10,2)) AS nvarchar(10)) AS OzursuzDevamsiz2 , 
                    ROW_NUMBER() OVER(ORDER BY Tarih) AS rownum  ,
                    concat(cast(a.DevamsizlikKodID AS varchar(2)),' - ', COALESCE(NULLIF(ddx.DevamsizlikAdi,''),ddx.DevamsizlikAdi_eng) collate SQL_Latin1_General_CP1254_CI_AS) AS DevamsizlikAdi,
                    cast(cast(dd.GunKarsiligi AS numeric(10,2)) AS varchar(5)) AS GunKarsiligi
                FROM ".$dbnamex."GNL_OgrenciDevamsizliklari  a 
                inner join ".$dbnamex."GNL_OgrenciSeviyeleri b ON b.OgrenciID = a.OgrenciID
                inner join ".$dbnamex."GNL_OgrenciSeviyeleri c ON c.OgrenciSeviyeID = b.OgrenciSeviyeID
                LEFT JOIN ".$dbnamex."[GNL_DevamsizlikKodlari] dd ON dd.DevamsizlikKodID = a.DevamsizlikKodID
                LEFT JOIN BILSANET_MOBILE.dbo.Mobil_DevamsizlikKodlari_lng ddx ON (ddx.language_parent_id = a.DevamsizlikKodID OR ddx.DevamsizlikKodID = a.DevamsizlikKodID) and 
                                    ddx.language_id = ".$languageIdValue." 
                WHERE 
                    a.DersYiliID = '".$dersYiliID."' AND 
                    a.OgrenciID ='".$kisiId."'; 
 
            SET NOCOUNT OFF; 
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
     * @ login olan kurum yöneticileri için şube listesi   !! notlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kurumyoneticisisubelistesi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
              
            $sql = "  
            SET NOCOUNT ON;    
            SELECT * FROM ( 
                SELECT     
                    null as SinifID,
                    null as DersYiliID,
                    -1 as SeviyeID, 
                    '-1' as SinifKodu,
                    null as SinifAdi,
                    null as Sanal,
                    null as SubeGrupID,
                    null as SeviyeKodu,
                    null as SinifOgretmeni,
                    null as MudurYardimcisi,
                    COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS Aciklama
                    FROM BILSANET_MOBILE.dbo.sys_specific_definitions a 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = a.[id]  or  ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                    WHERE a.[main_group] = 1 and a.[first_group] = 7 AND
                        a.language_parent_id =0 

            UNION  
               
                SELECT 
                    S.SinifID,
                    S.DersYiliID,
                    S.SeviyeID,
                    S.SinifKodu,
                    S.SinifAdi,
                    S.Sanal,
                    S.SubeGrupID,
                    SEV.SeviyeKodu,
                    concat( gks.Adi collate SQL_Latin1_General_CP1254_CI_AS,' ',gks.Soyadi   collate SQL_Latin1_General_CP1254_CI_AS) As SinifOgretmeni,
                    concat(gkm.Adi collate SQL_Latin1_General_CP1254_CI_AS,' ',gkm.Soyadi  collate SQL_Latin1_General_CP1254_CI_AS ) As MudurYardimcisi,
                    concat(S.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,' - ', gks.Adi  collate SQL_Latin1_General_CP1254_CI_AS+' '+gks.Soyadi  collate SQL_Latin1_General_CP1254_CI_AS)  as Aciklama
                FROM ".$dbnamex."GNL_Siniflar S
                INNER JOIN ".$dbnamex."GNL_Seviyeler SEV ON S.SeviyeID = SEV.SeviyeID
                LEFT JOIN ".$dbnamex."GNL_SinifOgretmenleri SO ON (S.SinifID = SO.SinifID AND SO.OgretmenTurID=1)
                LEFT JOIN ".$dbnamex."GNL_SinifOgretmenleri MY ON (S.SinifID = MY.SinifID AND MY.OgretmenTurID=2)
                LEFT JOIN ".$dbnamex."GNL_Kisiler gks on gks.KisiID=SO.OgretmenID 
                LEFT JOIN ".$dbnamex."GNL_Kisiler gkm on gkm.KisiID=MY.OgretmenID
                WHERE S.DersYiliID = '".$DersYiliID."'
                AND S.Sanal < (CASE WHEN 1 = 0 THEN 2 ELSE 1 END)
                 ) as fdsa
                ORDER BY SeviyeID, SinifKodu;
 
            SET NOCOUNT OFF;   
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
     * @ ogretmenin şube listesi   !! notlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmensubelistesi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $ogretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $ogretmenID = $params['OgretmenID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
              
            $sql = "  
            SET NOCOUNT ON;    
            SELECT * FROM ( 
                SELECT     
                    null AS SinifID, 
                    COALESCE(NULLIF(ax.[description]  collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS Aciklama,
                    null AS SeviyeID 
                FROM BILSANET_MOBILE.dbo.sys_specific_definitions a 
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = a.[id] or  ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                WHERE a.[main_group] = 1 and a.[first_group] = 7 AND
                    a.language_parent_id =0  
            UNION   
                SELECT	DISTINCT   
                        ss.SinifID ,
                        ss.SinifKodu collate SQL_Latin1_General_CP1254_CI_AS AS Aciklama ,
                        ss.SeviyeID
                FROM ".$dbnamex."GNL_Siniflar  ss
                INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri so ON ss.SinifID = so.SinifID  
                INNER JOIN ".$dbnamex."GNL_DersHavuzlari dh ON so.DersHavuzuID = dh.DersHavuzuID
                WHERE 
                    so.OgretmenID = '".$ogretmenID."' 
                     AND ss.Sanal = 0  
                 ) AS fdsa
                ORDER BY SeviyeID, Aciklama;
 
            SET NOCOUNT OFF;   
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
     * @ login olan kurum yöneticisinin sectiği subedeki ögrencilistesi  !! notlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kysubeogrencilistesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
           $SinifID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "  
             SET NOCOUNT ON; 
                SELECT 
                    NULL AS OgrenciSeviyeID, 
                    NULL AS OgrenciID, 
                    NULL AS SinifID, 
                    NULL AS OgrenciArsivTurID,  
                    NULL AS OgrenciID,  
                    NULL AS Numarasi,  
                    NULL AS KisiID, 
                    NULL AS CinsiyetID, 
                    NULL AS Adi, 
                    NULL AS Soyadi, 
                    NULL AS TCKimlikNo, 
                    NULL AS ePosta, 
                    NULL AS Yasamiyor, 	
                    NULL AS OdendiMi,  	
                    NULL AS SeviyeID ,
                    NULL AS Fotograf,
                    COALESCE(NULLIF(ax.[description]  collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS Aciklama				 
                    FROM BILSANET_MOBILE.dbo.sys_specific_definitions a 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = a.[id] or  ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                    WHERE a.[main_group] = 1 and a.[first_group] = 5 AND
                        a.language_parent_id =0  

                UNION

                SELECT 
                    GOS.[OgrenciSeviyeID], 
                    GOS.[OgrenciID], 
                    GOS.[SinifID], 
                    GOS.[OgrenciArsivTurID], 
                    OGR.[OgrenciID], 
                    OOB.[Numarasi],
                    KISI.[KisiID], 
                    KISI.[CinsiyetID], 
                    KISI.[Adi], 
                    KISI.[Soyadi], 
                    KISI.[TCKimlikNo], 
                    KISI.[ePosta], 
                    KISI.[Yasamiyor], 	
                    ".$dbnamex."FNC_GNL_AdayKayitUcretOdendiMi(GOS.[OgrenciID],DY.DersYiliID) AS OdendiMi,  	
                    S.[SeviyeID] ,
                    ff.Fotograf,
                    concat(KISI.[Adi]  collate SQL_Latin1_General_CP1254_CI_AS  , ' ', KISI.[Soyadi]  collate SQL_Latin1_General_CP1254_CI_AS ) as Aciklama
                    /* --	GOS.[DavranisNotu1], 
                    --	GOS.[DavranisNotu2], 
                    --	GOS.[DavranisPuani],                     
                    --	GOS.[OzursuzDevamsiz1], 
                    --	GOS.[OzursuzDevamsiz2], 
                    --	GOS.[OzurluDevamsiz1], 
                    --	GOS.[OzurluDevamsiz2], 
                    --	GOS.[YapilanSosyalEtkinlikSaati], 
                    --	GOS.[SosyalEtkinlikTamamlandi], 
                    --	GOS.[KayitYenileme], 
                    --	GOS.[KayitYenilemeAciklamasi], 
                    --	GOS.[YetistirmeKursu], 
                    --	GOS.[YetistirmeKursuAciklamasi], 
                    --	GOS.[Yatili], 
                    --	GOS.[Gunduzlu], 
                    --	GOS.[Parali], 
                    --	GOS.[Yemekli], 
                    --	GOS.[Burslu], 
                    --	GOS.[BursOrani], 
                    --	GOS.[KimlikParasi], 
                    --	GOS.[SeviyedeOkulaKayitli], 
                    -- GOS.[OgrenciArsivTurID], 
                    --	OOB.[YabanciDilID], 
                    --	OOB.[KayitTarihi], 
                    --	OOB.[IkinciYabanciDilID], 
                    */
                    FROM ".$dbnamex."GNL_OgrenciSeviyeleri GOS 
                    INNER JOIN ".$dbnamex."GNL_Siniflar S ON S.SinifID = GOS.SinifID  
                    INNER JOIN ".$dbnamex."GNL_Ogrenciler OGR ON (OGR.OgrenciID = GOS.OgrenciID) 
                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON (DY.DersYiliID = S.DersYiliID)  
                    INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON (OOB.OgrenciID = OGR.OgrenciID AND OOB.OkulID= DY.OkulID)  
                    INNER JOIN ".$dbnamex."GNL_Kisiler KISI ON (KISI.KisiID = GOS.OgrenciID) 
                    LEFT JOIN ".$dbnamex."GNL_Fotograflar ff on ff.KisiID =GOS.OgrenciID
                    WHERE  
                            GOS.SinifID = '".$SinifID."' AND
                            GOS.OgrenciArsivTurID = 1; 

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
    
    /** 
     * @author Okan CIRAN
     * @ login olan kurum yöneticisinin sectiği subedeki ögrencilistesi  !! notlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kySubeOgrenciDersListesi($params = array()) { /// okiii 
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $OgrenciSeviyeID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciSeviyeID']) && $params['OgrenciSeviyeID'] != "")) {
                $OgrenciSeviyeID = $params['OgrenciSeviyeID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "  
            SET NOCOUNT ON; 
            SELECT  
                OgrenciID ,
                OgrenciSeviyeID ,
                DersHavuzuID ,
                Numarasi ,
                Adi ,
                Soyadi ,
                ( Adi + ' ' + Soyadi ) AS AdiSoyadi ,
                DersKodu ,
                DersAdi ,
                DonemID ,
                cast(isnull(Donem1_DonemNotu,0) as varchar(10)) as Donem1_DonemNotu ,
                cast(isnull(Donem2_DonemNotu,0) as varchar(10)) as Donem2_DonemNotu ,
                PuanOrtalamasi ,
                Donem1_PuanOrtalamasi ,
                Donem2_PuanOrtalamasi ,
                Donem1_DonemNotu AS AktifDonemNotu ,
                YetistirmeKursuNotu , 
                cast(isnull(YilSonuNotu,0) as varchar(10)) as YilSonuNotu ,
                cast(isnull(YilSonuPuani,0) as varchar(10)) as YilSonuPuani,
                YilsonuToplamAgirligi , 
                OdevAldi ,
                ProjeAldi ,
                OgrenciDersID ,
                OgrenciDonemNotID ,  
                PuanOrtalamasi ,
                Hesaplandi ,
                KanaatNotu ,
                Sira ,
                EgitimYilID , 
                cast(isnull(HaftalikDersSaati,0) as varchar(10)) as HaftalikDersSaati,
                Perf1OdevAldi ,
                Perf2OdevAldi ,
                Perf3OdevAldi ,
                Perf4OdevAldi ,
                Perf5OdevAldi ,
                AltDers ,
                YillikProjeAldi ,
                YetistirmeKursunaGirecek ,
                concat(DersOgretmenAdi ,' ', DersOgretmenSoyadi) as  OgretmenAdiSoyadi,
                isPuanNotGirilsin ,
                isPuanNotHesapDahil ,
                AgirlikliYilSonuNotu ,
                AgirlikliYilsonuPuani ,
                PBYCOrtalama, 
                DersSabitID 
                
        FROM    ( SELECT    
                    YetistirmeKursuNotu , 
                    cast(YilSonuNotu as numeric(10,2)) as YilSonuNotu,
                    cast(YilSonuPuani as numeric(10,2)) as YilSonuPuani,
                    YilsonuToplamAgirligi ,
                    PuanOrtalamasi ,
                    PuanOrtalamasi AS Donem1_PuanOrtalamasi ,
                    Donem2_PuanOrtalamasi ,
                    Hesaplandi ,
                    ProjeAldi ,
                    SinifID ,
                    ODNB.DersHavuzuID ,
                    ODNB.OgrenciSeviyeID ,
                    ODNB.OgrenciDersID ,
                    OgrenciDonemNotID ,
                    Puan ,
                    SinavTanimID ,
                    cast(Donem1_DonemNotu as numeric(10,2)) as Donem1_DonemNotu,
                    OdevAldi ,
                    KanaatNotu ,
                    cast(Donem2_DonemNotu as numeric(10,2)) as Donem2_DonemNotu,
                    Numarasi ,
                    OgrenciID ,
                    Adi ,
                    Soyadi ,
                    DersKodu ,
                    COALESCE(NULLIF(COALESCE(NULLIF(ax.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,''),ax.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS),''), ODNB.DersAdi) AS DersAdi,
                    DonemID ,
                    Sira ,
                    EgitimYilID , 
                    cast( isnull(HaftalikDersSaati ,0) as numeric(10,2)) as HaftalikDersSaati,
                    Perf1OdevAldi ,
                    Perf2OdevAldi ,
                    Perf3OdevAldi ,
                    Perf4OdevAldi ,
                    Perf5OdevAldi ,
                    AltDers ,
                    ODNB.YillikProjeAldi ,
                    YetistirmeKursunaGirecek ,
                    DersSirasi = ISNULL(( SELECT Sira
                                          FROM ".$dbnamex."GNL_SinifDersleri SD
                                          WHERE SD.SinifID = ODNB.SinifID
                                                 AND SD.DersHavuzuID = ODNB.DersHavuzuID  ), 999) ,
                    DersOgretmenAdi  collate SQL_Latin1_General_CP1254_CI_AS as DersOgretmenAdi,
                    DersOgretmenSoyadi  collate SQL_Latin1_General_CP1254_CI_AS as DersOgretmenSoyadi ,
                    isPuanNotGirilsin ,
                    isPuanNotHesapDahil ,
                    AgirlikliYilSonuNotu ,
                    AgirlikliYilsonuPuani ,
                    PBYCOrtalama, 
                    DersSabitID 		 
                FROM ".$dbnamex."OgrenciDersNotBilgileri_Donem1 ODNB
                LEFT JOIN ".$dbnamex."GNL_OgrenciDersGruplari ODG ON ODG.OgrenciDersID = ODNB.OgrenciDersID
                LEFT JOIN ".$dbnamex."GNL_OgrenciDersGrupTanimlari ODGT ON 
                            ODGT.OgrenciDersGrupTanimID=ODG.OgrenciDersGrupTanimID AND 
                            ODG.OgrenciDersID = ODNB.OgrenciDersID  	
                            
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng axx on (axx.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS = ODNB.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS) and axx.language_id= 647  
                LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng ax on (ax.DersAdiEng  collate SQL_Latin1_General_CP1254_CI_AS = axx.DersAdiEng  collate SQL_Latin1_General_CP1254_CI_AS) and ax.language_id= lx.id  
                WHERE isPuanNotGirilsin = 1 
				                  ) p PIVOT
                                ( MAX(Puan) FOR SinavTanimID IN ( [1], [2], [3], [4], [5], [6], [7], [8],
                                      [9], [10], [11], [12], [13], [14], [15],
                                      [19], [20], [21], [35], [36], [37], [38],
                                      [39], [41], [42], [43], [44], [45] ) ) 
                AS pvt
                WHERE OgrenciSeviyeID = '".$OgrenciSeviyeID."' AND 
                    AltDers = 0   
                SET NOCOUNT OFF; 
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
     * @ login olan ögretmenin sectiği subedeki ögrencilistesi  !! sınavlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmensinavlistesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $OgretmenID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $OkulID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $EgitimYilID =  -1;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            $OkulOgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            $operationId = $this->findByOkulOgretmenID(
                            array( 'OgretmenID' =>$OgretmenID, 'OkulID' => $OkulID,'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($operationId)) {
                $OkulOgretmenID = $operationId ['resultSet'][0]['OkulOgretmenID'];
            }   
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            $grid = 0;
            if (isset($params['Grid']) && $params['Grid'] != "") {
                $grid = $params['Grid'];
            } 
             
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiogrsinavlari') IS NOT NULL DROP TABLE #okiogrsinavlari; 

            CREATE TABLE #okiogrsinavlari
                            (
                            /* OgretmenID [uniqueidentifier], */ 
                            SinavID [uniqueidentifier], 
                            OkulID [uniqueidentifier], 
                            OkulOgretmenID [uniqueidentifier],
                            SinavTurID int,	
                            SeviyeID int,
                            SinavUygulamaSekliID int,
                            KitapcikTurID int,
                            SinavKodu varchar(100)  collate SQL_Latin1_General_CP1254_CI_AS,
                            SinavAciklamasi varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                            SinavTarihi datetime,
                            SinavBitisTarihi datetime, 
                            SinavSuresi int, 
                            KitapcikSayisi int, 
                            DogruSilenYanlisSayisi int, 
                            PuanlarYuvarlansinMi int, 
                            OrtalamaVeSapmaHesaplansinMi int, 
                            SiralamadaYasKontroluYapilsinMi int, 	
                            isDegerlendirildi int,
                            isAlistirma int,
                            OptikFormGirisiYapilabilirMi int,
                            isOtherTeachers int,
                            isUserExam int,
                            isOgrenciVeliSinavVisible int,
                            isAltKurumHidden int,
                            sonBasilabilirOnayTarihi datetime,
                            SinavTurAdi varchar(100)  collate SQL_Latin1_General_CP1254_CI_AS,
                            SeviyeKodu varchar(10)  collate SQL_Latin1_General_CP1254_CI_AS,
                            NotDonemID int,
                            SinavTanimID int, 
                            isNotAktarildi bit 
                                                ) ;

                    INSERT #okiogrsinavlari EXEC ".$dbnamex."[PRC_SNV_Sinavlar_FindForOgretmen]
                                                    @OkulOgretmenID = '".$OkulOgretmenID."',
                                                    @EgitimYilID = ".intval($EgitimYilID).",
                                                    @OkulID = '".$OkulID."',
                                                    @KisiID =  '".$KisiID."' ; 

                    select  
                        null AS Donem  , 
                        null AS SinavTarihi ,
                        null AS SinavBitisTarihi , 
                        null AS SinavTurAdi  ,
                        null AS SinavKodu ,
                        null AS SinavID ,  
                        COALESCE(NULLIF(ax.[description]  collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS SinavAciklamasi				 
                    FROM BILSANET_MOBILE.dbo.sys_specific_definitions a 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =647 AND lx.deleted =0 AND lx.active =0 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = a.[id] or  ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                    WHERE a.[main_group] = 1 and a.[first_group] = 4 AND
                            a.language_parent_id =0 AND 
                            0= ".$grid."

                    UNION 
                    select  
                        gd.[Donem] , 
                        SinavTarihi ,
                        SinavBitisTarihi , 
                        SinavTurAdi  collate SQL_Latin1_General_CP1254_CI_AS  ,
                        SinavKodu  collate SQL_Latin1_General_CP1254_CI_AS ,
                        SinavID ,  
                        SinavAciklamasi  collate SQL_Latin1_General_CP1254_CI_AS  
                    FROM #okiogrsinavlari a 
                    inner join ".$dbnamex."[GNL_Donemler] gd on gd.DonemID = a.NotDonemID 
            IF OBJECT_ID('tempdb..#okiogrsinavlari') IS NOT NULL DROP TABLE #okiogrsinavlari; 
            SET NOCOUNT OFF; 
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
     * @ login olan yakının yada ögrencinin sinav listesi !! sınavlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function yakinisinavlistesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $OgretmenID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $OkulID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $EgitimYilID =  -1;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            
            $OkulOgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            $operationId = $this->findByOkulOgretmenID(
                            array( 'OgretmenID' =>$OgretmenID, 'OkulID' => $OkulID,'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($operationId)) {
                $OkulOgretmenID = $operationId ['resultSet'][0]['OkulOgretmenID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiyakinsinavlari') IS NOT NULL DROP TABLE #okiyakinsinavlari; 

            CREATE TABLE #okiyakinsinavlari
                            ( 
                            SinavID [uniqueidentifier],
                            OkulID [uniqueidentifier], 
                            OkulOgretmenID [uniqueidentifier],
                            SinavTurID int,	
                            SeviyeID int,
                            SinavUygulamaSekliID int,
                            KitapcikTurID int,
                            SinavKodu varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                            SinavAciklamasi varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                            SinavTarihi datetime,
                            SinavBitisTarihi datetime,    
                            SinavSuresi int, 
                            KitapcikSayisi int, 
                            DogruSilenYanlisSayisi int, 
                            PuanlarYuvarlansinMi int, 
                            OrtalamaVeSapmaHesaplansinMi int, 
                            SiralamadaYasKontroluYapilsinMi int, 
                            isDegerlendirildi int,
                            isAlistirma int,
                            OptikFormGirisiYapilabilirMi int,
                            isOtherTeachers int,
                            isUserExam int,
                            isOgrenciVeliSinavVisible int,
                            isAltKurumHidden int,
                            sonBasilabilirOnayTarihi datetime, 
                            SinavTurAdi varchar(100)  collate SQL_Latin1_General_CP1254_CI_AS,
                            SeviyeKodu varchar(10)  collate SQL_Latin1_General_CP1254_CI_AS,
                            NotDonemID int,
                            SinavTanimID int,      
                            isNotAktarildi bit,
                            SinavOgrenciID [uniqueidentifier]
                                                ) ;

                    INSERT #okiyakinsinavlari EXEC  ".$dbnamex."[PRC_SNV_Sinavlar_FindForOgrenci]
                                                    @OkulOgretmenID = '".$OkulOgretmenID."',
                                                    @EgitimYilID = ".intval($EgitimYilID).",
                                                    @OkulID = '".$OkulID."',
                                                    @KisiID =  '".$KisiID."' ; 

                    select  
                        gd.[Donem] , 
                        SinavTarihi ,
                        SinavBitisTarihi , 
                        SinavTurAdi  collate SQL_Latin1_General_CP1254_CI_AS  ,
                        SinavKodu  collate SQL_Latin1_General_CP1254_CI_AS ,
                        SinavAciklamasi   collate SQL_Latin1_General_CP1254_CI_AS 
                    /*
                        SinavTurID ,	
                        SeviyeID ,
                        SinavUygulamaSekliID ,
                        KitapcikTurID ,
                        SinavSuresi , 
                        KitapcikSayisi , 
                        DogruSilenYanlisSayisi , 
                        PuanlarYuvarlansinMi , 
                        OrtalamaVeSapmaHesaplansinMi , 
                        SiralamadaYasKontroluYapilsinMi , 	
                        isDegerlendirildi ,
                        isAlistirma ,
                        OptikFormGirisiYapilabilirMi ,
                        isOtherTeachers ,
                        isUserExam ,
                        isOgrenciVeliSinavVisible ,
                        isAltKurumHidden ,
                        sonBasilabilirOnayTarihi ,
                        SeviyeKodu  ,
                        SinavTanimID , 
                        isNotAktarildi  ,
                        OgretmenID  ,
                        SinavID ,  
                        OkulID , 
                        OkulOgretmenID ,
                        SinavOgrenciID
                    */
                    FROM #okiyakinsinavlari a 
                    INNER JOIN ".$dbnamex."[GNL_Donemler] gd on gd.DonemID = a.NotDonemID ;
            IF OBJECT_ID('tempdb..#okiyakinsinavlari') IS NOT NULL DROP TABLE #okiyakinsinavlari; 
            SET NOCOUNT OFF; 
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
     * @ login olan yakının yada ögrencinin sinav listesi !! sınavlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrencininSinavlistesi($params = array()) { /// okii 
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
          
            $OkulID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }   
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            }  
            
            $sql = "  
                 SET NOCOUNT ON;  
 
                declare  
                    @EgitimYilID int,
                    @OkulID uniqueidentifier,
                    @KisiID UNIQUEIDENTIFIER

                set @OkulID ='".$OkulID."';
                set @KisiID ='".$KisiID."'
  
                IF OBJECT_ID('tempdb..#OgrencininKendiOkulundaGirdigiSinavlar') IS NOT NULL DROP TABLE #OgrencininKendiOkulundaGirdigiSinavlar; 
                IF OBJECT_ID('tempdb..#OgrencininOlcmeDegerlendirmedeGirdigiSinavlar') IS NOT NULL DROP TABLE #OgrencininOlcmeDegerlendirmedeGirdigiSinavlar; 
                 
                set @EgitimYilID =  (SELECT max(EgitimYilID) FROM  ".$dbnamex."GNL_DersYillari dyx  where dyx.OkulID = @OkulID and dyx.AktifMi =1)   ;
                SELECT * FROM (
                    SELECT 
                        /* -- OS.OgrenciID ,SNV.EgitimYilID, */ 
                        SNV.SinavID, 
                        SNV.OkulID, 
                        SNV.OkulOgretmenID,	
                        SNV.SinavTurID,	
                        concat(ST.SinavTurAdi collate SQL_Latin1_General_CP1254_CI_AS, ' ' , SVY.SeviyeKodu collate SQL_Latin1_General_CP1254_CI_AS) as sinavTurTanim,
                        SNV.SeviyeID, 
                        SNV.KitapcikTurID,
                        SNV.SinavKodu,
                        SNV.SinavAciklamasi,
                        SNV.SinavTarihi,
                        SNV.SinavBitisTarihi, 
                        SNV.SinavSuresi, 
                        SOGR.SinavOgrenciID,
                        concat(ogt.Adi  collate SQL_Latin1_General_CP1254_CI_AS,'' ,ogt.Soyadi  collate SQL_Latin1_General_CP1254_CI_AS) as ogretmen
                    
                    FROM ".$dbnamex."SNV_Sinavlar SNV
                    INNER JOIN ".$dbnamex."SNV_SinavTurleri ST ON ST.SinavTurID = SNV.SinavTurID 
                    INNER JOIN ".$dbnamex."GNL_Seviyeler SVY ON SVY.SeviyeID = SNV.SeviyeID 
                    INNER JOIN ".$dbnamex."SNV_SinavSiniflari SSNF ON SSNF.SinavID=SNV.SinavID
                    INNER JOIN ".$dbnamex."SNV_SinavOgrencileri SOGR ON SOGR.SinavSinifID=SSNF.SinavSinifID
                    INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON OS.OgrenciSeviyeID = SOGR.OgrenciSeviyeID AND OS.OgrenciID = @KisiID	
                    LEFT JOIN ".$dbnamex."OGT_OkulOgretmenleri ooo ON ooo.OkulOgretmenID = SNV.OkulOgretmenID
                    LEFT JOIN ".$dbnamex."GNL_Kisiler ogt ON ogt.KisiID = ooo.OgretmenID
                    WHERE 
                        SNV.OkulID = @OkulID AND 
                        SNV.EgitimYilID = @EgitimYilID AND 
                        SNV.isOgrenciVeliSinavVisible  =1  
                    
                    UNION 

                    SELECT  
                        /* OS.OgrenciID ,SNV.EgitimYilID, */ 
                        SNV.SinavID, 
                        SNV.OkulID, 
                        SNV.OkulOgretmenID,	
                        SNV.SinavTurID,	
                        concat(ST.SinavTurAdi collate SQL_Latin1_General_CP1254_CI_AS, ' ' , SVY.SeviyeKodu collate SQL_Latin1_General_CP1254_CI_AS) as sinavTanim,
                        SNV.SeviyeID, 
                        SNV.KitapcikTurID,
                        SNV.SinavKodu,
                        SNV.SinavAciklamasi,
                        SNV.SinavTarihi,
                        SNV.SinavBitisTarihi, 
                        SNV.SinavSuresi, 
                        SOGR.SinavOgrenciID,
                        concat(ogt.Adi collate SQL_Latin1_General_CP1254_CI_AS ,'' ,ogt.Soyadi  collate SQL_Latin1_General_CP1254_CI_AS) as ogretmen
                  
                    FROM ".$dbnamex."SNV_Sinavlar SNV
                    INNER JOIN ".$dbnamex."SNV_SinavOkullari SO ON SO.SinavID = SNV.SinavID	
                    INNER JOIN ".$dbnamex."SNV_SinavTurleri ST ON ST.SinavTurID = SNV.SinavTurID 
                    INNER JOIN ".$dbnamex."GNL_Seviyeler SVY ON SVY.SeviyeID = SNV.SeviyeID 
                    INNER JOIN ".$dbnamex."SNV_SinavSiniflari SSNF ON SSNF.SinavID=SNV.SinavID
                    INNER JOIN ".$dbnamex."SNV_SinavOgrencileri SOGR ON SOGR.SinavSinifID=SSNF.SinavSinifID
                    INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON OS.OgrenciSeviyeID = SOGR.OgrenciSeviyeID AND OS.OgrenciID = @KisiID	
                    LEFT JOIN ".$dbnamex."OGT_OkulOgretmenleri ooo ON ooo.OkulOgretmenID = SNV.OkulOgretmenID
                    LEFT JOIN ".$dbnamex."GNL_Kisiler ogt ON ogt.KisiID = ooo.OgretmenID 
                        WHERE SO.OkulID = @OkulID 
                        AND SNV.EgitimYilID = @EgitimYilID
                        AND SNV.isAltKurumHidden = 0
                        AND SNV.isOgrenciVeliSinavVisible = 1    
 
                ) as ssss  ORDER BY  SinavTarihi DESC  
                
                SET NOCOUNT OFF;  
 
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
     * @ login olan ögretmenin sectiği subedeki ögrencilistesi  !! sınavlar kısmında kullanılıyor
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kurumYoneticisiSinavListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $OgretmenID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $OkulID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $EgitimYilID =  -1;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            
            $OkulOgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            $operationId = $this->findByOkulOgretmenID(
                            array( 'OgretmenID' =>$OgretmenID, 'OkulID' => $OkulID,'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($operationId)) {
                $OkulOgretmenID = $operationId ['resultSet'][0]['OkulOgretmenID'];
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okikysinavlari') IS NOT NULL DROP TABLE #okikysinavlari; 

            CREATE TABLE #okikysinavlari
                            (   
                            SinavID [uniqueidentifier],
                            OkulID [uniqueidentifier], 
                            OkulOgretmenID [uniqueidentifier],
                            SinavTurID int,	
                            SeviyeID int,
                            SinavUygulamaSekliID int,
                            KitapcikTurID int,
                            SinavKodu varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                            SinavAciklamasi varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                            SinavTarihi datetime,
                            SinavBitisTarihi datetime,  
                            SinavSuresi int, 
                            KitapcikSayisi int, 
                            DogruSilenYanlisSayisi int, 
                            PuanlarYuvarlansinMi int, 
                            OrtalamaVeSapmaHesaplansinMi int, 
                            SiralamadaYasKontroluYapilsinMi int, 
                            isDegerlendirildi int,
                            isAlistirma int,
                            OptikFormGirisiYapilabilirMi int,
                            isOtherTeachers int,
                            isUserExam int,
                            isOgrenciVeliSinavVisible int,
                            isAltKurumHidden int,
                            sonBasilabilirOnayTarihi datetime, 
                            SinavTurAdi varchar(100) collate SQL_Latin1_General_CP1254_CI_AS ,
                            SeviyeKodu varchar(10)  collate SQL_Latin1_General_CP1254_CI_AS,
                            NotDonemID int,
                            SinavTanimID int,
                            isNotAktarildi bit,
                            YaziliStsSinavDersiDersHavuzuID [uniqueidentifier]
                            ) ;

                    INSERT #okikysinavlari EXEC ".$dbnamex."[PRC_SNV_Sinavlar_FindForOgrenci]
                                                    @OkulOgretmenID = '".$OkulOgretmenID."',
                                                    @EgitimYilID = ".intval($EgitimYilID).",
                                                    @OkulID = '".$OkulID."',
                                                    @KisiID =  '".$KisiID."' ; 

                    select  
                        gd.[Donem], 
                        SinavTarihi,
                        SinavBitisTarihi, 
                        SinavTurAdi  collate SQL_Latin1_General_CP1254_CI_AS ,
                        SinavKodu collate SQL_Latin1_General_CP1254_CI_AS ,
                        SinavAciklamasi   collate SQL_Latin1_General_CP1254_CI_AS 
                    /*
                        SinavTurID ,	
                        SeviyeID ,
                        SinavUygulamaSekliID ,
                        KitapcikTurID ,
                        SinavSuresi , 
                        KitapcikSayisi , 
                        DogruSilenYanlisSayisi , 
                        PuanlarYuvarlansinMi , 
                        OrtalamaVeSapmaHesaplansinMi , 
                        SiralamadaYasKontroluYapilsinMi , 	
                        isDegerlendirildi ,
                        isAlistirma ,
                        OptikFormGirisiYapilabilirMi ,
                        isOtherTeachers ,
                        isUserExam ,
                        isOgrenciVeliSinavVisible ,
                        isAltKurumHidden ,
                        sonBasilabilirOnayTarihi ,
                        SeviyeKodu  ,
                        SinavTanimID , 
                        isNotAktarildi  ,
                        OgretmenID  ,
                        SinavID ,  
                        OkulID , 
                        OkulOgretmenID ,
                        SinavOgrenciID,
                        YaziliStsSinavDersiDersHavuzuID
                    */
                    FROM #okikysinavlari a 
                    INNER JOIN ".$dbnamex."[GNL_Donemler] gd ON gd.DonemID = a.NotDonemID ;
            IF OBJECT_ID('tempdb..#okikysinavlari') IS NOT NULL DROP TABLE #okikysinavlari; 
            SET NOCOUNT OFF;  
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
     * @ login olan ogretmenin velilerle olan randevu listesi.  !!
     * @version v 1.0  03.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function findByOkulOgretmenID($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            }
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
             $OkulID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }
            $OgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
           
            $sql = "  
            SET NOCOUNT ON;    	 
            IF OBJECT_ID('tempdb..#okiOkulOgretmenID') IS NOT NULL DROP TABLE #okiOkulOgretmenID; 

            CREATE TABLE #okiOkulOgretmenID
                            (
                            OkulOgretmenID [uniqueidentifier],
                            OkulID [uniqueidentifier], 
                            OgretmenID [uniqueidentifier]   ) ;

            INSERT #okiOkulOgretmenID EXEC ".$dbnamex."PRC_OGT_OkulOgretmen_FindByOkulOgretmenID 
                @OkulID= '".$OkulID."',
                @OgretmenID=  '".$OgretmenID."' ; 

            SELECT *,   
            (CASE WHEN (1 = 1) THEN 1 ELSE 0 END)  as control
            FROM #okiOkulOgretmenID ;
            IF OBJECT_ID('tempdb..#okiOkulOgretmenID') IS NOT NULL DROP TABLE #okiOkulOgretmenID; 
            SET NOCOUNT OFF;  
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
     * @ login olan kişinin gelen mesajları... 
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gelenMesajListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $okundu = null;
            $addsql = null ; 
            if ((isset($params['okundu']) && $params['okundu'] != "")) {
                $okundu = $params['okundu'];
                $addsql = " AND MK.Okundu = " .$okundu ;
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            declare @startRowIndex int; 
            declare @maximumRows int ; 
            declare @sortExpression  nvarchar(10) ='' collate SQL_Latin1_General_CP1254_CI_AS; 
         
		SELECT 
			M.MesajID,
			M.KisiID AS ReceiverID,
			MK.Okundu,
			MK.OkunduguTarih,
			M.Silindi,
			M.MesajOncelikID,
			M.Konu collate SQL_Latin1_General_CP1254_CI_AS ,
			M.Mesaj collate SQL_Latin1_General_CP1254_CI_AS ,
			M.Tarih,
			M.KisiID AS SenderID,
			K.Adi  collate SQL_Latin1_General_CP1254_CI_AS AS SenderAdi,
			K.Soyadi  collate SQL_Latin1_General_CP1254_CI_AS AS SenderSoyadi,
			(K.Adi  collate SQL_Latin1_General_CP1254_CI_AS  + ' ' + K.Soyadi collate SQL_Latin1_General_CP1254_CI_AS ) AS SenderAdiSoyadi,
			(CASE WHEN (SELECT COUNT(1) FROM ".$dbnamex."MSJ_MesajEklentileri WHERE MesajID = M.MesajID)>0 THEN 1 ELSE 0 END) AS AttachmentFile,
			ROW_NUMBER() OVER(ORDER BY Tarih DESC) as RowNum 
		FROM ".$dbnamex."MSJ_Mesajlar M 
		INNER JOIN ".$dbnamex."MSJ_MesajKutulari MK ON M.MesajID = MK.MesajID  
		INNER JOIN ".$dbnamex."GNL_Kisiler K ON M.KisiID = K.KisiID 
		WHERE MK.KisiID = '".$KisiID."' 
                ".$addsql."   
                AND M.Silindi=0 
                ORDER BY Tarih DESC;

             
            SET NOCOUNT OFF;  
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
     * @ login olan kişinin gelen mesajları... 
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gidenMesajListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $okundu = null;
            $addsql = null ; 
            if ((isset($params['okundu']) && $params['okundu'] != "")) {
                $okundu = $params['okundu'];
                $addsql = " AND MK.Okundu = " .$okundu ;
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            $sql = "   
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okigidenmesajlar') IS NOT NULL DROP TABLE #okigidenmesajlar; 
  
            CREATE TABLE #okigidenmesajlar
                            (   
                                MesajID uniqueidentifier,
                                MesajOncelikID smallint,
                                Konu nvarchar(max)  collate SQL_Latin1_General_CP1254_CI_AS, 
                                Tarih smalldatetime,
                                SenderID uniqueidentifier,
                                ReceiverIDs nvarchar(max) collate SQL_Latin1_General_CP1254_CI_AS,
                                ReceiverNames nvarchar(max) collate SQL_Latin1_General_CP1254_CI_AS,
                                AttachmentFile bit,
                                RowNum int
                            ) ;

            INSERT #okigidenmesajlar  exec ".$dbnamex."PRC_MSJ_GonderilenMesaj_FindByKisiID
                                                    @KisiID='".$KisiID."',
                                                    @sortExpression=N'',
                                                    @startRowIndex=0,
                                                    @maximumRows=100; 

            SELECT  
                MesajID, 
                MesajOncelikID, 
                Konu collate SQL_Latin1_General_CP1254_CI_AS as Konu, 
                Tarih, 
                SenderID, 
                ReceiverIDs, 
                ReceiverNames collate SQL_Latin1_General_CP1254_CI_AS as ReceiverNames,
                AttachmentFile,
                RowNum 
            FROM #okigidenmesajlar a  
            ORDER BY RowNum;

            IF OBJECT_ID('tempdb..#okigidenmesajlar') IS NOT NULL DROP TABLE #okigidenmesajlar; 
            SET NOCOUNT OFF;  
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
     * @ login olan kişinin mesaj göndermesi  --sadece sistem tipinde mesaj gönderiyor.
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function sendMesajDefault($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();

            $KisiID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $ReceiveKisiID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['ReceiveKisiID']) && $params['ReceiveKisiID'] != "")) {
                $ReceiveKisiID = $params['ReceiveKisiID'];
            }
            $Konu = '-2';
            if ((isset($params['Konu']) && $params['Konu'] != "")) {
                $Konu = $params['Konu'];
            }
            $MesajTipID = '1';
            if ((isset($params['MesajTipID']) && $params['MesajTipID'] != "")) {
                $MesajTipID = $params['MesajTipID'];
            }
            $Mesaj = '';
            if ((isset($params['Mesaj']) && $params['Mesaj'] != "")) {
                $Mesaj = $params['Mesaj'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;   
     
            DECLARE @MesajID1 uniqueidentifier, 
                @MesajIDNewBie uniqueidentifier, 
                @MesajTarihi datetime ,
                @p2 xml ,
                @KisiID1 nvarchar(50) =''  collate SQL_Latin1_General_CP1254_CI_AS, 
                @MesajTipID1 int; 
            set @MesajTarihi = getdate();
            set @MesajIDNewBie = NEWID();  

            SET NOCOUNT ON;   
    
            DECLARE 
                @MesajID uniqueidentifier,  
                @ReceiveKisiID nvarchar(50) =''  collate SQL_Latin1_General_CP1254_CI_AS; 
		 
            set @KisiID1 = '" . $KisiID . "';
            set @ReceiveKisiID = '" . $ReceiveKisiID . "';
            set @MesajTipID1 = " . $MesajTipID . ";
                
            set @MesajID1 = @MesajIDNewBie ;
 
        
            SET NOCOUNT OFF; 
							 
            exec  ".$dbnamex."PRC_MSJ_Mesaj_Save 
                            @MesajID = @MesajIDNewBie OUTPUT,
                            @MesajOncelikID = 1,
                            @Konu= N'" . $Konu . "',
                            @Mesaj=N'" . $Mesaj . "',
                            @Tarih= @MesajTarihi,
                            @KisiID= @KisiID1,
                            @SinavID=NULL,
                            @MesajTipID= @MesajTipID1;   
                            
            exec ".$dbnamex."PRC_MSJ_MesajKutusu_Save @KisiID=@KisiID1,
            @MesajID=@MesajID1 ;  
                                        
            set @p2=convert(xml,N'<Table><MessageBoxes><KisiID>'+@ReceiveKisiID+'</KisiID></MessageBoxes></Table>')
            
            exec ".$dbnamex."PRC_MSJ_MesajKutusu_SaveXML 
                        @MesajID=@MesajIDNewBie,
                        @Data=@p2; 
          
            SET NOCOUNT OFF;    
                ";
            $statement = $pdo->prepare($sql); 
         // echo debugPDO($sql, $params);
            $result = $statement->execute();
            $insertID =1;
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
     * @author Okan CIRAN  -- kullanılmıyor
     * @ login olan kişinin mesaj göndermesi  --sadece sistem tipinde mesaj gönderiyor.
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function sendMesajDefaultMesajKutusuSave($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();

            $KisiID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $MesajID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['MesajID']) && $params['MesajID'] != "")) {
                $MesajID = $params['MesajID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;   
       
			DECLARE 
                            @MesajID uniqueidentifier, 
                            @p2 xml ;  
              
            set @p2=convert(xml,N'<Table><MessageBoxes><KisiID>'".$KisiID."'</KisiID></MessageBoxes></Table>')
 
  						 
            exec ".$dbnamex."PRC_MSJ_MesajKutusu_SaveXML 
                        @MesajID='".$MesajID."',
                        @Data=@p2;    
	 
            SET NOCOUNT OFF;  

                ";
        //    print_r($sql) ; 
            $statement = $pdo->prepare($sql); 
       //    $result = $statement->execute();
             $insertID =1;
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
     * @author Okan CIRAN  -- kullanılmıyor
     * @ login olan kişinin dashboard una data gönderir. rollere göre data değişir.
     * @version v 1.0  11.11.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function dashboardIconCounts($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $RolID = 1; 
            if ((isset($params['RolID']) && $params['RolID'] != "")) {
                $RolID = $params['RolID']; 
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            declare @rolid int, @tc nvarchar(12) =''  collate SQL_Latin1_General_CP1254_CI_AS, @KisiID nvarchar(50) =''  collate SQL_Latin1_General_CP1254_CI_AS,
                @setsql nvarchar(4000) =''  collate SQL_Latin1_General_CP1254_CI_AS,
                @deletesql nvarchar(500) =''  collate SQL_Latin1_General_CP1254_CI_AS,
                @execsql nvarchar(1000) =''  collate SQL_Latin1_General_CP1254_CI_AS,
                @settable nvarchar(50) =''  collate SQL_Latin1_General_CP1254_CI_AS,
                @settable1 nvarchar(50) =''  collate SQL_Latin1_General_CP1254_CI_AS,
                @selecttable1 nvarchar(100) =''  collate SQL_Latin1_General_CP1254_CI_AS ;
            set @rolid = ".$RolID."; 
            set @KisiID = '".$KisiID."';
            select @tc = TCKimlikNo from ".$dbnamex."GNL_Kisiler where KisiID =@KisiID;  
            set @settable = '##dssbrrd'+cast(@tc AS nvarchar(50));  
            set @settable1 = 'tempdb.dbo.'+@settable;   
            set @deletesql = 'DROP TABLE '+ @settable;  
            IF OBJECT_ID(@settable1) IS NOT NULL EXECUTE sp_executesql @deletesql;  
            set @selecttable1 = 'SELECT adet,tip,aciklama,url,pageurl FROM '+@settable;
            SELECT @setsql =  
             CASE 
		WHEN 4= @rolid THEN N' SELECT adet,tip, 
                                            COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''''),axx.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS aciklama, 
                                            ssss.url, ''muhasebe/tahsilatlar.html'' as pageurl    
                                        into '+@settable+' 
                                        FROM (
                                            SELECT ISNULL(SUM(BS.ToplamTutar), 0) AS adet, 2 AS tip, ''Bugünkü Ödemeler'' AS aciklama, ''http://mobile.okulsis.net:8280/okulsis/image/okulsis/time.png'' AS url                                                     
                                            FROM ".$dbnamex."MUH_BorcluSozlesmeleri BS
                                            INNER JOIN ".$dbnamex."MUH_Sozlesmeler SOZ ON SOZ.SozlesmeID = BS.SozlesmeID
                                            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                                            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                                            WHERE cast(BS.TaahhutnameTarihi AS date) = cast(getdate() AS date)
                                        ) AS ssss
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions axx on axx.[main_group] = 2 and axx.[first_group] = 1 and axx.language_parent_id =0 and axx.language_id= 647   
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = axx.[id] or ax.[id] = axx.[id] ) and  ax.language_id= lx.id  
 
                                    ' 
		WHEN 5= @rolid THEN N'  SELECT adet,tip, 
                                            COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''''),axx.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS aciklama, 
                                            ssss.url, ''muhasebe/tahsilatlar.html'' as pageurl  
                                        into '+@settable+'
                                        FROM (
                                            SELECT ISNULL(sum(TaksitTutari), 0) AS adet, 2 AS tip, ''Ödeme Plani'' AS aciklama, ''http://mobile.okulsis.net:8280/okulsis/image/okulsis/msg1.png'' AS url                                                    
                                            FROM ".$dbnamex."MUH_BorcluOdemePlani BOP 
                                            WHERE Odendi =0 AND 
                                            cast(OdemeTarihi AS date) = cast(getdate() AS date) AND 
                                            BOP.BorcluSozlesmeID in (SELECT DISTINCT BS.BorcluSozlesmeID FROM  ".$dbnamex."MUH_BorcluSozlesmeleri BS) 
                                        ) AS ssss
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions axx on axx.[main_group] = 2 and axx.[first_group] = 3 and axx.language_parent_id =0 and axx.language_id= 647   
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = axx.[id] or ax.[id] = axx.[id] ) and  ax.language_id= lx.id  
          
                                    '
		WHEN 6= @rolid THEN N'  SELECT adet,tip, 
                                            COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''''),axx.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS aciklama, 
                                            ssss.url, ''muhasebe/tahsilatlar.html'' as pageurl  
                                        into '+@settable+'
                                        FROM (
                                            SELECT ISNULL(sum(TaksitTutari), 0) AS adet, 2 AS tip, ''Ödeme Plani'' AS aciklama, ''http://mobile.okulsis.net:8280/okulsis/image/okulsis/time.png'' AS url                                                 
                                            FROM ".$dbnamex."MUH_BorcluOdemePlani BOP 
                                            WHERE Odendi =0 AND 
                                                cast(OdemeTarihi AS date) = cast(getdate() AS date) AND 
                                                BOP.BorcluSozlesmeID in (SELECT DISTINCT BS.BorcluSozlesmeID FROM ".$dbnamex."MUH_BorcluSozlesmeleri BS)
                                        ) AS ssss
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions axx on axx.[main_group] = 2 and axx.[first_group] = 3 and axx.language_parent_id =0 and axx.language_id= 647   
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = axx.[id] or ax.[id] = axx.[id] ) and  ax.language_id= lx.id  
         
                                    '
		WHEN 7= @rolid THEN N'  SELECT adet,tip, 
                                            COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''''),axx.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS aciklama, 
                                            ssss.url,'''' as pageurl
                                        into '+@settable+'
                                        FROM (    
                                            SELECT 
                                                ISNULL(count(vr.VeliRandevuID), 0) AS adet, 2 AS tip, ''Randevularınız'' AS aciklama, ''http://mobile.okulsis.net:8280/okulsis/image/okulsis/time.png'' AS url                                             
                                            FROM ".$dbnamex."VLG_VeliRandevu vr
                                            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri so ON vr.SinifOgretmenID = so.SinifOgretmenID 
                                            INNER JOIN ".$dbnamex."GNL_OgrenciYakinlari oy ON vr.VeliID = oy.OgrenciYakinID 
                                            INNER JOIN ".$dbnamex."GNL_Kisiler AS Ogr ON so.OgretmenID = Ogr.KisiID 
                                            INNER JOIN ".$dbnamex."GNL_Kisiler AS Og ON oy.OgrenciID = Og.KisiID 
                                            INNER JOIN ".$dbnamex."GNL_Kisiler AS Veli ON oy.YakinID = Veli.KisiID 
                                            INNER JOIN ".$dbnamex."GNL_DersHavuzlari dh ON so.DersHavuzuID = dh.DersHavuzuID 
                                            INNER JOIN ".$dbnamex."GNL_Dersler dr ON dh.DersID = dr.DersID
                                            WHERE Ogr.KisiID ='''+@KisiID+''' AND 
                                                cast(getdate() AS date) between cast(BasZamani AS date) AND cast(BitZamani AS date)
                                        ) AS ssss
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions axx on axx.[main_group] = 2 and axx.[first_group] = 4 and axx.language_parent_id =0 and axx.language_id= 647   
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = axx.[id] or ax.[id] = axx.[id] ) and  ax.language_id= lx.id  
                                           
                                    ' 
		WHEN 8= @rolid THEN N'  SELECT top 1 adet, 2 AS tip, aciklama, url, pageurl
                                        into '+@settable+'
                                        FROM ( 
                                                SELECT top 1 adet, 2 AS tip, ''http://mobile.okulsis.net:8280/okulsis/image/okulsis/time.png'' AS url ,firstgroup,
                                                    COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''''),axx.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS aciklama
                                              
                                                FROM ( 
                                                    SELECT ISNULL(count(SNV.SinavID), 0) AS adet, 1 AS sira,''Sınavlarınız'' AS aciklama  , 5 as firstgroup ,''sinav/ogrenci_new.html'' as pageurl
                                                    FROM ".$dbnamex."SNV_Sinavlar SNV 
                                                    INNER JOIN ".$dbnamex."SNV_SinavSiniflari SSNF ON SSNF.SinavID=SNV.SinavID
                                                    INNER JOIN ".$dbnamex."SNV_SinavOgrencileri SOGR ON SOGR.SinavSinifID=SSNF.SinavSinifID
                                                    INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON OS.OgrenciSeviyeID = SOGR.OgrenciSeviyeID AND OS.OgrenciID = '''+@KisiID+'''	
                                                    WHERE cast(SNV.SinavTarihi AS date) = cast(getdate() AS date)
                                                UNION  
                                                    SELECT ISNULL(count(OO.OgrenciOdevID), 0) AS adet, 2 AS sira, ''Ödevleriniz'' AS aciklama   , 6 as firstgroup ,''odevler/ogrenci.html'' as pageurl                                                 
                                                    FROM ".$dbnamex."ODV_OgrenciOdevleri OO 
                                                    INNER JOIN ".$dbnamex."ODV_OdevTanimlari OT ON OT.OdevTanimID = OO.OdevTanimID  
                                                    WHERE OO.OgrenciID = '''+@KisiID+''' AND
                                                        OO.OgrenciGordu=0 AND 
                                                        OT.TeslimTarihi <= getdate()  
                                                      ) AS sss  
                                                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                                                LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions axx on axx.[main_group] = 2 and axx.[first_group] = sss.firstgroup and axx.language_parent_id =0 and axx.language_id= 647   
                                                LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = axx.[id] or ax.[id] = axx.[id] ) and  ax.language_id= lx.id  
                                                ORDER BY sira ASC 
                                                ) AS ssss '
		WHEN 9= @rolid THEN N'      declare 
                                            @VeliID uniqueidentifier;  
                                            SELECT @VeliID = OgrenciYakinID FROM ".$dbnamex."GNL_OgrenciYakinlari 
                                            WHERE YakinID = '''+@KisiID+''' ;  
                                            SELECT adet,tip, 
                                            COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''''),axx.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS aciklama, 
                                            ssss.url ,''sinav/yakin_new.html'' as pageurl 
                                            into '+@settable+'
                                            FROM (
                                                SELECT ISNULL(count(vr.VeliRandevuID), 0) as adet, 2 AS tip, ''Randevularınız'' AS aciklama, ''http://mobile.okulsis.net:8280/okulsis/image/okulsis/time.png'' AS url                                                 
                                                FROM ".$dbnamex."VLG_VeliRandevu vr
                                                INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri so ON vr.SinifOgretmenID = so.SinifOgretmenID 
                                                INNER JOIN ".$dbnamex."GNL_OgrenciYakinlari oy ON vr.VeliID = oy.OgrenciYakinID 
                                                INNER JOIN ".$dbnamex."GNL_Kisiler AS ogr ON so.OgretmenID = ogr.KisiID 
                                                INNER JOIN ".$dbnamex."GNL_Kisiler AS og ON oy.OgrenciID = og.KisiID 
                                                INNER JOIN ".$dbnamex."GNL_Kisiler AS Veli ON oy.YakinID = Veli.KisiID
                                                WHERE 
                                                   VeliID = @VeliID AND
                                                   cast(getdate() AS date) between cast(BasZamani AS date) AND cast(BitZamani AS date) 
                                            ) AS ssss
                                            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
                                            LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions axx on axx.[main_group] = 2 and axx.[first_group] = 4 and axx.language_parent_id =0 and axx.language_id= 647   
                                            LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = axx.[id] or ax.[id] = axx.[id] ) and  ax.language_id= lx.id  
   	 
                                            '  
                ELSE N'             
                                    SELECT adet,tip, 
                                            COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''''),axx.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS aciklama, 
                                            ssss.url ,'''' as pageurl 
                                    into '+@settable+'
                                    FROM (
                                            SELECT ISNULL(count(M.MesajID), 0) AS adet, 2 AS tip, ''Aktiviteler'' AS aciklama, ''http://mobile.okulsis.net:8280/okulsis/image/okulsis/time.png'' AS url                                           
                                            FROM ".$dbnamex."MSJ_Mesajlar M 
                                            INNER JOIN ".$dbnamex."MSJ_MesajKutulari MK ON M.MesajID = MK.MesajID 
                                            INNER JOIN ".$dbnamex."GNL_Kisiler K ON M.KisiID = K.KisiID 
                                            WHERE M.Silindi = 0 AND MK.Okundu = 1 AND MK.KisiID = '''+@KisiID+''' 
                                           ) AS ssss
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =647 AND lx.deleted =0 AND lx.active =0 
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions axx on axx.[main_group] = 2 and axx.[first_group] = 7 and axx.language_parent_id =0 and axx.language_id= 647   
                                        LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on (ax.language_parent_id = axx.[id] or ax.[id] = axx.[id] ) and  ax.language_id= lx.id  
     
                                        ' 
		end; 
	 
            EXECUTE sp_executesql @setsql;
            set @execsql = ' 
                SELECT 
                   adet,tip,aciklama,url,pageurl 
                FROM (
                    SELECT 
                        count(M.MesajID) AS adet, 1 AS tip, ''Mesajlarınız'' AS aciklama, ''http://mobile.okulsis.net:8280/okulsis/image/okulsis/msg1.png'' AS url,
                        ''mesajlar/mesaj.html'' as pageurl  
                    FROM ".$dbnamex."MSJ_Mesajlar M 
                    INNER JOIN ".$dbnamex."MSJ_MesajKutulari MK ON M.MesajID = MK.MesajID  
                    INNER JOIN ".$dbnamex."GNL_Kisiler K ON M.KisiID = K.KisiID 
                    WHERE M.Silindi = 0 AND MK.Okundu = 0 AND MK.KisiID = '''+@KisiID+''' 
                    union 
                    '+@selecttable1+' 
            ) AS Parent;
            ';  
            EXECUTE sp_executesql @execsql;  
            IF OBJECT_ID(@settable1) IS NOT NULL EXECUTE sp_executesql @deletesql; 
            SET NOCOUNT OFF;
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
     * @ login olan kişinin sectiği mesajın detayı... 
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gelenMesajDetay($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $MesajID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['MesajID']) && $params['MesajID'] != "")) {
                $MesajID = $params['MesajID'];
            } 
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            
            $sql = "  
            SET NOCOUNT ON;   

            SELECT 
                   MesajID,
                   ReceiverID,
                   Okundu,
                   OkunduguTarih,
                   Silindi,
                   MesajOncelikID,
                   Konu,
                   Mesaj,
                   Tarih,
                   SenderID,
                   SenderAdi,
                   SenderSoyadi,
                   SenderAdiSoyadi,
                   AttachmentFile,
                   RowNum 
            FROM (
		SELECT 
			M.MesajID,
			M.KisiID AS ReceiverID,
			MK.Okundu,
			MK.OkunduguTarih,
			M.Silindi,
			M.MesajOncelikID,
			M.Konu,
			M.Mesaj,
			M.Tarih,
			M.KisiID AS SenderID,
			K.Adi AS SenderAdi,
			K.Soyadi AS SenderSoyadi,
			(K.Adi collate SQL_Latin1_General_CP1254_CI_AS + ' ' + K.Soyadi collate SQL_Latin1_General_CP1254_CI_AS) AS SenderAdiSoyadi,
			(CASE WHEN (SELECT COUNT(1) FROM ".$dbnamex."MSJ_MesajEklentileri WHERE MesajID = M.MesajID)>0 THEN 1 ELSE 0 END) AS AttachmentFile,
			ROW_NUMBER() OVER(ORDER BY Tarih DESC) AS RowNum 
		FROM ".$dbnamex."MSJ_Mesajlar M 
		LEFT JOIN ".$dbnamex."MSJ_MesajKutulari MK ON M.MesajID = MK.MesajID  
		INNER JOIN ".$dbnamex."GNL_Kisiler K ON M.KisiID = K.KisiID 
		WHERE M.MesajID = '".$MesajID."'
            ) AS Parent ; 
            SET NOCOUNT OFF;  
                 "; 
            $statement = $pdo->prepare($sql);   
        // echo debugPDO($sql, $params);
            $statement->execute(); 
            
            $gelenMesajOkunduParams = array('MesajID' =>  $MesajID, 'KisiID'=>  $KisiID, ); 
            $this->gelenMesajOkundu($gelenMesajOkunduParams);  
            
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
    * @ login olan kişinin sectiği mesajın detayına tıklarsa okundu isaretliyecegiz.... 
     * @version v 1.0  21.04.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function gelenMesajOkundu($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();
            
            $MesajID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['MesajID']) && $params['MesajID'] != "")) {
                $MesajID = $params['MesajID'];
            }  
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 

        
            $sql = "
                    SET NOCOUNT ON;   

                    DECLARE 
                        @MesajID1 uniqueidentifier,  
                        @KisiID1 nvarchar(50) =''  collate SQL_Latin1_General_CP1254_CI_AS; 

                    set @KisiID1 = '" . $KisiID . "';
                    set @MesajID1 = '" . $MesajID . "';

                    exec ".$dbnamex."PRC_MSJ_MesajKutusu_Save @KisiID=@KisiID1,
                    @MesajID=@MesajID1 ;  
                    SET NOCOUNT OFF;   
                           ";
            $statement = $pdo->prepare($sql); 
      // echo debugPDO($sql, $params);
            $result = $statement->execute(); 
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]); 

            $pdo->commit();
            return array("found" => true, "errorInfo" => $errorInfo, );
                 
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
   
    /** 
     * @author Okan CIRAN
     * @ ödev listesi... 
     * @version v 1.0  24.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function odevListesiOgretmen($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }   
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
            
            $OgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiozetodevtanimlari') IS NOT NULL DROP TABLE #okiozetodevtanimlari; 

            CREATE TABLE #okiozetodevtanimlari
                        (  
                            OdevTanimID [uniqueidentifier], 
                            OgretmenAdi  varchar(100)  collate SQL_Latin1_General_CP1254_CI_AS,
                            SinifKodu  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                            SeviyeID int,
                            SeviyeAdi  varchar(100) collate SQL_Latin1_General_CP1254_CI_AS, 
                            DersBilgisi  varchar(100) collate SQL_Latin1_General_CP1254_CI_AS, 
                            Tanim  varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                            Aciklama  varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                            Tarih smalldatetime, 
                            TeslimTarihi smalldatetime, 
                            OdevTipID tinyint, 
                            TanimDosyaID [uniqueidentifier], 
                            TanimDosyaAdi   varchar(100) collate SQL_Latin1_General_CP1254_CI_AS, 
                            TanimYuklemeTarihi smalldatetime,  
                            TanimBoyut int ,  
                            TanimDosya image, 
                            VerildigiOgrenciSayisi int, 
                            BakanOgrenciSayisi int ,
                            YapanOgrenciSayisi int,
                            OnayOgrenciSayisi int 
                        ) ;

                    INSERT #okiozetodevtanimlari EXEC ".$dbnamex."[PRC_ODV_OdevTanimlari_FindOzet]
                                                    @OgretmenID =  '".$OgretmenID."',
                                                    @DersYiliID =  '".$DersYiliID."',
                                                    @Tumu =  1;   

                    SELECT  
                        OdevTanimID  , 
                        OgretmenAdi  ,
                        SinifKodu   ,
                        SeviyeID  ,
                        SeviyeAdi   , 
                        DersBilgisi   , 
                        Tanim   ,
                        Aciklama  ,
                        Tarih  , 
                        TeslimTarihi  , 
                        OdevTipID  , 
                        TanimDosyaID  , 
                        TanimDosyaAdi   , 
                        TanimYuklemeTarihi  ,  
                        TanimBoyut   ,  
                        TanimDosya  , 
                        VerildigiOgrenciSayisi  , 
                        BakanOgrenciSayisi   ,
                        YapanOgrenciSayisi  ,
                        OnayOgrenciSayisi   
                    from #okiozetodevtanimlari a ;
            IF OBJECT_ID('tempdb..#okiozetodevtanimlari') IS NOT NULL DROP TABLE #okiozetodevtanimlari;       
            SET NOCOUNT OFF;   
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
     * @ ögrenci ve yakını için ödev listesi... 
     * @version v 1.0  24.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function odevListesiOgrenciveYakin($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
            
            $OgrenciID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciID']) && $params['OgrenciID'] != "")) {
                $OgrenciID = $params['OgrenciID'];
            }
            $EgitimYilID = 1970;
            if ((isset($params['EgitimYilID']) && $params['EgitimYilID'] != "")) {
                $EgitimYilID = $params['EgitimYilID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            
            $sql = "  
            SET NOCOUNT ON;  
            
            SELECT 
                OO.OgrenciOdevID,
                OO.OgrenciID,
                OO.OdevTanimID,
                OO.OgrenciCevap,
                OO.OgrenciGordu,
                OO.OgrenciOnay,
                OO.OgrenciTeslimTarihi,
                OO.OgretmenDegerlendirme,
                OO.OdevOnayID,
                K.Adi  collate SQL_Latin1_General_CP1254_CI_AS  + ' ' + K.Soyadi  collate SQL_Latin1_General_CP1254_CI_AS  AS OgretmenAdi,
                D.DersAdi,
                OT.Tanim,
                OT.Tarih,
                OT.TeslimTarihi,
                OT.Aciklama
            FROM ".$dbnamex."ODV_OgrenciOdevleri OO 
            INNER JOIN ".$dbnamex."ODV_OdevTanimlari OT ON OT.OdevTanimID = OO.OdevTanimID 
            INNER JOIN ".$dbnamex."OGT_Ogretmenler AS OGT ON OGT.OgretmenID = OT.OgretmenID 
            INNER JOIN ".$dbnamex."GNL_Kisiler AS K ON K.KisiID = OGT.OgretmenID 
            INNER JOIN ".$dbnamex."GNL_SinifDersleri AS SD ON SD.SinifDersID = OT.SinifDersID 
            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri AS SO ON SO.SinifID = SD.SinifID AND SO.DersHavuzuID = SD.DersHavuzuID AND OT.OgretmenID = SO.OgretmenID 
            INNER JOIN ".$dbnamex."GNL_Siniflar AS S ON S.SinifID = SD.SinifID 
            INNER JOIN ".$dbnamex."GNL_DersHavuzlari AS DH ON DH.DersHavuzuID = SD.DersHavuzuID 
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = DH.DersYiliID 
            INNER JOIN ".$dbnamex."GNL_Dersler AS D ON D.DersID = DH.DersID 
            WHERE OO.OgrenciID = '".$OgrenciID."' AND DY.EgitimYilID = ".intval($EgitimYilID)."
            ORDER BY Tarih DESC
 
                   
            SET NOCOUNT OFF;   
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
     * @ kurum ödev listesi... 
     * @version v 1.0  24.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function odevListesiKurumYoneticisi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
              
            $sql = "  
            SET NOCOUNT ON;  
            SELECT  
                OT.OdevTanimID ,
                K.Adi  collate SQL_Latin1_General_CP1254_CI_AS  + ' ' + K.Soyadi  collate SQL_Latin1_General_CP1254_CI_AS AS OgretmenAdi ,
                S.SinifKodu ,
                SV.SeviyeAdi ,
                DH.DersKodu  collate SQL_Latin1_General_CP1254_CI_AS + ' (' + D.DersAdi collate SQL_Latin1_General_CP1254_CI_AS  + ')' AS DersBilgisi ,
                OT.Tanim ,
                OT.Tarih ,
                OT.TeslimTarihi
            FROM ".$dbnamex."ODV_OdevTanimlari AS OT
            INNER JOIN ".$dbnamex."GNL_SinifDersleri AS SD ON SD.SinifDersID = OT.SinifDersID
            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri AS SO ON SO.SinifID = SD.SinifID
                                                          AND SO.DersHavuzuID = SD.DersHavuzuID
                                                          AND OT.OgretmenID = SO.OgretmenID
            INNER JOIN ".$dbnamex."GNL_Siniflar AS S ON S.SinifID = SD.SinifID
            INNER JOIN ".$dbnamex."GNL_Seviyeler SV ON S.SeviyeID = SV.SeviyeID
            INNER JOIN ".$dbnamex."GNL_DersHavuzlari AS DH ON DH.DersHavuzuID = SD.DersHavuzuID
            INNER JOIN ".$dbnamex."GNL_Dersler AS D ON D.DersID = DH.DersID
            INNER JOIN ".$dbnamex."OGT_Ogretmenler AS OGT ON OGT.OgretmenID = OT.OgretmenID
            INNER JOIN ".$dbnamex."GNL_Kisiler AS K ON K.KisiID = OGT.OgretmenID
            WHERE
                S.DersYiliID = '".$DersYiliID."'
            ORDER BY TeslimTarihi DESC;  
            SET NOCOUNT OFF;   
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
     * @ öğretmen ders programi listesi... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenDersProgramiListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $OgretmenID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            }
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $DonemID = -1;
            if ((isset($params['DonemID']) && $params['DonemID'] != "")) {
                $DonemID = $params['DonemID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
              
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiogretmendersprogramilistesi') IS NOT NULL DROP TABLE #okiogretmendersprogramilistesi; 

            CREATE TABLE #okiogretmendersprogramilistesi
                    (   
                        HaftaGunu smallint,
                        DersSirasi smallint,
                        SinifDersID [uniqueidentifier],
                        DersAdi varchar(100)  collate SQL_Latin1_General_CP1254_CI_AS, 
                        DersKodu varchar(100) collate SQL_Latin1_General_CP1254_CI_AS, 
                        SinifKodu varchar(100) collate SQL_Latin1_General_CP1254_CI_AS,
                        SubeGrupID int,
                        BaslangicSaati datetime,
                        BitisSaati datetime,
                        DersBaslangicBitisSaati  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                        SinifOgretmenID [uniqueidentifier],
                        DersHavuzuID [uniqueidentifier],
                        SinifID [uniqueidentifier],
                        DersID [uniqueidentifier]  
                    ) ;

            INSERT #okiogretmendersprogramilistesi EXEC ".$dbnamex."[PRC_GNL_DersProgrami_FindByOgretmenIDDersYiliID]
                                            @OgretmenID = '".$OgretmenID."',
                                            @DersYiliID = '".$DersYiliID."', 
                                            @DonemID = ".intval($DonemID)." ; 
 
            SELECT  
                HaftaGunu ,
                DersSirasi ,
                SinifDersID ,
                COALESCE(NULLIF(COALESCE(NULLIF(dx.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS,''),dxx.DersAdiEng  collate SQL_Latin1_General_CP1254_CI_AS),''),a.DersAdi)  AS DersAdi,
                DersKodu, 
                SinifKodu ,
                SubeGrupID ,
                BaslangicSaati ,
                BitisSaati ,
                DersBaslangicBitisSaati,
                SinifOgretmenID ,
                DersHavuzuID ,
                SinifID ,
                DersID  ,
                COALESCE(NULLIF(ax.[description]  collate SQL_Latin1_General_CP1254_CI_AS,''),ax.[description_eng]  collate SQL_Latin1_General_CP1254_CI_AS) AS Aciklama
            
            FROM #okiogretmendersprogramilistesi a  
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
            LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions ax on ax.language_id= lx.id  
                and ax.[main_group] = 3 and ax.[first_group] = a.HaftaGunu and  ax.[first_group]>0 
            LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng dxx on (dxx.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS= a.DersAdi collate SQL_Latin1_General_CP1254_CI_AS) and dxx.language_id= 647
            LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Dersler_lng dx on (dx.DersAdiEng  collate SQL_Latin1_General_CP1254_CI_AS= dxx.DersAdiEng collate SQL_Latin1_General_CP1254_CI_AS) and ax.language_id= lx.id   
                       
                   
            IF OBJECT_ID('tempdb..#okiogretmendersprogramilistesi') IS NOT NULL DROP TABLE #okiogretmendersprogramilistesi; 
            SET NOCOUNT OFF;   
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
     * @ öğrenci, yakını ders programi listesi... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrenciVeYakiniDersProgramiListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
            
            $KisiID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciID']) && $params['OgrenciID'] != "")) {
                $KisiID = $params['OgrenciID'];
            }
            $findOgrenciseviyeIDValue= null ; 
            $findOgrenciseviyeID = $this->findOgrenciseviyeID(
                            array( 'KisiID' =>$KisiID,  'Cid' =>$cid,'Did' =>$did, ));
            if (\Utill\Dal\Helper::haveRecord($findOgrenciseviyeID)) {
                $findOgrenciseviyeIDValue = $findOgrenciseviyeID ['resultSet'][0]['OgrenciseviyeID'];
                $SinifID = $findOgrenciseviyeID ['resultSet'][0]['SinifID'];
            }  
            
            $OgrenciSeviyeID = $findOgrenciseviyeIDValue;
         /*   if ((isset($params['OgrenciSeviyeID']) && $params['OgrenciSeviyeID'] != "")) {
                $OgrenciSeviyeID = $params['OgrenciSeviyeID'];
            }
          *  
          */
           if ($SinifID == "") {
                $SinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
                if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                    $SinifID = $params['SinifID'];
                }
            }
            $DonemID = -1;
            if ((isset($params['DonemID']) && $params['DonemID'] != "")) {
                $DonemID = $params['DonemID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
              
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiogrencidersprogramilistesi') IS NOT NULL DROP TABLE #okiogrencidersprogramilistesi; 
            IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 
            CREATE TABLE #okiogrencidersprogramilistesi
                    (   
                        BaslangicSaati datetime,
                        BitisSaati datetime,
                        DersSaati varchar(20)  collate SQL_Latin1_General_CP1254_CI_AS,
                        DersSirasi smallint,
                        Gun1_SinifDersID  varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun2_SinifDersID  varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun3_SinifDersID  varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun4_SinifDersID  varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun5_SinifDersID  varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun6_SinifDersID  varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun7_SinifDersID  varchar(50) collate SQL_Latin1_General_CP1254_CI_AS 
                    ) ;
                    
            Select distinct sd1.SinifDersID, sd1.DersHavuzuID, sd1.SinifID, dd.DersID,dd.DersAdi
                into #dersller 
            FROM ".$dbnamex."GNL_SinifDersleri sd1    
            LEFT JOIN ".$dbnamex."GNL_Siniflar s1 on s1.SinifID = sd1.SinifID
            LEFT JOIN ".$dbnamex."GNL_DersHavuzlari  dh ON sd1.DersHavuzuID = dh.DersHavuzuID 
            LEFT JOIN ".$dbnamex."GNL_Dersler  dd ON dh.DersID = dd.DersID 

            INSERT #okiogrencidersprogramilistesi EXEC ".$dbnamex."[PRC_GNL_DersProgrami_FindForOgrenci]
                                            @OgrenciSeviyeID = '".$OgrenciSeviyeID."',
                                            @SinifID =   '".$SinifID."', 
                                            @DonemID = ".intval($DonemID)." ; 

            SELECT  
                cast(cast(BaslangicSaati as time) as varchar(5)) as BaslangicSaati,
                cast(cast(BitisSaati as time) as varchar(5)) as BitisSaati,
                DersSaati ,
                DersSirasi,
                isnull(sdz1.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun1_ders, 
                isnull(sdz2.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun2_ders, 
                isnull(sdz3.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun3_ders, 
                isnull(sdz4.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun4_ders, 
                isnull(sdz5.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun5_ders, 
                isnull(sdz6.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun6_ders, 
                isnull(sdz7.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun7_ders 
            FROM #okiogrencidersprogramilistesi a
            LEFT JOIN #dersller sdz1 on sdz1.SinifDersID = a.Gun1_SinifDersID 
            LEFT JOIN #dersller sdz2 on sdz2.SinifDersID = a.Gun2_SinifDersID 
            LEFT JOIN #dersller sdz3 on sdz3.SinifDersID = a.Gun3_SinifDersID 
            LEFT JOIN #dersller sdz4 on sdz4.SinifDersID = a.Gun4_SinifDersID 
            LEFT JOIN #dersller sdz5 on sdz5.SinifDersID = a.Gun5_SinifDersID 
            LEFT JOIN #dersller sdz6 on sdz6.SinifDersID = a.Gun6_SinifDersID 
            LEFT JOIN #dersller sdz7 on sdz7.SinifDersID = a.Gun7_SinifDersID 
                   
            IF OBJECT_ID('tempdb..#okiogrencidersprogramilistesi') IS NOT NULL DROP TABLE #okiogrencidersprogramilistesi; 
            IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 
            SET NOCOUNT OFF; 
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
     * @ sınıf combobox listesi... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kurumPersoneliSinifListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "  
            SET NOCOUNT ON;  
            SELECT * FROM ( 
            SELECT 
                NULL AS SinifID,
                NULL AS SinifKodu,
                'LUTFEN SINIF SEÇİNİZ...!' AS SinifAdi ,
                NULL AS SeviyeID 
            union 
            SELECT 
                SN.SinifID,
                SN.SinifKodu,
                SN.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,
                SeviyeID 
            FROM ".$dbnamex."GNL_Siniflar SN 
            WHERE SN.DersYiliID =  '".$DersYiliID."' AND 
                    Sanal = 0 
                    ) AS DDDD
            ORDER BY SeviyeID ,SinifKodu,SinifAdi;
            SET NOCOUNT OFF; 
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
     * @ kurum Personeli ders programi listesi... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kurumPersoneliDersProgramiListesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
             
            $SinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }
            $DonemID = -1;
            if ((isset($params['DonemID']) && $params['DonemID'] != "")) {
                $DonemID = $params['DonemID'];
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            $sql = "   
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okikpdersprogramilistesi') IS NOT NULL DROP TABLE #okikpdersprogramilistesi; 
            IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 
            CREATE TABLE #okikpdersprogramilistesi
                    (   
                        BaslangicSaati datetime,
                        BitisSaati datetime,
                        DersSaati varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                        DersSirasi smallint,
                        Gun1_SinifDersID  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun2_SinifDersID  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun3_SinifDersID  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun4_SinifDersID  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun5_SinifDersID  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun6_SinifDersID  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                        Gun7_SinifDersID  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS 
                    ) ;
                    
            Select distinct sd1.SinifDersID, sd1.DersHavuzuID, sd1.SinifID, dd.DersID,dd.DersAdi
                into #dersller 
            FROM ".$dbnamex."GNL_SinifDersleri sd1    
            LEFT JOIN ".$dbnamex."GNL_Siniflar s1 on s1.SinifID = sd1.SinifID
            LEFT JOIN ".$dbnamex."GNL_DersHavuzlari  dh ON sd1.DersHavuzuID = dh.DersHavuzuID 
            LEFT JOIN ".$dbnamex."GNL_Dersler  dd ON dh.DersID = dd.DersID 

            INSERT #okikpdersprogramilistesi EXEC ".$dbnamex."[PRC_GNL_DersProgrami_Find] 
                                            @SinifID =  '".$SinifID."', 
                                            @DonemID =  ".intval($DonemID)." ; 

            SELECT  
                BaslangicSaati ,
                BitisSaati ,
                DersSaati ,
                DersSirasi,
                isnull(sdz1.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun1_ders, 
                isnull(sdz2.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun2_ders, 
                isnull(sdz3.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun3_ders, 
                isnull(sdz4.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun4_ders, 
                isnull(sdz5.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun5_ders, 
                isnull(sdz6.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun6_ders, 
                isnull(sdz7.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as Gun7_ders 
            FROM #okikpdersprogramilistesi a
            LEFT JOIN #dersller sdz1 on sdz1.SinifDersID = a.Gun1_SinifDersID 
            LEFT JOIN #dersller sdz2 on sdz2.SinifDersID = a.Gun2_SinifDersID 
            LEFT JOIN #dersller sdz3 on sdz3.SinifDersID = a.Gun3_SinifDersID 
            LEFT JOIN #dersller sdz4 on sdz4.SinifDersID = a.Gun4_SinifDersID 
            LEFT JOIN #dersller sdz5 on sdz5.SinifDersID = a.Gun5_SinifDersID 
            LEFT JOIN #dersller sdz6 on sdz6.SinifDersID = a.Gun6_SinifDersID 
            LEFT JOIN #dersller sdz7 on sdz7.SinifDersID = a.Gun7_SinifDersID 
                   
            IF OBJECT_ID('tempdb..#okikpdersprogramilistesi') IS NOT NULL DROP TABLE #okikpdersprogramilistesi; 
            IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 
            SET NOCOUNT OFF; 
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
     * @ sınıf seviyelerini listeler (combobox)... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function sinifSeviyeleriCombo($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "  
           SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 

            CREATE TABLE #okisinifseviyeleri
                    (    
					SinifID uniqueidentifier,  
					DersYiliID uniqueidentifier,  
					SeviyeID int,  
					SinifKodu varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,  
					SinifAdi varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,  
					SinifMevcudu int ,
					Sanal bit,  
					SubeGrupID int ,  
 					SeviyeAdi  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,  
					HaftalikDersSaati int  
					) ;

            INSERT #okisinifseviyeleri EXEC ".$dbnamex."[PRC_GNL_Sinif_Find_BySeviyeID] 
                                            @DersYiliID =   '".$DersYiliID."',  
                                            @SinifKodu=  '',
                                            @basSeviye = 0 ,
                                            @bitSeviye= 50  ; 

            SELECT  distinct  
                        SeviyeID ,  
                        SeviyeAdi   
            FROM #okisinifseviyeleri   ;
                   
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 
            SET NOCOUNT OFF;  
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
     * @ sınıf seviyelerini listeler... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function sinifSeviyeleri($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $SeviyeID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SeviyeID']) && $params['SeviyeID'] != "")) {
                $SeviyeID = $params['SeviyeID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "  
           SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 

            CREATE TABLE #okisinifseviyeleri
                    (    
					SinifID uniqueidentifier,  
					DersYiliID uniqueidentifier,  
					SeviyeID int,  
					SinifKodu varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,  
					SinifAdi varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,  
					SinifMevcudu int ,
					Sanal bit,  
					SubeGrupID int ,  
 					SeviyeAdi  varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,  
					HaftalikDersSaati int  
					) ;

            INSERT #okisinifseviyeleri EXEC ".$dbnamex."[PRC_GNL_Sinif_Find_BySeviyeID] 
                                            @DersYiliID = '".$DersYiliID."',  
                                            @SinifKodu=  '',
                                            @basSeviye = 0 ,
                                            @bitSeviye= 50  ; 

            SELECT  
                SinifID ,  
                DersYiliID ,  
                SeviyeID ,  
                SinifKodu ,  
                SinifAdi ,  
                SinifMevcudu  ,
                Sanal ,  
                SubeGrupID  ,  
                SeviyeAdi  ,  
                HaftalikDersSaati   
            FROM #okisinifseviyeleri   
            WHERE SeviyeID = ".intval($SeviyeID)." ;
                   
            IF OBJECT_ID('tempdb..#okisinifseviyeleri') IS NOT NULL DROP TABLE #okisinifseviyeleri; 
            SET NOCOUNT OFF; 
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
     * @ profil bilgisini döndürür... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function gnlProfil($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
           
            $sql = "   
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okignlProfil') IS NOT NULL DROP TABLE #okignlProfil; 

            CREATE TABLE #okignlProfil
                    (   
                        KisiID uniqueidentifier,
                        CinsiyetID int,
                        Adi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                        Soyadi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                        AdiSoyadi varchar(80) collate SQL_Latin1_General_CP1254_CI_AS, 
                        TCKimlikNo bigint,
                        ePosta varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                        Yasamiyor bit,
                        EPostaSifresi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                    ) ;

            INSERT #okignlProfil EXEC  ".$dbnamex."PRC_GNL_Kisi_Get @KisiID = '".$KisiID."'; 

            SELECT   
                KisiID ,
                CinsiyetID ,
                Adi,
                Soyadi ,
                AdiSoyadi , 
                TCKimlikNo ,
                ePosta ,
                Yasamiyor ,
                EPostaSifresi 
            FROM #okignlProfil ;   
                   
            IF OBJECT_ID('tempdb..#okignlProfil') IS NOT NULL DROP TABLE #okignlProfil; 
            SET NOCOUNT OFF; 
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
     * @ dashboard   !! --- kullanılmıyor 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function dashboarddata ($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $kisiId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['kisiId']) && $params['kisiId'] != "")) {
                $kisiId = $params['kisiId'];
            }  
            $OkulID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $kisiId = $params['OkulID'];
            }   
            $dersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['dersYiliID']) && $params['dersYiliID'] != "")) {
                $dersYiliID = $params['dersYiliID'];
            }    
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
            set nocount on; 
            
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
            CREATE TABLE #tmpzz (
 
		DersYiliID [uniqueidentifier] ,
		OkulID [uniqueidentifier]  ,
		DonemID  [int] ,
		TedrisatID [int],
		TakdirTesekkurHesapID  [int]    ,
		OnKayitTurID  [int]  ,
                EgitimYilID  [int]  ,
		Donem1BaslangicTarihi [datetime]  ,
		Donem1BitisTarihi [datetime]  ,
		Donem2BaslangicTarihi [datetime]  ,
		Donem2BitisTarihi [datetime]  ,
		Donem1AcikGun [decimal](18, 4)  ,
		Donem2AcikGun [decimal](18, 4)  ,
		YilSonuHesapla [bit] ,
		DevamsizliktanBasarisiz [bit]  ,
		SorumlulukSinavSayisi [tinyint],
		DevamsizlikSabahOgleAyri  [bit] ,
		YilSonuPuanYuvarlansin [bit],
                EgitimYili [varchar](50) collate SQL_Latin1_General_CP1254_CI_AS,
		OkulDurumPuani [decimal](18, 4),
		YilSonuNotYuvarlansin  [bit],
		YilSonuPuanSinavSonraYuvarlansin  [bit],
		YilSonuNotSinavSonraYuvarlansin  [bit],
		AktifMi [bit]    ); 

            INSERT  INTO #tmpzz
            EXEC  ".$dbnamex."[PRC_GNL_DersYili_Find] @OkulID = '".$OkulID."'  
 
            SELECT  
                -1 AS HaftaGunu,
                -1 AS DersSirasi, 
                null AS SinifDersID ,
                null  AS DersAdi,
                null  AS DersKodu,
                null  AS SinifKodu,
                null  AS SubeGrupID,
                null  AS BaslangicSaati,
                null  AS BitisSaati,
                null  AS DersBaslangicBitisSaati,
                null  AS SinifOgretmenID,
                null  AS DersHavuzuID,
                null  AS SinifID,
                null  AS DersID, 
                null  AS Aciklama1,
                'LÜTFEN SEÇİNİZ...' AS Aciklama,
                null  AS DersYiliID,
                null  AS DonemID, 
                null  AS EgitimYilID  

            union  

            (SELECT 
                DP.HaftaGunu,
		DP.DersSirasi,
		DP.SinifDersID,
		DRS.DersAdi, 
		DH.DersKodu, 
		SNF.SinifKodu,
		SNF.SubeGrupID,
		DS.BaslangicSaati,
		DS.BitisSaati,
		dbo.GetFormattedTime(BaslangicSaati, 1) + ' - ' + dbo.GetFormattedTime(BitisSaati, 1) AS DersBaslangicBitisSaati,
		SO.SinifOgretmenID,
		DH.DersHavuzuID,
		SNF.SinifID,
		DRS.DersID,
		(CASE WHEN ISNULL(DS.BaslangicSaati,'')<>'' AND ISNULL(DS.BitisSaati,'')<>'' THEN
				CAST(DS.DersSirasi AS NVARCHAR(2)) + '. ' + 
				DRS.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS+ ' (' + 
				CONVERT(VARCHAR(5),DS.BaslangicSaati,108) + '-' + CONVERT(VARCHAR(5),DS.BitisSaati,108) + ')'
			 ELSE
				CAST(DP.DersSirasi AS NVARCHAR(2)) + '. ' + DRS.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS
			 END) AS Aciklama1 ,
                concat(SNF.SinifKodu collate SQL_Latin1_General_CP1254_CI_AS,' - ', DRS.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS) as Aciklama,   
                #tmp.DersYiliID,
                #tmp.DonemID,
                #tmp.EgitimYilID
            FROM  ".$dbnamex."GNL_DersProgramlari DP
            INNER JOIN  ".$dbnamex."GNL_SinifDersleri SD ON  SD.SinifDersID = DP.SinifDersID
            INNER JOIN  ".$dbnamex."GNL_SinifOgretmenleri SO  ON SO.SinifID = SD.SinifID AND SO.DersHavuzuID = SD.DersHavuzuID 
							AND SO.OgretmenID = '".$kisiId."'
            INNER JOIN  ".$dbnamex."GNL_Siniflar SNF ON SD.SinifID = SNF.SinifID  AND SNF.DersYiliID = '".$dersYiliID."' @DersYiliID  
            INNER JOIN  ".$dbnamex."GNL_DersHavuzlari DH ON SD.DersHavuzuID = DH.DersHavuzuID 
            INNER JOIN  ".$dbnamex."GNL_Dersler DRS ON DH.DersID = DRS.DersID
            LEFT JOIN   ".$dbnamex."GNL_DersSaatleri DS ON DS.DersYiliID = SNF.DersYiliID AND DS.SubeGrupID = SNF.SubeGrupID AND DS.DersSirasi = DP.DersSirasi
            inner join #tmpzz on #tmpzz.DersYiliID = SNF.DersYiliID and DP.DonemID = #tmpzz.DonemID 
            ) ORDER BY HaftaGunu, BaslangicSaati,DersSirasi, DersAdi ;  
            
            IF OBJECT_ID('tempdb..#tmpzz') IS NOT NULL DROP TABLE #tmpzz; 
            SET NOCOUNT OFF;

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
     * @ borclu sozlesmeleri
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function muhBorcluSozlesmeleri($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $OgrenciID= 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciID']) && $params['OgrenciID'] != "")) {
                $OgrenciID = $params['OgrenciID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiborclusozlesmeleri') IS NOT NULL DROP TABLE #okiborclusozlesmeleri; 

            CREATE TABLE #okiborclusozlesmeleri
                    (    
                    OncekiYil int,
                    OdenecekTutarKDVHaric  float,
                    OdenecekKDV float,
                    OdenecekTutarKDVDahil float,
                    Cinsiyet  varchar(10) collate SQL_Latin1_General_CP1254_CI_AS,
                    DogumTarihi date,
                    YakinTuru varchar(20) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcluBanka varchar(20) collate SQL_Latin1_General_CP1254_CI_AS, 
                    OgrenciEvTelefonu varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                    OgrenciCepTelefonu varchar(20) collate SQL_Latin1_General_CP1254_CI_AS ,
                    OgrenciEmail varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcluSozlesmeID  UNIQUEIDENTIFIER,
                    TaahhutnameNo  int,
                    IslemNumarasi bigint,
                    OdemeSekliAciklama varchar(50) collate SQL_Latin1_General_CP1254_CI_AS,
                    TaahhutnameTarihi  datetime,
                    ToplamTutar  float ,
                    Pesinat float,
                    NetTutar float  ,             
                    ToplamOdenen float,
                    KalanTutar float,
                    ToplamIndirim float,
                    ToplamIndirimYuzdesi float ,
                    IndirimliTutar float,
                    PesinatOdemeTarihi datetime,
                    PesinatAlindi  bit,
                    DersYiliID  UNIQUEIDENTIFIER,
                    SozlesmeID  UNIQUEIDENTIFIER,
                    OgrenciID  UNIQUEIDENTIFIER,
                    IndirimOrani float,
                    IndirimID  UNIQUEIDENTIFIER,
                    IndirimOrani2 float,
                    IndirimID2  UNIQUEIDENTIFIER,
                    IndirimOrani3 float,
                    IndirimID3  UNIQUEIDENTIFIER,
                    OdemePlanID  UNIQUEIDENTIFIER,
                    GelecekYil bit,
                    SozlesmeIptalEdildi bit,
                    SozlesmeIptalTarihi smalldatetime,
                    IadeTutari float,
                    OdemeSekliID int,
                    OgrenciYakinID   UNIQUEIDENTIFIER,
                    OkulID  UNIQUEIDENTIFIER,
                    SozlesmelerAciklama varchar(100) collate SQL_Latin1_General_CP1254_CI_AS ,
                    Numarasi bigint,
                    OgrenciAdi varchar(50)  collate SQL_Latin1_General_CP1254_CI_AS,
                    OgrenciSoyadi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS  , 
                    OgrenciTcKimlikNo  bigint,
                    SinifKodu varchar(10) collate SQL_Latin1_General_CP1254_CI_AS ,
                    OkulAdi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcluTcKimlikNo varchar(11) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcluAdi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcluSoyadi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcluEmail varchar(50)  collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcluAdresi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcluCepTelefonu varchar(15) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcluEvTelefonu varchar(15)  collate SQL_Latin1_General_CP1254_CI_AS,
                    BorcluIsTelefonu varchar(15)  collate SQL_Latin1_General_CP1254_CI_AS,
                    BorcluFax varchar(15)  collate SQL_Latin1_General_CP1254_CI_AS,
                    TaksitSayisi int,
                    FaizOrani decimal(18,2),
                    Aktif bit,
                    IndirimTipi varchar(50)  collate SQL_Latin1_General_CP1254_CI_AS,
                    IndirimTipleri varchar(50)  collate SQL_Latin1_General_CP1254_CI_AS,
                    VeliAdi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    VeliSoyadi varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BorcTurID  UNIQUEIDENTIFIER,
                    BorcTuru_Aciklama varchar(50)  collate SQL_Latin1_General_CP1254_CI_AS,
                    OdemePlani_Aciklama varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    OdemePlaniAciklama varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    Indirimler varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    OdemeSekli varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    BankaHesapNo varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                    PesinatFaturaDetayID  UNIQUEIDENTIFIER,
                    VergiDairesi varchar(50)  collate SQL_Latin1_General_CP1254_CI_AS,
                    VergiNo varchar(50)  collate SQL_Latin1_General_CP1254_CI_AS
                    ) ;

            INSERT #okiborclusozlesmeleri exec ".$dbnamex."PRC_MUH_BorcluSozlesmeleri_GetByDinamikIndirim
                                            @Sart=N'BS.OgrenciID =''".$OgrenciID."'' AND BS.DersYiliID=''".$DersYiliID."''',
                                            @Order=N'X.NetTutar DESC'; 

            SELECT  
                OncekiYil,
                OdenecekTutarKDVHaric,
                OdenecekKDV,
                OdenecekTutarKDVDahil,
                Cinsiyet,
                /*DogumTarihi,*/
                YakinTuru ,
                BorcluBanka, 
                /*OgrenciEvTelefonu,
                OgrenciCepTelefonu  ,
                OgrenciEmail  ,*/ 
                BorcluSozlesmeID  , 
                TaahhutnameNo  ,
                IslemNumarasi ,
                OdemeSekliAciklama ,
                cast(TaahhutnameTarihi  as date) as TaahhutnameTarihi,
                ToplamTutar   ,
                Pesinat ,
                NetTutar   ,             
                ToplamOdenen ,
                KalanTutar ,
                ToplamIndirim ,
                ToplamIndirimYuzdesi  ,
                IndirimliTutar ,
                cast(PesinatOdemeTarihi   as date) as PesinatOdemeTarihi,
                PesinatAlindi  ,
                /*DersYiliID  ,
                SozlesmeID  ,*/
                OgrenciID  ,
                IndirimOrani ,
                /*IndirimID  ,*/
                IndirimOrani2 ,
                /*IndirimID2  ,*/
                IndirimOrani3 ,
                /*IndirimID3  ,*/
                /*OdemePlanID  ,*/
                /*GelecekYil ,*/
                /* SozlesmeIptalEdildi ,*/
                cast(SozlesmeIptalTarihi as date) as SozlesmeIptalTarihi ,
                IadeTutari ,
                /*OdemeSekliID ,
                OgrenciYakinID   ,*/
                OkulID  ,
                SozlesmelerAciklama  ,
                Numarasi ,
                concat(OgrenciAdi , ' ', OgrenciSoyadi) as OgrenciAdiSoyadi  , 
                OgrenciTcKimlikNo  ,
                SinifKodu ,
                OkulAdi ,
                BorcluTcKimlikNo ,
                concat(BorcluAdi , ' ' ,BorcluSoyadi) as BorcluAdiSoyadi  ,
                /*BorcluEmail ,
                BorcluAdresi,*/
                BorcluCepTelefonu ,
                /*BorcluEvTelefonu ,
                BorcluIsTelefonu,
                BorcluFax  ,*/
                TaksitSayisi ,
                FaizOrani ,
                /*Aktif ,*/
                IndirimTipi  ,
                IndirimTipleri  ,
                concat(VeliAdi , ' ' ,VeliSoyadi ) as VeliAdiSoyadi,
                /*BorcTurID  ,*/
                BorcTuru_Aciklama  ,
                OdemePlani_Aciklama  ,
                OdemePlaniAciklama  ,
                Indirimler  ,
                OdemeSekli  ,
                BankaHesapNo ,
                /*PesinatFaturaDetayID  ,*/
                VergiDairesi ,
                VergiNo 
            FROM #okiborclusozlesmeleri   
            /*   WHERE SeviyeID = ".intval($OgrenciID)." ;*/
                   
            IF OBJECT_ID('tempdb..#okiborclusozlesmeleri') IS NOT NULL DROP TABLE #okiborclusozlesmeleri; 
            SET NOCOUNT OFF; 
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
     * @ tahsilat bilgisi
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function muhYapilacakTahsilatlarA($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $KurumID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KurumID']) && $params['KurumID'] != "")) {
                $KurumID = $params['KurumID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
          
            
            $sql = "  
            SET NOCOUNT ON;
            declare  @EgitimYilID INT ,
            @KurumID UNIQUEIDENTIFIER;

            set @KurumID='".$KurumID."';
            SELECT  @EgitimYilID = max(EgitimYilID) FROM GNL_DersYillari DY 
            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE  
                KurumID =  @KurumID AND
                AktifMi = 1; 

            DECLARE @Dil NVARCHAR(255)
            SELECT  @Dil = @@language
            SET language turkish ; 

            DECLARE @Bugun SMALLDATETIME = DATEADD(d, DATEDIFF(d, 0, GETDATE()), 0);

            DECLARE @BaslangicAY SMALLDATETIME,
                    @BitisAY SMALLDATETIME , 
                    @BaslangicYil SMALLDATETIME,
                    @BitisYil SMALLDATETIME;

            SET @BaslangicAY = DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)
            SET @BitisAY = DATEADD(MINUTE, -1,
                                   DATEADD(MONTH,
                                           DATEDIFF(MONTH, 0, GETDATE()) + 1, 0))

            SET @BaslangicYil = DATEADD(YEAR, DATEDIFF(YEAR, 0, GETDATE()), 0)
            SET @BitisYil = DATEADD(MINUTE, -1,
                                    DATEADD(YEAR, DATEDIFF(YEAR, 0, GETDATE()) + 1,
                                            0));
  				
            SELECT 'Günlük Toplam Tahsilat' AS Tahsilat ,
                'Bugün Yapılacak' AS Gelecek ,
                'Yapılan Ödemelerden Bugün Yapılanlar' AS TahsilatAciklama ,
                'Ödeme Planında Bugün Yapılması Gerekenler' AS GelecekAciklama ,
                ISNULL(( SELECT SUM(BOP.TaksitTutari)
                         FROM ".$dbnamex."MUH_BorcluOdemePlani BOP
                                INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = BOP.BorcluSozlesmeID
                                INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                                INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                         WHERE BOP.OdemeTarihi = @Bugun
                                AND KurumID = @KurumID
                                AND BOP.OdemeTarihi IS NOT NULL
                                AND Odendi = 0
                                AND EgitimYilID = @EgitimYilID
                       ), 0) AS YapilacakTahsilat ,
                ISNULL(SUM(YO.OdemeTutari), 0) AS YapilanTahsilat ,
                ( SELECT ISNULL(SUM(Pesinat), 0) AS ToplamPesinat
                  FROM ".$dbnamex."MUH_BorcluSozlesmeleri BS
                            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                  WHERE CAST(PesinatOdemeTarihi AS DATE) = @Bugun
                            AND KurumID = @KurumID
                            AND EgitimYilID = @EgitimYilID
                ) AS ToplamPesinat
            FROM ".$dbnamex."MUH_YapilanOdemeler YO
                    INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = YO.BorcluSozlesmeID
                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                    INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE YO.OdemeTarihi = @Bugun
                    AND KurumID = @KurumID
                    AND EgitimYilID = @EgitimYilID

            UNION ALL

            SELECT  'Aylık Toplam Tahsilat' AS Tahsilat ,
                'Bugünden Sonra Bu Ay Yapılacak' AS Gelecek ,
                'Yapılan Ödemelerden Bu Ay Yapılan Ödemeler' AS TahsilatAciklama ,
                'Ödeme Planında Bugünden Sonra Bu Ay Sonuna Kadar Yapılması Gerekenler' AS GelecekAciklama ,
                ISNULL(( SELECT SUM(BOP.TaksitTutari)
                         FROM ".$dbnamex."MUH_BorcluOdemePlani BOP
                                INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = BOP.BorcluSozlesmeID
                                INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                                INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                         WHERE  BOP.OdemeTarihi > @Bugun
                                AND KurumID = @KurumID
                                AND BOP.OdemeTarihi <= @BitisAY
                                AND BOP.OdemeTarihi IS NOT NULL
                                AND Odendi = 0
                                AND EgitimYilID = @EgitimYilID
                       ), 0) AS YapilacakTahsilat ,
                ISNULL(SUM(YO.OdemeTutari), 0) AS YapilanTahsilat ,
                ( SELECT    ISNULL(SUM(Pesinat), 0) AS ToplamPesinat
                  FROM      ".$dbnamex."MUH_BorcluSozlesmeleri BS
                            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                  WHERE     KurumID = @KurumID
                            AND CAST(PesinatOdemeTarihi AS DATE) >= @BaslangicAY
                            AND CAST(PesinatOdemeTarihi AS DATE) <= @BitisAY
                            AND EgitimYilID = @EgitimYilID
                ) AS ToplamPesinat
            FROM ".$dbnamex."MUH_YapilanOdemeler YO
                    INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = YO.BorcluSozlesmeID
                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                    INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE   YO.OdemeTarihi >= @BaslangicAY
                    AND YO.OdemeTarihi <= @BitisAY
                    AND KurumID = @KurumID
                    AND EgitimYilID = @EgitimYilID

            UNION ALL

            SELECT  'Yıllık Toplam Tahsilat' AS Tahsilat ,
                    'Bugünden Sonra Bu Yıl Yapılacak' AS Gelecek ,
                    'Yapılan Ödemelerden Bu Yıl Yapılan Ödemeler' AS TahsilatAciklama ,
                    'Ödeme Planında Bugünden Sonra Bu Yıl Sonuna Kadar Yapılması Gerekenler' AS GelecekAciklama ,
                    ISNULL(( SELECT SUM(BOP.TaksitTutari)
                         FROM ".$dbnamex."MUH_BorcluOdemePlani BOP
                                INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = BOP.BorcluSozlesmeID
                                INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                                INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                         WHERE  BOP.OdemeTarihi > @Bugun
                                AND KurumID = @KurumID
                                AND BOP.OdemeTarihi <= @BitisYil
                                AND BOP.OdemeTarihi IS NOT NULL
                                AND Odendi = 0
                                AND EgitimYilID = @EgitimYilID
                       ), 0) AS YapilacakTahsilat ,
                ISNULL(SUM(YO.OdemeTutari), 0) AS YapilanTahsilat ,
                ( SELECT    ISNULL(SUM(Pesinat), 0) AS ToplamPesinat
                  FROM      ".$dbnamex."MUH_BorcluSozlesmeleri BS
                            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                  WHERE     KurumID = @KurumID
                            AND CAST(PesinatOdemeTarihi AS DATE) >= @BaslangicYil
                            AND CAST(PesinatOdemeTarihi AS DATE) <= @BitisYil
                            AND EgitimYilID = @EgitimYilID
                ) AS ToplamPesinat
            FROM    ".$dbnamex."MUH_YapilanOdemeler YO
                    INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = YO.BorcluSozlesmeID
                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                    INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE   YO.OdemeTarihi >= @BaslangicYil
                    AND YO.OdemeTarihi <= @BitisYil
                    AND KurumID = @KurumID
                    AND EgitimYilID = @EgitimYilID;
            SET NOCOUNT OFF; 
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
     * @ tahsilat özet
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function muhYapilacakTahsilatlarB($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $KurumID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KurumID']) && $params['KurumID'] != "")) {
                $KurumID = $params['KurumID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;
            declare  @EgitimYilID INT ,
            @KurumID UNIQUEIDENTIFIER;

            set @KurumID='".$KurumID."';
            SELECT  @EgitimYilID = max(EgitimYilID) FROM GNL_DersYillari DY 
            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE  
                KurumID =  @KurumID AND
                AktifMi = 1; 

            DECLARE @Dil NVARCHAR(255)
            SELECT  @Dil = @@language
            SET language turkish ; 

            DECLARE @Bugun SMALLDATETIME = DATEADD(d, DATEDIFF(d, 0, GETDATE()), 0);

            DECLARE @BaslangicAY SMALLDATETIME,
                    @BitisAY SMALLDATETIME , 
                    @BaslangicYil SMALLDATETIME,
                    @BitisYil SMALLDATETIME;

            SET @BaslangicAY = DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)
            SET @BitisAY = DATEADD(MINUTE, -1,
                                   DATEADD(MONTH,
                                           DATEDIFF(MONTH, 0, GETDATE()) + 1, 0))

            SET @BaslangicYil = DATEADD(YEAR, DATEDIFF(YEAR, 0, GETDATE()), 0)
            SET @BitisYil = DATEADD(MINUTE, -1,
                                    DATEADD(YEAR, DATEDIFF(YEAR, 0, GETDATE()) + 1,
                                            0));
  				
            SELECT  'Bugün' AS Tahsilat ,
                COUNT(BS.BorcluSozlesmeID) AS Adet ,
                SOZ.Aciklama ,
                ISNULL(SUM(BS.ToplamTutar), 0) AS ToplamTutar
            FROM ".$dbnamex."MUH_BorcluSozlesmeleri BS
            INNER JOIN ".$dbnamex."MUH_Sozlesmeler SOZ ON SOZ.SozlesmeID = BS.SozlesmeID
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE BS.TaahhutnameTarihi = @Bugun
                AND KurumID = @KurumID
                AND DY.EgitimYilID = @EgitimYilID
            GROUP BY SOZ.Aciklama
		 
            UNION ALL
		 
            SELECT DATENAME(month, GETDATE()) AS Tahsilat ,
                COUNT(BS.BorcluSozlesmeID) AS Adet ,
                SOZ.Aciklama ,
                ISNULL(SUM(BS.ToplamTutar), 0) AS ToplamTutar
            FROM ".$dbnamex."MUH_BorcluSozlesmeleri BS
            INNER JOIN ".$dbnamex."MUH_Sozlesmeler SOZ ON SOZ.SozlesmeID = BS.SozlesmeID
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE BS.TaahhutnameTarihi >= @BaslangicAY
                AND BS.TaahhutnameTarihi <= @BitisAY
                AND KurumID = @KurumID
                AND DY.EgitimYilID = @EgitimYilID
            GROUP BY SOZ.Aciklama
		 
            UNION ALL
		 
            SELECT 'Bu Yıl' AS Tahsilat ,
                COUNT(BS.BorcluSozlesmeID) AS Adet ,
                SOZ.Aciklama ,
                ISNULL(SUM(BS.ToplamTutar), 0) AS ToplamTutar
            FROM ".$dbnamex."MUH_BorcluSozlesmeleri BS
            INNER JOIN ".$dbnamex."MUH_Sozlesmeler SOZ ON SOZ.SozlesmeID = BS.SozlesmeID
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE BS.TaahhutnameTarihi >= @BaslangicYil
                AND BS.TaahhutnameTarihi <= @BitisYil
                AND KurumID = @KurumID
                AND DY.EgitimYilID = @EgitimYilID
            GROUP BY SOZ.Aciklama ; 
 
            SET NOCOUNT OFF; 
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
     * @ tahsil edilemeyenler
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function muhYapilacakTahsilatlarC($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $KurumID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KurumID']) && $params['KurumID'] != "")) {
                $KurumID = $params['KurumID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;
            declare  @EgitimYilID INT ,
            @KurumID UNIQUEIDENTIFIER;

            set @KurumID='".$KurumID."';
            SELECT  @EgitimYilID = max(EgitimYilID) FROM GNL_DersYillari DY 
            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE  
                KurumID =  @KurumID AND
                AktifMi = 1; 

            DECLARE @Dil NVARCHAR(255)
            SELECT  @Dil = @@language
            SET language turkish ; 

            DECLARE @Bugun SMALLDATETIME = DATEADD(d, DATEDIFF(d, 0, GETDATE()), 0);

            DECLARE @BaslangicAY SMALLDATETIME,
                    @BitisAY SMALLDATETIME , 
                    @BaslangicYil SMALLDATETIME,
                    @BitisYil SMALLDATETIME;

            SET @BaslangicAY = DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)
            SET @BitisAY = DATEADD(MINUTE, -1,
                                   DATEADD(MONTH,
                                           DATEDIFF(MONTH, 0, GETDATE()) + 1, 0))

            SET @BaslangicYil = DATEADD(YEAR, DATEDIFF(YEAR, 0, GETDATE()), 0)
            SET @BitisYil = DATEADD(MINUTE, -1,
                                    DATEADD(YEAR, DATEDIFF(YEAR, 0, GETDATE()) + 1,
                                            0)); 
            SELECT distinct SOZ.Aciklama ,
                BOP.TaksitTutari ,
                BOP.TaksitNo ,
                K.TCKimlikNo ,
                K.Adi + ' ' + K.Soyadi AS OgrenciAdi
            FROM  ".$dbnamex."MUH_BorcluSozlesmeleri BS
            INNER JOIN ".$dbnamex."MUH_Sozlesmeler SOZ ON SOZ.SozlesmeID = BS.SozlesmeID
            INNER JOIN ".$dbnamex."MUH_BorcluOdemePlani BOP ON BOP.BorcluSozlesmeID = BS.BorcluSozlesmeID
            INNER JOIN ".$dbnamex."MUH_OdemePlanlari OP ON OP.OdemePlanID = BS.OdemePlanID
            INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = BS.OgrenciID
            INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON OS.OgrenciID = BS.OgrenciID
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE BOP.OdemeTarihi IS NOT NULL
                AND BOP.OdemeTarihi = @Bugun
                AND BOP.Iptal = 0
                AND BOP.Odendi = 0
                AND KurumID = @KurumID
                AND DY.EgitimYilID = @EgitimYilID;
            SET language @Dil  ;
 
            SET NOCOUNT OFF; 
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
     * @ borclu odeme planı 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function muhBorcluOdemePlani($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $BorcluSozlesmeID= 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['BorcluSozlesmeID']) && $params['BorcluSozlesmeID'] != "")) {
                $BorcluSozlesmeID = $params['BorcluSozlesmeID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
           
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okiborcluodemeplani') IS NOT NULL DROP TABLE #okiborcluodemeplani; 

            CREATE TABLE #okiborcluodemeplani
                    (    
                        BorcluOdemePlaniID UNIQUEIDENTIFIER,
                        BorcluSozlesmeID UNIQUEIDENTIFIER,
                        OdemeTarihi datetime,
                        TaksitNo int ,
                        TaksitTutari float,
                        Odendi bit,
                        OdemeAciklamasi varchar(20) collate SQL_Latin1_General_CP1254_CI_AS  ,
                        Iptal bit ,
                        FaturaDetayID UNIQUEIDENTIFIER,
                        OdenenTutar float,
                        FaturaTarihi datetime ,
                        FaturaSeri varchar(20) collate SQL_Latin1_General_CP1254_CI_AS ,
                        FaturaNo int,
                        TaksitTutariYedek float,
                        KDVOrani decimal (18,2),
                        Aciklama varchar(50) collate SQL_Latin1_General_CP1254_CI_AS ,
                        OdemeSekli varchar(20)  collate SQL_Latin1_General_CP1254_CI_AS,
                        Odeme_Aciklamasi varchar(20)  collate SQL_Latin1_General_CP1254_CI_AS 
                    ) ;

            INSERT #okiborcluodemeplani exec PRC_MUH_BorcluOdemePlani_Find_ByBorcluSozlesmeID
							 @BorcluSozlesmeID='".$BorcluSozlesmeID."'; 

            SELECT  
                    BorcluOdemePlaniID  ,
                    BorcluSozlesmeID  ,
                    OdemeTarihi  ,
                    TaksitNo   ,
                    TaksitTutari  ,
                    Odendi  ,
                    case Odendi when 0 then 'Ödenmedi' else 'Ödendi' end as Odendi_aciklama,
                    OdemeAciklamasi   ,
                    Iptal  ,
                    FaturaDetayID ,
                    OdenenTutar ,
                    FaturaTarihi   ,
                    FaturaSeri   ,
                    FaturaNo  ,
                    TaksitTutariYedek  ,
                    KDVOrani  ,
                    Aciklama   ,
                    OdemeSekli    
            FROM #okiborcluodemeplani ;   
                   
            IF OBJECT_ID('tempdb..#okiborcluodemeplani') IS NOT NULL DROP TABLE #okiborcluodemeplani; 
            SET NOCOUNT OFF; 
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
     * @ sınav ögrencilerini listeler... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function sinavOgrencileri($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $SinavID= 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavID']) && $params['SinavID'] != "")) {
                $BorcluSozlesmeID = $params['SinavID'];
            } 
            $OgrenciID= 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciID']) && $params['OgrenciID'] != "")) {
                $BorcluSozlesmeID = $params['OgrenciID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            IF OBJECT_ID('tempdb..#okisinavogrencileri') IS NOT NULL DROP TABLE #okisinavogrencileri; 

            CREATE TABLE #okisinavogrencileri
                    (    
                        SinavOgrenciID UNIQUEIDENTIFIER,
                        SinavSinifID UNIQUEIDENTIFIER,
                        SinavKitapcikID UNIQUEIDENTIFIER,
                        SinavOkulID UNIQUEIDENTIFIER,
                        SinavaGirecegiOkulID UNIQUEIDENTIFIER,
                        OgrenciNumarasi bigint, 
                        MekanYeriSiraNo int,
                        SinifKodu varchar(20) collate SQL_Latin1_General_CP1254_CI_AS , 
                        Girmedi bit, 
                        OgrenciSeviyeID  UNIQUEIDENTIFIER					 
                    ) ;

            INSERT #okisinavogrencileri exec PRC_SNV_SinavOgrencileri_Find_BySinavIDOgrenciID
									@SinavID='".$SinavID."',
									@OgrenciID='".$OgrenciID."'

            SELECT  
                SinavOgrenciID ,
                SinavSinifID ,
                SinavKitapcikID ,
                SinavOkulID ,
                SinavaGirecegiOkulID ,
                OgrenciNumarasi , 
                MekanYeriSiraNo ,
                SinifKodu  , 
                Girmedi , 
                OgrenciSeviyeID  
            FROM #okisinavogrencileri   
         
                   
            IF OBJECT_ID('tempdb..#okisinavogrencileri') IS NOT NULL DROP TABLE #okisinavogrencileri; 
            SET NOCOUNT OFF; 
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
     * @ devamsızlık listesi 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kurumVePersonelDevamsizlik($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $DersYiliID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['DersYiliID']) && $params['DersYiliID'] != "")) {
                $DersYiliID = $params['DersYiliID'];
            }
            $tarih = '1970-01-01';
            if ((isset($params['Tarih']) && $params['Tarih'] != "")) {
                $tarih = $params['Tarih'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "    
            SET NOCOUNT ON;  

            declare  @DersYiliID uniqueidentifier ;
            declare  @Tarih datetime ;
            declare  @pageNo int ;
            declare  @pageRecCount int;

            set @DersYiliID='".$DersYiliID."';
            set @Tarih= '".$tarih."';
            set @pageNo=1;
            set @pageRecCount=0;

            SELECT  							
		Tarih,	 
		Adi, 
		Soyadi, 
                CONCAT(adi collate SQL_Latin1_General_CP1254_CI_AS,' ',soyadi collate SQL_Latin1_General_CP1254_CI_AS) AS adsoyad,	
		OOB.Numarasi, 
		SinifKodu, 
		OgrenciDevamsizlikID,  
		OD.DersYiliID,  
		OD.OgrenciID,  
		OD.DevamsizlikKodID,  
		OD.DevamsizlikPeriyodID, 		
		Aciklama, 
		DevamsizlikKodu,  
		DevamsizlikAdi, 
		DevamsizlikPeriyodu,  
		ROW_NUMBER() OVER(ORDER BY Tarih, Adi, Soyadi) AS rownum   
            FROM  ".$dbnamex."GNL_OgrenciDevamsizliklari OD  
		LEFT JOIN ".$dbnamex."GNL_Kisiler K ON OD.OgrenciID=K.KisiID 
		LEFT JOIN ".$dbnamex."GNL_Ogrenciler O ON OD.OgrenciID=O.OgrenciID 
		LEFT JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON O.OgrenciID = OS.OgrenciID 
		LEFT JOIN ".$dbnamex."GNL_Siniflar S ON OS.SinifID = S.SinifID 
		LEFT JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = S.DersYiliID 
		LEFT JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OgrenciID = O.OgrenciID AND OOB.OkulID= DY.OkulID 
		LEFT JOIN ".$dbnamex."GNL_DevamsizlikKodlari DK ON OD.DevamsizlikKodID=DK.DevamsizlikKodID 
		LEFT JOIN ".$dbnamex."GNL_DevamsizlikPeriyodlari DP ON OD.DevamsizlikPeriyodID=DP.DevamsizlikPeriyodID  
            WHERE 
                    CONVERT (NVARCHAR(10),(CONVERT(DATETIME,Tarih,103)),120) =  CONVERT (NVARCHAR(10),(CONVERT(DATETIME,@Tarih,103)),120)    AND  
                    OD.DersYiliID= cast(@DersYiliID AS NVARCHAR(50))    AND 
                    S.DersYiliID= cast(@DersYiliID as nvarchar(50))   
            ORDER BY OOB.Numarasi,DevamsizlikPeriyodu DESC; 
            SET NOCOUNT OFF;  
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
     * @ sınıf seviyelerini listeler... 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function findOgrenciseviyeID($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
             
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "    
            SET NOCOUNT ON;  

            SELECT top 1 
                OS.OgrenciseviyeID, 
                OS.SinifID , 
                OK.OkulID, 
                OK.KurumID,
                OOB.OgrenciOkulBilgiID,
                DY.DersYiliID ,
                (CASE WHEN (1 = 1) THEN 1 ELSE 0 END)  as control
            FROM ".$dbnamex."GNL_OgrenciSeviyeleri OS
            INNER JOIN ".$dbnamex."GNL_Siniflar S ON S.SinifID = OS.SinifID
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = S.DersYiliID
            INNER JOIN ".$dbnamex."GNL_Okullar OK ON OK.OkulID = DY.OkulID
            INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OkulID = OK.OkulID AND OOB.OgrenciID = OS.OgrenciID
            INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = Os.OgrenciID
            WHERE     
                Os.OgrenciID = '".$KisiID."' 
            ORDER BY dy.EgitimYilID desc;

            SET NOCOUNT OFF;  
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
     * @ ogrenci için dashboard   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function dashboarddataOgrenci ($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "    
                SET NOCOUNT ON

                set datefirst 1; 
                declare  
                    @SinifID UNIQUEIDENTIFIER,
                    @DonemID INT=1,
                    @OgrenciSeviyeID uniqueidentifier ,
                    @DersYiliID uniqueidentifier ,  
                    @OkulID uniqueidentifier ,  
                    @KisiID uniqueidentifier ,
                    @SubeGrupID int;

                /*
                set @SinifID = 'F4201B97-B073-4DD7-8891-8091C3DC82CF'; 
                set @OgrenciSeviyeID ='C8611CCD-E3B1-42DB-B83E-013E419BB4B7';
                */ 
                set @KisiID = '".$KisiID."';  /* 'D74EAF39-2225-4F1C-AC9E-22F73BA8D4C8' ;  */  
                IF OBJECT_ID('tempdb..#DersProgrami') IS NOT NULL DROP TABLE #DersProgrami;   
                IF OBJECT_ID('tempdb..#xxx') IS NOT NULL DROP TABLE #xxx;  
                IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 

                CREATE TABLE #DersProgrami(DersSirasi smallint,
                                            HaftaGunu smallint,
                                            SinifDersID nvarchar(4000) collate SQL_Latin1_General_CP1254_CI_AS);
                Select distinct sd1.SinifDersID, sd1.DersHavuzuID, sd1.SinifID, dd.DersID,dd.DersAdi
                    into #dersller 
                FROM ".$dbnamex."GNL_SinifDersleri sd1    
                LEFT JOIN ".$dbnamex."GNL_Siniflar s1 on s1.SinifID = sd1.SinifID
                LEFT JOIN ".$dbnamex."GNL_DersHavuzlari  dh ON sd1.DersHavuzuID = dh.DersHavuzuID 
                LEFT JOIN ".$dbnamex."GNL_Dersler  dd ON dh.DersID = dd.DersID 

                 SELECT top 1 
                        @DersYiliID = DY.DersYiliID ,
                        @SubeGrupID = S.SubeGrupID , 
                        @OgrenciSeviyeID = OS.OgrenciseviyeID, 
                        @SinifID = OS.SinifID   
                        /*  OK.OkulID, 
                            OK.KurumID,
                            OOB.OgrenciOkulBilgiID, 
                            dy.EgitimYilID, */  
                FROM  ".$dbnamex."GNL_OgrenciSeviyeleri OS
                INNER JOIN ".$dbnamex."GNL_Siniflar S ON S.SinifID = OS.SinifID
                INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = S.DersYiliID
                INNER JOIN ".$dbnamex."GNL_Okullar OK ON OK.OkulID = DY.OkulID
                INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OkulID = OK.OkulID AND OOB.OgrenciID = OS.OgrenciID
                INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = Os.OgrenciID
                WHERE     
                    Os.OgrenciID =@KisiID
                ORDER BY dy.EgitimYilID desc; 

                DECLARE @DersSirasi smallint;
                DECLARE @HaftaGunu smallint;
                DECLARE @SinifDersID uniqueidentifier;

                DECLARE db_cursor CURSOR FOR  
                    SELECT DersSirasi,HaftaGunu,SinifDersID FROM ".$dbnamex."GNL_DersProgramlari
                    WHERE SinifDersID IN 
                        (SELECT SinifDersID FROM Bilsanet1.dbo.GNL_SinifDersleri 
                        WHERE SinifID = @SinifID AND DersHavuzuID IN (SELECT DersHavuzuID FROM ".$dbnamex."GNL_OgrenciDersleri WHERE OgrenciSeviyeID=@OgrenciSeviyeID))
                        AND DonemID = @DonemID ORDER BY HaftaGunu,DersSirasi,SinifDersID
                OPEN db_cursor   
                FETCH NEXT FROM db_cursor INTO @DersSirasi, @HaftaGunu, @SinifDersID
                WHILE @@FETCH_STATUS = 0   
                BEGIN   
                       
                    INSERT INTO #DersProgrami (DersSirasi,HaftaGunu,SinifDersID) VALUES
                    (@DersSirasi, @HaftaGunu, CAST(@SinifDersID AS nvarchar(40)))
                           
                    FETCH NEXT FROM db_cursor INTO @DersSirasi, @HaftaGunu,@SinifDersID
                END   

                CLOSE db_cursor   
                DEALLOCATE db_cursor  

                SELECT	
                    DS.BaslangicSaati,
                    DS.BitisSaati,
                    ".$dbnamex."GetFormattedTime(DS.BaslangicSaati, 1) + ' - ' + ".$dbnamex."GetFormattedTime(DS.BitisSaati, 1) AS DersSaati,
                    DS.DersSirasi,
                    DP1.SinifDersID AS Gun1_SinifDersID,
                    DP2.SinifDersID AS Gun2_SinifDersID,
                    DP3.SinifDersID AS Gun3_SinifDersID,
                    DP4.SinifDersID AS Gun4_SinifDersID,
                    DP5.SinifDersID AS Gun5_SinifDersID,
                    DP6.SinifDersID AS Gun6_SinifDersID,
                    DP7.SinifDersID AS Gun7_SinifDersID
                    into #xxx
                FROM ".$dbnamex."GNL_DersSaatleri AS DS
                INNER JOIN ".$dbnamex."GNL_DersYillari AS DY ON DY.DersYiliID = DS.DersYiliID 
                LEFT JOIN #DersProgrami AS DP1 ON (DP1.HaftaGunu=1 AND DP1.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP2 ON (DP2.HaftaGunu=2 AND DP2.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP3 ON (DP3.HaftaGunu=3 AND DP3.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP4 ON (DP4.HaftaGunu=4 AND DP4.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP5 ON (DP5.HaftaGunu=5 AND DP5.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP6 ON (DP6.HaftaGunu=6 AND DP6.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP7 ON (DP7.HaftaGunu=7 AND DP7.DersSirasi = DS.DersSirasi)  
                WHERE DY.DersYiliID = @DersYiliID AND DS.SubeGrupID = @SubeGrupID
                ORDER BY DS.DersSirasi; 

                SELECT  
                    ssdddsdsd.DersSaati, ISNULL(ssdddsdsd.SinifAdi  collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 1 as dayy, ISNULL(ssdddsdsd.SinifDersID,'') ,ISNULL(ssdddsdsd.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as DersAdi,				  
                    concat(kkk.Adi  collate SQL_Latin1_General_CP1254_CI_AS,'',kkk.Soyadi collate SQL_Latin1_General_CP1254_CI_AS) as ogretmen , '' as ogrenci , 
                    'Ders Saati' as Alan1,'Sınıf' as Alan2,'Öğretmen' as Alan3,'Öğrenci' as Alan4 ,'Ders' as Alan5 
                FROM ( 
                    SELECT rrr.DersSaati, ISNULL(g1.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 1 as dayy, rrr.Gun1_SinifDersID as SinifDersID,zzz1.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd1 on ddd1.[SinifDersID] = rrr.Gun1_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g1 on g1.SinifID = ddd1.SinifID 
                    LEFT JOIN #dersller zzz1 on zzz1.SinifDersID =  rrr.Gun1_SinifDersID
                union 
                    SELECT rrr.DersSaati, ISNULL(g2.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 2 as dayy, rrr.Gun2_SinifDersID as SinifDersID,zzz2.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 	 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd2 on ddd2.[SinifDersID] = rrr.Gun2_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g2 on g2.SinifID = ddd2.SinifID  
                    LEFT JOIN #dersller zzz2 on zzz2.SinifDersID =  rrr.Gun1_SinifDersID
                union 
                    SELECT rrr.DersSaati,  ISNULL(g3.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'')  as SinifAdi, 3 as dayy, rrr.Gun3_SinifDersID as SinifDersID,zzz3.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr  
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd3 on ddd3.[SinifDersID] = rrr.Gun3_SinifDersID  
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g3 on g3.SinifID = ddd3.SinifID  
                    LEFT JOIN #dersller zzz3 on zzz3.SinifDersID =  rrr.Gun1_SinifDersID
                union 
                    SELECT rrr.DersSaati, ISNULL(g4.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 4 as dayy, rrr.Gun4_SinifDersID  as SinifDersID,zzz4.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd4 on ddd4.[SinifDersID] = rrr.Gun4_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g4 on g4.SinifID = ddd4.SinifID  
                    LEFT JOIN #dersller zzz4 on zzz4.SinifDersID =  rrr.Gun1_SinifDersID
                union 
                    SELECT rrr.DersSaati, ISNULL(g5.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'')  as SinifAdi, 5 as dayy, rrr.Gun5_SinifDersID as SinifDersID,zzz5.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd5 on ddd5.[SinifDersID] = rrr.Gun5_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g5 on g5.SinifID = ddd5.SinifID  
                    LEFT JOIN #dersller zzz5 on zzz5.SinifDersID =  rrr.Gun1_SinifDersID
                union 
                    SELECT rrr.DersSaati, ISNULL(g6.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'')  as SinifAdi, 6 as dayy, rrr.Gun6_SinifDersID as SinifDersID,zzz6.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd6 on ddd6.[SinifDersID] = rrr.Gun6_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g6 on g6.SinifID = ddd6.SinifID 
                    LEFT JOIN #dersller zzz6 on zzz6.SinifDersID =  rrr.Gun1_SinifDersID 
                union 
                    SELECT rrr.DersSaati , ISNULL(g7.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi  , 7 as dayy, rrr.Gun7_SinifDersID  as SinifDersID,zzz7.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd7 on ddd7.[SinifDersID] = rrr.Gun7_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g7 on g7.SinifID = ddd7.SinifID 
                    LEFT JOIN #dersller zzz7 on zzz7.SinifDersID =  rrr.Gun1_SinifDersID
                ) as ssdddsdsd
                LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ssdd ON ssdd.SinifDersID =ssdddsdsd.SinifDersID  
                LEFT JOIN ".$dbnamex."[GNL_SinifOgretmenleri] soso ON soso.SinifID =ssdd.SinifID  
                LEFT JOIN ".$dbnamex."GNL_Kisiler kkk ON kkk.KisiID =soso.OgretmenID 
                WHERE   dayy =   DATEPART(dw,getdate())  
                /* and SinifAdi is not null */

                IF OBJECT_ID('tempdb..#DersProgrami') IS NOT NULL DROP TABLE #DersProgrami;   
                IF OBJECT_ID('tempdb..#xxx') IS NOT NULL DROP TABLE #xxx; 
                IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 
    
              SET NOCOUNT OFF
   
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
     * @ ogretmen için dashboard   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function dashboarddataOgretmen ($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $sql = "  
                SET NOCOUNT ON  
                DECLARE  
                        @SinifID UNIQUEIDENTIFIER,
                        @DonemID INT=1, 
                        @DersYiliID uniqueidentifier ,  
                        @OkulID uniqueidentifier ,  
                        @KisiID uniqueidentifier ,
                        @SubeGrupID int;

                set @KisiID =  '".$KisiID."';   /* '17A68CAA-1A13-460A-BEAA-FB483AC82F7B' --  '3F1A5A43-0581-4793-BB6C-AC0775EA68C5'  */ 
                set datefirst 1; 
                IF OBJECT_ID('tempdb..#DersProgrami') IS NOT NULL DROP TABLE #DersProgrami;   
                IF OBJECT_ID('tempdb..#xxx') IS NOT NULL DROP TABLE #xxx;  
                IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 
                Select distinct sd1.SinifDersID, sd1.DersHavuzuID, sd1.SinifID, dd.DersID,dd.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS
                    into #dersller 
                FROM ".$dbnamex."GNL_SinifDersleri sd1    
                LEFT JOIN ".$dbnamex."GNL_Siniflar s1 on s1.SinifID = sd1.SinifID
                LEFT JOIN ".$dbnamex."GNL_DersHavuzlari  dh ON sd1.DersHavuzuID = dh.DersHavuzuID 
                LEFT JOIN ".$dbnamex."GNL_Dersler  dd ON dh.DersID = dd.DersID 
 
                CREATE TABLE #DersProgrami(DersSirasi smallint,
                                            HaftaGunu smallint,
                                            SinifDersID nvarchar(4000)  collate SQL_Latin1_General_CP1254_CI_AS);  
  
                SELECT top 1 
                    @DersYiliID = DY.DersYiliID ,
                    @SubeGrupID = S.SubeGrupID ,  
                    @SinifID = OS.SinifID   
                 /*  OK.OkulID, 
                    OK.KurumID,
                    OOB.OgrenciOkulBilgiID, 
                    dy.EgitimYilID, */  
                FROM ".$dbnamex."[GNL_SinifOgretmenleri] OS
                INNER JOIN ".$dbnamex."GNL_Siniflar S ON S.SinifID = OS.SinifID
                INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = S.DersYiliID
                INNER JOIN ".$dbnamex."GNL_Okullar OK ON OK.OkulID = DY.OkulID 
                INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = Os.OgretmenID 
                LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ssdd ON os.SinifID =ssdd.SinifID   
                WHERE     
                    os.OgretmenID =@KisiID
                ORDER BY dy.EgitimYilID desc; 
		
                SELECT @DonemID =donemID FROM ( 
                    SELECT 
                        1 as donemID 
                    FROM ".$dbnamex."[GNL_DersYillari]
                    WHERE [AktifMi] =1 and 
                        getdate() between [Donem1BaslangicTarihi] and [Donem1BitisTarihi] and 
                       DersYiliID =@DersYiliID 
                union 
                    SELECT 
                        2 as donemID 
                    FROM ".$dbnamex."[GNL_DersYillari]
                    WHERE [AktifMi] =1 and 
                        getdate() between [Donem2BaslangicTarihi] and [Donem2BitisTarihi] and 
                        DersYiliID =@DersYiliID 
                   ) as sdsasd; 

                DECLARE @DersSirasi smallint;
                DECLARE @HaftaGunu smallint;
                DECLARE @SinifDersID uniqueidentifier;

                DECLARE db_cursor CURSOR FOR  
                SELECT DersSirasi,HaftaGunu,SinifDersID FROM ".$dbnamex."GNL_DersProgramlari
                WHERE SinifDersID IN (SELECT SinifDersID FROM ".$dbnamex."GNL_SinifDersleri WHERE SinifID = @SinifID)
                    AND DonemID = @DonemID ORDER BY HaftaGunu,DersSirasi,SinifDersID
                OPEN db_cursor   
                FETCH NEXT FROM db_cursor INTO @DersSirasi, @HaftaGunu, @SinifDersID
                WHILE @@FETCH_STATUS = 0   
                BEGIN   
                       
                    INSERT INTO #DersProgrami (DersSirasi,HaftaGunu,SinifDersID) VALUES
                    (@DersSirasi, @HaftaGunu, CAST(@SinifDersID AS nvarchar(40)))
                        
                    FETCH NEXT FROM db_cursor INTO @DersSirasi, @HaftaGunu,@SinifDersID
                END   

                CLOSE db_cursor   
                DEALLOCATE db_cursor  

                SELECT 
                    DS.BaslangicSaati,
                    DS.BitisSaati,
                    ".$dbnamex."GetFormattedTime(DS.BaslangicSaati, 1) + ' - ' + ".$dbnamex."GetFormattedTime(DS.BitisSaati, 1) AS DersSaati,
                    DS.DersSirasi,
                    DP1.SinifDersID AS Gun1_SinifDersID,
                    DP2.SinifDersID AS Gun2_SinifDersID,
                    DP3.SinifDersID AS Gun3_SinifDersID,
                    DP4.SinifDersID AS Gun4_SinifDersID,
                    DP5.SinifDersID AS Gun5_SinifDersID,
                    DP6.SinifDersID AS Gun6_SinifDersID,
                    DP7.SinifDersID AS Gun7_SinifDersID
                    into #xxx
                FROM ".$dbnamex."GNL_DersSaatleri AS DS
                INNER JOIN ".$dbnamex."GNL_DersYillari AS DY ON DY.DersYiliID = DS.DersYiliID 
                LEFT JOIN #DersProgrami AS DP1 ON (DP1.HaftaGunu=1 AND DP1.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP2 ON (DP2.HaftaGunu=2 AND DP2.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP3 ON (DP3.HaftaGunu=3 AND DP3.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP4 ON (DP4.HaftaGunu=4 AND DP4.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP5 ON (DP5.HaftaGunu=5 AND DP5.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP6 ON (DP6.HaftaGunu=6 AND DP6.DersSirasi = DS.DersSirasi)  
                LEFT JOIN #DersProgrami AS DP7 ON (DP7.HaftaGunu=7 AND DP7.DersSirasi = DS.DersSirasi)  
                WHERE DY.DersYiliID = @DersYiliID AND DS.SubeGrupID = @SubeGrupID
                ORDER BY DS.DersSirasi

                SELECT  
                    ssdddsdsd.DersSaati, ISNULL(ssdddsdsd.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 1 as dayy, ISNULL(ssdddsdsd.SinifDersID,'') ,ISNULL(ssdddsdsd.DersAdi,'') as DersAdi collate SQL_Latin1_General_CP1254_CI_AS,				  
                    concat(kkk.Adi ,'',kkk.Soyadi) as ogretmen , '' as ogrenci , 
                    'Ders Saati' as Alan1,'Sınıf' as Alan2,'Öğretmen' as Alan3,'Öğrenci' as Alan4 ,'Ders' as Alan5 

                FROM ( 
                    SELECT rrr.DersSaati, ISNULL(g1.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 1 as dayy, rrr.Gun1_SinifDersID as SinifDersID,zzz1.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd1 on ddd1.[SinifDersID] = rrr.Gun1_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g1 on g1.SinifID = ddd1.SinifID  
                    LEFT JOIN #dersller zzz1 on zzz1.SinifDersID =  rrr.Gun1_SinifDersID
                union 
                    SELECT rrr.DersSaati, ISNULL(g2.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 2 as dayy, rrr.Gun2_SinifDersID as SinifDersID,zzz2.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 	 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd2 on ddd2.[SinifDersID] = rrr.Gun2_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g2 on g2.SinifID = ddd2.SinifID  
                    LEFT JOIN #dersller zzz2 on zzz2.SinifDersID =  rrr.Gun2_SinifDersID
                union 
                    SELECT rrr.DersSaati, ISNULL(g3.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'')  as SinifAdi, 3 as dayy, rrr.Gun3_SinifDersID as SinifDersID,zzz3.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr  
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd3 on ddd3.[SinifDersID] = rrr.Gun3_SinifDersID  
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g3 on g3.SinifID = ddd3.SinifID  
                    LEFT JOIN #dersller zzz3 on zzz3.SinifDersID =  rrr.Gun3_SinifDersID
                union 
                    SELECT rrr.DersSaati, ISNULL(g4.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 4 as dayy, rrr.Gun4_SinifDersID  as SinifDersID,zzz4.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd4 on ddd4.[SinifDersID] = rrr.Gun4_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g4 on g4.SinifID = ddd4.SinifID 
                    LEFT JOIN #dersller zzz4 on zzz4.SinifDersID =  rrr.Gun4_SinifDersID 
                union 
                    SELECT rrr.DersSaati, ISNULL(g5.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'')  as SinifAdi, 5 as dayy, rrr.Gun5_SinifDersID as SinifDersID,zzz5.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd5 on ddd5.[SinifDersID] = rrr.Gun5_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g5 on g5.SinifID = ddd5.SinifID 
                    LEFT JOIN #dersller zzz5 on zzz5.SinifDersID =  rrr.Gun5_SinifDersID 
                union 
                    SELECT rrr.DersSaati, ISNULL(g6.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'')  as SinifAdi, 6 as dayy, rrr.Gun6_SinifDersID as SinifDersID,zzz6.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd6 on ddd6.[SinifDersID] = rrr.Gun6_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g6 on g6.SinifID = ddd6.SinifID 
                    LEFT JOIN #dersller zzz6 on zzz6.SinifDersID =  rrr.Gun6_SinifDersID 
                union 
                    SELECT rrr.DersSaati, ISNULL(g7.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi  , 7 as dayy, rrr.Gun7_SinifDersID  as SinifDersID,zzz7.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #xxx rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd7 on ddd7.[SinifDersID] = rrr.Gun7_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g7 on g7.SinifID = ddd7.SinifID 
                    LEFT JOIN #dersller zzz7 on zzz7.SinifDersID =  rrr.Gun7_SinifDersID
                ) as ssdddsdsd
                LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ssdd ON ssdd.SinifDersID =ssdddsdsd.SinifDersID  
                LEFT JOIN ".$dbnamex."[GNL_SinifOgretmenleri] soso ON soso.SinifID =ssdd.SinifID  
                LEFT JOIN ".$dbnamex."GNL_Kisiler kkk ON kkk.KisiID =soso.OgretmenID 
                 WHERE dayy =  DATEPART(dw,getdate())  
                /* and SinifAdi is not null */

                IF OBJECT_ID('tempdb..#DersProgrami') IS NOT NULL DROP TABLE #DersProgrami;   
                IF OBJECT_ID('tempdb..#xxx') IS NOT NULL DROP TABLE #xxx; 
                IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 
                SET NOCOUNT OFF 
     
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
     * @ veli için dashboard   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function dashboarddataYakini ($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "   
            SET NOCOUNT ON

            set datefirst 1; 
            declare  
                @SinifID UNIQUEIDENTIFIER,
                @DonemID INT=1,
                @OgrenciSeviyeID uniqueidentifier ,
                @DersYiliID uniqueidentifier ,  
                @OkulID uniqueidentifier ,  
                @KisiID uniqueidentifier ,
                @SubeGrupID int; 

                /*
                set @SinifID = 'F4201B97-B073-4DD7-8891-8091C3DC82CF'; 
                set @OgrenciSeviyeID ='C8611CCD-E3B1-42DB-B83E-013E419BB4B7';
                */ 
                set @KisiID = '".$KisiID."';  /* 'A552D233-1842-4DA1-8B3B-33FE3358E3F3' ;  */  
                IF OBJECT_ID('tempdb..#DersProgrami') IS NOT NULL DROP TABLE #DersProgrami;   
                IF OBJECT_ID('tempdb..#DersProgramiSonuc') IS NOT NULL DROP TABLE #DersProgramiSonuc;  
                IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 
                Select distinct sd1.SinifDersID, sd1.DersHavuzuID, sd1.SinifID, dd.DersID,dd.DersAdi
                    into #dersller 
                FROM ".$dbnamex."GNL_SinifDersleri sd1    
                LEFT JOIN ".$dbnamex."GNL_Siniflar s1 on s1.SinifID = sd1.SinifID
                LEFT JOIN ".$dbnamex."GNL_DersHavuzlari  dh ON sd1.DersHavuzuID = dh.DersHavuzuID 
                LEFT JOIN ".$dbnamex."GNL_Dersler  dd ON dh.DersID = dd.DersID 
                CREATE TABLE #DersProgrami(DersSirasi smallint,
                                            HaftaGunu smallint,
                                            SinifDersID nvarchar(4000)  collate SQL_Latin1_General_CP1254_CI_AS,
                                            OgrenciseviyeID nvarchar(4000) collate SQL_Latin1_General_CP1254_CI_AS);
		
		CREATE TABLE #DersProgramiSonuc(  BaslangicSaati varchar(20)  collate SQL_Latin1_General_CP1254_CI_AS,
                    BitisSaati varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                    DersSaati varchar(20) collate SQL_Latin1_General_CP1254_CI_AS,
                    DersSirasi int,
                    Gun1_SinifDersID nvarchar(40) collate SQL_Latin1_General_CP1254_CI_AS,
                    Gun2_SinifDersID nvarchar(40) collate SQL_Latin1_General_CP1254_CI_AS,
                    Gun3_SinifDersID nvarchar(40) collate SQL_Latin1_General_CP1254_CI_AS,
                    Gun4_SinifDersID nvarchar(40) collate SQL_Latin1_General_CP1254_CI_AS,
                    Gun5_SinifDersID nvarchar(40) collate SQL_Latin1_General_CP1254_CI_AS,
                    Gun6_SinifDersID nvarchar(40) collate SQL_Latin1_General_CP1254_CI_AS,
                    Gun7_SinifDersID nvarchar(40) collate SQL_Latin1_General_CP1254_CI_AS,
                    OgrenciseviyeID nvarchar(40) collate SQL_Latin1_General_CP1254_CI_AS);  
  
                DECLARE @DersSirasi smallint;
                DECLARE @HaftaGunu smallint;
                DECLARE @SinifDersID uniqueidentifier;
   
                DECLARE db_cursor CURSOR FOR  
                        SELECT  
                            DY.DersYiliID ,
                            S.SubeGrupID , 
                            OS.OgrenciseviyeID, 
                            OS.SinifID   
                        FROM ".$dbnamex."[GNL_OgrenciYakinlari] oy
                        INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS on  oy.OgrenciID =  Os.OgrenciID
                        INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = Os.OgrenciID 
                        INNER JOIN ".$dbnamex."GNL_Siniflar S ON S.SinifID = OS.SinifID
                        INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = S.DersYiliID
                        INNER JOIN ".$dbnamex."GNL_Okullar OK ON OK.OkulID = DY.OkulID
                        INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OkulID = OK.OkulID AND OOB.OgrenciID = OS.OgrenciID 
                        WHERE     
                           oy.YakinID =@KisiID
                        ORDER BY dy.EgitimYilID desc;  
			
		OPEN db_cursor   
                FETCH NEXT FROM db_cursor INTO @DersYiliID, @SubeGrupID , @OgrenciseviyeID, @SinifID
                WHILE @@FETCH_STATUS = 0   
                BEGIN   

                    DECLARE db_cursor1 CURSOR FOR  
                        SELECT DersSirasi,HaftaGunu,SinifDersID FROM ".$dbnamex."GNL_DersProgramlari
                        WHERE SinifDersID IN 
                            (SELECT SinifDersID FROM ".$dbnamex."GNL_SinifDersleri 
                            WHERE SinifID = @SinifID AND DersHavuzuID IN (SELECT DersHavuzuID FROM ".$dbnamex."GNL_OgrenciDersleri WHERE OgrenciSeviyeID=@OgrenciSeviyeID))
                            AND DonemID = @DonemID ORDER BY HaftaGunu,DersSirasi,SinifDersID
                        OPEN db_cursor1   
                        FETCH NEXT FROM db_cursor1 INTO @DersSirasi, @HaftaGunu, @SinifDersID
                        WHILE @@FETCH_STATUS = 0   
                        BEGIN   

                            INSERT INTO #DersProgrami (DersSirasi,HaftaGunu,SinifDersID,OgrenciseviyeID) VALUES
                            (@DersSirasi, @HaftaGunu, CAST(@SinifDersID AS nvarchar(40)),@OgrenciseviyeID)

                            FETCH NEXT FROM db_cursor1 INTO @DersSirasi, @HaftaGunu,@SinifDersID
                        END   

                    CLOSE db_cursor1   
                    DEALLOCATE db_cursor1  
		 
                    insert into #DersProgramiSonuc (BaslangicSaati,BitisSaati,DersSaati,
                    DersSirasi,Gun1_SinifDersID,Gun2_SinifDersID,Gun3_SinifDersID,
                    Gun4_SinifDersID,Gun5_SinifDersID,Gun6_SinifDersID,Gun7_SinifDersID,OgrenciseviyeID)
                    SELECT 
                        DS.BaslangicSaati,
                        DS.BitisSaati,
                        ".$dbnamex."GetFormattedTime(DS.BaslangicSaati, 1) + ' - ' + ".$dbnamex."GetFormattedTime(DS.BitisSaati, 1) AS DersSaati,
                        DS.DersSirasi,
                        DP1.SinifDersID AS Gun1_SinifDersID,
                        DP2.SinifDersID AS Gun2_SinifDersID,
                        DP3.SinifDersID AS Gun3_SinifDersID,
                        DP4.SinifDersID AS Gun4_SinifDersID,
                        DP5.SinifDersID AS Gun5_SinifDersID,
                        DP6.SinifDersID AS Gun6_SinifDersID,
                        DP7.SinifDersID AS Gun7_SinifDersID,
                        @OgrenciseviyeID
                    FROM ".$dbnamex."GNL_DersSaatleri AS DS
                    INNER JOIN ".$dbnamex."GNL_DersYillari AS DY ON DY.DersYiliID = DS.DersYiliID 
                    LEFT JOIN #DersProgrami AS DP1 ON (DP1.HaftaGunu=1 AND DP1.DersSirasi = DS.DersSirasi)  
                    LEFT JOIN #DersProgrami AS DP2 ON (DP2.HaftaGunu=2 AND DP2.DersSirasi = DS.DersSirasi)  
                    LEFT JOIN #DersProgrami AS DP3 ON (DP3.HaftaGunu=3 AND DP3.DersSirasi = DS.DersSirasi)  
                    LEFT JOIN #DersProgrami AS DP4 ON (DP4.HaftaGunu=4 AND DP4.DersSirasi = DS.DersSirasi)  
                    LEFT JOIN #DersProgrami AS DP5 ON (DP5.HaftaGunu=5 AND DP5.DersSirasi = DS.DersSirasi)  
                    LEFT JOIN #DersProgrami AS DP6 ON (DP6.HaftaGunu=6 AND DP6.DersSirasi = DS.DersSirasi)  
                    LEFT JOIN #DersProgrami AS DP7 ON (DP7.HaftaGunu=7 AND DP7.DersSirasi = DS.DersSirasi)  

                    WHERE DY.DersYiliID = @DersYiliID AND DS.SubeGrupID = @SubeGrupID
                    ORDER BY DS.DersSirasi;  
 
                    FETCH NEXT FROM db_cursor INTO @DersYiliID, @SubeGrupID , @OgrenciseviyeID, @SinifID
                END   

                CLOSE db_cursor   
                DEALLOCATE db_cursor  

	  
                SELECT
                    ssdddsdsd.DersSaati, ISNULL(ssdddsdsd.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 1 as dayy, ISNULL(ssdddsdsd.SinifDersID,'') as SinifDersID,ISNULL(ssdddsdsd.DersAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as DersAdi,				  
                    concat(kkk.Adi ,'',kkk.Soyadi) as ogretmen , concat(k.Adi ,' ',k.Soyadi) as ogrenci ,
                   'Ders Saati' as Alan1,'Sınıf' as Alan2,'Öğretmen' as Alan3,'Öğrenci' as Alan4,'Ders' as Alan5  
                FROM ( 
                    SELECT rrr.DersSaati, ISNULL(g1.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 1 as dayy, rrr.Gun1_SinifDersID as SinifDersID, OgrenciseviyeID, DersSirasi,zzz1.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #DersProgramiSonuc rrr
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd1 on ddd1.[SinifDersID] = rrr.Gun1_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g1 on g1.SinifID = ddd1.SinifID
                    LEFT JOIN #dersller zzz1 on zzz1.SinifDersID =  rrr.Gun1_SinifDersID
                union 
                    SELECT rrr.DersSaati, ISNULL(g2.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 2 as dayy, rrr.Gun2_SinifDersID as SinifDersID, OgrenciseviyeID, DersSirasi,zzz2.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #DersProgramiSonuc rrr 	 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd2 on ddd2.[SinifDersID] = rrr.Gun2_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g2 on g2.SinifID = ddd2.SinifID 
                    LEFT JOIN #dersller zzz2 on zzz2.SinifDersID =  rrr.Gun2_SinifDersID 
                union 
                    SELECT rrr.DersSaati, ISNULL(g3.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'')  as SinifAdi, 3 as dayy, rrr.Gun3_SinifDersID as SinifDersID, OgrenciseviyeID, DersSirasi,zzz3.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #DersProgramiSonuc rrr  
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd3 on ddd3.[SinifDersID] = rrr.Gun3_SinifDersID  
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g3 on g3.SinifID = ddd3.SinifID  
                    LEFT JOIN #dersller zzz3 on zzz3.SinifDersID =  rrr.Gun3_SinifDersID
                union 
                    SELECT rrr.DersSaati, ISNULL(g4.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi , 4 as dayy, rrr.Gun4_SinifDersID  as SinifDersID, OgrenciseviyeID, DersSirasi,zzz4.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #DersProgramiSonuc rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd4 on ddd4.[SinifDersID] = rrr.Gun4_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g4 on g4.SinifID = ddd4.SinifID 
                    LEFT JOIN #dersller zzz4 on zzz4.SinifDersID =  rrr.Gun4_SinifDersID 
                union 
                    SELECT rrr.DersSaati, ISNULL(g5.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'')  as SinifAdi, 5 as dayy, rrr.Gun5_SinifDersID as SinifDersID, OgrenciseviyeID, DersSirasi,zzz5.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #DersProgramiSonuc rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd5 on ddd5.[SinifDersID] = rrr.Gun5_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g5 on g5.SinifID = ddd5.SinifID 
                    LEFT JOIN #dersller zzz5 on zzz5.SinifDersID =  rrr.Gun5_SinifDersID 
                union 
                    SELECT rrr.DersSaati, ISNULL(g6.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'')  as SinifAdi, 6 as dayy, rrr.Gun6_SinifDersID as SinifDersID, OgrenciseviyeID, DersSirasi,zzz6.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #DersProgramiSonuc rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd6 on ddd6.[SinifDersID] = rrr.Gun6_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g6 on g6.SinifID = ddd6.SinifID
                    LEFT JOIN #dersller zzz6 on zzz6.SinifDersID =  rrr.Gun6_SinifDersID  
                union 
                    SELECT rrr.DersSaati, ISNULL(g7.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS,'') as SinifAdi  , 7 as dayy, rrr.Gun7_SinifDersID  as SinifDersID, OgrenciseviyeID, DersSirasi,zzz7.DersAdi collate SQL_Latin1_General_CP1254_CI_AS
                    FROM #DersProgramiSonuc rrr 
                    LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ddd7 on ddd7.[SinifDersID] = rrr.Gun7_SinifDersID
                    LEFT JOIN ".$dbnamex."[GNL_Siniflar] g7 on g7.SinifID = ddd7.SinifID
                    LEFT JOIN #dersller zzz7 on zzz7.SinifDersID =  rrr.Gun7_SinifDersID 
                ) as ssdddsdsd
                INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS on ssdddsdsd.OgrenciseviyeID =  Os.OgrenciseviyeID
                INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = Os.OgrenciID 
                LEFT JOIN ".$dbnamex."[GNL_SinifDersleri] ssdd ON ssdd.SinifDersID =ssdddsdsd.SinifDersID  
                LEFT JOIN ".$dbnamex."[GNL_SinifOgretmenleri] soso ON soso.SinifID =ssdd.SinifID  
                LEFT JOIN ".$dbnamex."GNL_Kisiler kkk ON kkk.KisiID =soso.OgretmenID 
                WHERE dayy = DATEPART(dw,getdate())  
                /* and SinifAdi is not null */
                ORDER BY ogrenci , DersSirasi

            IF OBJECT_ID('tempdb..#DersProgrami') IS NOT NULL DROP TABLE #DersProgrami;   
            IF OBJECT_ID('tempdb..#DersProgramiSonuc') IS NOT NULL DROP TABLE #DersProgramiSonuc;  
            IF OBJECT_ID('tempdb..#dersller') IS NOT NULL DROP TABLE #dersller; 
            SET NOCOUNT OFF;
          
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
     * @ yönetici için dashboard   !!
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function dashboarddataYonetici ($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
           
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID'];
            }
            $KurumID=  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['KurumID']) && $params['KurumID'] != "")) {
                $KurumID = $params['KurumID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
             
            $sql = "   
            SET NOCOUNT ON
       
            declare  @EgitimYilID INT ,
            @KurumID UNIQUEIDENTIFIER ; 
                set  @KurumID='".$KurumID."';
            /*    set @EgitimYilID ='2016';*/ 
	 

            SELECT  @EgitimYilID = max(EgitimYilID) FROM  
            ".$dbnamex."GNL_DersYillari DY 
            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE  
                KurumID =  @KurumID AND
                AktifMi = 1

            DECLARE @Dil NVARCHAR(255)
            SELECT  @Dil = @@language
            SET language turkish 


            DECLARE @Bugun SMALLDATETIME = DATEADD(d, DATEDIFF(d, 0, GETDATE()), 0)

            DECLARE @BaslangicAY SMALLDATETIME
            DECLARE @BitisAY SMALLDATETIME

            DECLARE @BaslangicYil SMALLDATETIME
            DECLARE @BitisYil SMALLDATETIME

            SET @BaslangicAY = DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)
            SET @BitisAY = DATEADD(MINUTE, -1,
                                   DATEADD(MONTH,
                                           DATEDIFF(MONTH, 0, GETDATE()) + 1, 0))

            SET @BaslangicYil = DATEADD(YEAR, DATEDIFF(YEAR, 0, GETDATE()), 0)
            SET @BitisYil = DATEADD(MINUTE, -1,
                                    DATEADD(YEAR, DATEDIFF(YEAR, 0, GETDATE()) + 1,
                                            0))
 
            SELECT distinct ISNULL(Gelecek,'') AS DersSaati, ISNULL(YapilacakTahsilat,'') AS SinifAdi , '' AS ogretmen , '' AS ogrenci
             , 'Gelecek' as Alan1,'Yapilacak Tahsilat' as Alan2,'' as Alan3,'' as Alan4
            FROM  ( 		
            SELECT  /*'Günlük Toplam Tahsilat' AS Tahsilat ,*/
                    'Bugün Yapılacak' AS Gelecek ,
                /*    'Yapılan Ödemelerden Bugün Yapılanlar' AS TahsilatAciklama ,
                    'Ödeme Planında Bugün Yapılması Gerekenler' AS GelecekAciklama ,*/
                    ISNULL(( SELECT SUM(BOP.TaksitTutari)
                             FROM   ".$dbnamex."MUH_BorcluOdemePlani BOP
                                    INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = BOP.BorcluSozlesmeID
                                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                                    INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                             WHERE  BOP.OdemeTarihi = @Bugun
                                    AND KurumID = @KurumID
                                    AND BOP.OdemeTarihi IS NOT NULL
                                    AND Odendi = 0
                                    AND EgitimYilID = @EgitimYilID
                           ), 0) AS YapilacakTahsilat ,
                    ISNULL(SUM(YO.OdemeTutari), 0) AS YapilanTahsilat  
            FROM    ".$dbnamex."MUH_YapilanOdemeler YO
                    INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = YO.BorcluSozlesmeID
                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                    INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE   YO.OdemeTarihi = @Bugun
                    AND KurumID = @KurumID
                    AND EgitimYilID = @EgitimYilID
                     
            UNION ALL
                     
            SELECT  /*'Aylık Toplam Tahsilat' AS Tahsilat ,*/
                    'Bugünden Sonra Bu Ay Yapılacak' AS Gelecek ,
                  /*  'Bu Ay Yapılan Ödemeler' AS TahsilatAciklama ,
                    'Ödeme Planında Bugünden Sonra Bu Ay Sonuna Kadar Yapılması Gerekenler' AS GelecekAciklama ,*/
                    ISNULL(( SELECT SUM(BOP.TaksitTutari)
                             FROM   ".$dbnamex."MUH_BorcluOdemePlani BOP
                                    INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = BOP.BorcluSozlesmeID
                                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                                    INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                             WHERE  BOP.OdemeTarihi > @Bugun
                                    AND KurumID = @KurumID
                                    AND BOP.OdemeTarihi <= @BitisAY
                                    AND BOP.OdemeTarihi IS NOT NULL
                                    AND Odendi = 0
                                    AND EgitimYilID = @EgitimYilID
                           ), 0) AS YapilacakTahsilat ,
                    ISNULL(SUM(YO.OdemeTutari), 0) AS YapilanTahsilat  
            FROM    ".$dbnamex."MUH_YapilanOdemeler YO
                    INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = YO.BorcluSozlesmeID
                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                    INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE   YO.OdemeTarihi >= @BaslangicAY
                    AND YO.OdemeTarihi <= @BitisAY
                    AND KurumID = @KurumID
                    AND EgitimYilID = @EgitimYilID
                     
            UNION ALL
                     
            SELECT  /* 'Yıllık Toplam Tahsilat' AS Tahsilat , */
                    'Bugünden Sonra Bu Yıl Yapılacak' AS Gelecek ,
                 /*  'Yapılan Ödemelerden Bu Yıl Yapılan Ödemeler' AS TahsilatAciklama ,
                   'Ödeme Planında Bugünden Sonra Bu Yıl Sonuna Kadar Yapılması Gerekenler' AS GelecekAciklama ,*/
                    ISNULL(( SELECT SUM(BOP.TaksitTutari)
                             FROM   ".$dbnamex."MUH_BorcluOdemePlani BOP
                                    INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = BOP.BorcluSozlesmeID
                                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                                    INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
                             WHERE  BOP.OdemeTarihi > @Bugun
                                    AND KurumID = @KurumID
                                    AND BOP.OdemeTarihi <= @BitisYil
                                    AND BOP.OdemeTarihi IS NOT NULL
                                    AND Odendi = 0
                                    AND EgitimYilID = @EgitimYilID
                           ), 0) AS YapilacakTahsilat ,
                    ISNULL(SUM(YO.OdemeTutari), 0) AS YapilanTahsilat  
            FROM    ".$dbnamex."MUH_YapilanOdemeler YO
                    INNER JOIN ".$dbnamex."MUH_BorcluSozlesmeleri BS ON BS.BorcluSozlesmeID = YO.BorcluSozlesmeID
                    INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = BS.DersYiliID
                    INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = DY.OkulID
            WHERE   YO.OdemeTarihi >= @BaslangicYil
                    AND YO.OdemeTarihi <= @BitisYil
                    AND KurumID = @KurumID
                    AND EgitimYilID = @EgitimYilID
                                    ) AS sss ;

   
            SET NOCOUNT OFF;
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
     * @ odev tipleri
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function odevTipleri($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "  
                


            SET NOCOUNT ON;  
            
            SELECT 
                -1 as OdevTipID, 
                COALESCE(NULLIF(ax.[description]  collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng] collate SQL_Latin1_General_CP1254_CI_AS) AS OdevTipi 
                
            FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
            INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
            LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
            WHERE a.[main_group] = 1 and a.[first_group]  = 11 and
                a.language_parent_id =0  

            UNION
            SELECT 
                a.[OdevTipID], 
                COALESCE(NULLIF(COALESCE(NULLIF(ax.OdevTipi collate SQL_Latin1_General_CP1254_CI_AS,''),ax.OdevTipi_lng collate SQL_Latin1_General_CP1254_CI_AS),''),a.OdevTipi)  as OdevTipi
            FROM  ".$dbnamex."[ODV_OdevTipleri] a
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0 
            LEFT JOIN BILSANET_MOBILE.dbo.Mobile_OdevTipleri_lng ax on (ax.OdevTipID = a.OdevTipID) and ax.language_id= lx.id   
		 
            ORDER BY OdevTipID  

 
            SET NOCOUNT OFF;    
 
  
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
     * @ ogretmen odev atama işlemleri
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function odevAtama($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            
            $SinifDersID= 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifDersID']) && $params['SinifDersID'] != "")) {
                $SinifDersID = $params['SinifDersID'];
            } 
            $OgretmenID= 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $OgretmenID = $params['OgretmenID'];
            } 
            $Tarih= 'getdate()';
            if ((isset($params['Tarih']) && $params['Tarih'] != "")) {
                $Tarih = $params['Tarih'];
            } 
            $Tanim= 'Tanim';
            if ((isset($params['Tanim']) && $params['Tanim'] != "")) {
                $Tanim = $params['Tanim'];
            } 
            $Aciklama= '';
            if ((isset($params['Aciklama']) && $params['Aciklama'] != "")) {
                $Aciklama = $params['Aciklama'];
            }
            $TeslimTarihi= '';
            if ((isset($params['TeslimTarihi']) && $params['TeslimTarihi'] != "")) {
                $TeslimTarihi = $params['TeslimTarihi'];
            }
            $OdevTipID= 'OdevTipID';
            if ((isset($params['OdevTipID']) && $params['OdevTipID'] != "")) {
                $OdevTipID = $params['OdevTipID'];
            } 
            $NotIleDegerlendirilsin= 'NotIleDegerlendirilsin';
            if ((isset($params['NotIleDegerlendirilsin']) && $params['NotIleDegerlendirilsin'] != "")) {
                $NotIleDegerlendirilsin = $params['NotIleDegerlendirilsin'];
            } 
            $DonemNotunaEtkiEtsin= 'DonemNotunaEtkiEtsin';
            if ((isset($params['DonemNotunaEtkiEtsin']) && $params['DonemNotunaEtkiEtsin'] != "")) {
                $DonemNotunaEtkiEtsin = $params['DonemNotunaEtkiEtsin'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            $SendXmlData = '';
            $p2= '';
            if ((isset($params['XmlData']) && $params['XmlData'] != "")) {
                $p2 = $params['XmlData'];
                
                /*  
                <IDLIST>
                <ID VALUE='5E2D953C-0A7D-4A63-9368-01690DC7FE51"/>
                <ID VALUE="AEEFE2B7-6653-4776-9343-031155AF6181"/>
                </IDLIST>
                  
                 */
            $XmlData = ' '; 
            $dataValue = NULL;
            $devamsizlikKodID = NULL;
            if ((isset($params['XmlData']) && $params['XmlData'] != "")) {
                $XmlData = $params['XmlData'];
                $dataValue =  json_decode($XmlData, true);
                
             //   print_r( "////////////"); 
            //   print_r($dataValue  ); 
                // echo( "\\\\\\console\\\\\\"); 
                    foreach ($dataValue as $std) {                      
                        if ($std  != null) {
                        //   print_r($std ); 
                        //   if ($std[1] == 1) { $devamsizlikKodID = 2 ;}
                        //   if ($std[2] == 1) { $devamsizlikKodID = 0 ;}
                     
                          //  print_r(htmlentities('<Ogrenci><OgrenciID>').$dataValue[0][0]).htmlentities('</OgrenciID><DevamsizlikKodID>').$dataValue[0][1].htmlentities('</DevamsizlikKodID> ' )  ; 
                      // echo( '<Ogrenci><OgrenciID>'.$std[0].'</OgrenciID><DevamsizlikKodID>'.$devamsizlikKodID.'</DevamsizlikKodID><Aciklama/></Ogrenci>' ); 
                         $SendXmlData =$SendXmlData.'<ID VALUE="'.$std.'"/>' ;  
                        }
                    }
                  
               $SendXmlData = '<IDLIST>'.$SendXmlData.'</IDLIST>';
            }  
                
            } 
            $sql = "  
            SET NOCOUNT ON;   
            
            declare @p1 uniqueidentifier ,  
                   @SinifDersID1 UNIQUEIDENTIFIER,
                   @OgretmenID1 UNIQUEIDENTIFIER,
                   @Tarih1 SMALLDATETIME,
                   @Tanim1 NVARCHAR(100) ='' collate SQL_Latin1_General_CP1254_CI_AS,
                   @Aciklama1 NVARCHAR(max),
                   @TeslimTarihi1 SMALLDATETIME,
                   @OdevTipID1 TINYINT,
                   @DosyaID1 UNIQUEIDENTIFIER ,
                   @NotIleDegerlendirilsin1 BIT,
                   @DonemNotunaEtkiEtsin1 BIT,
                   @SentSms1 BIT,
                   @SentEPosta1 BIT;
 
            set @p1 =   NEWID()  ;
 
            set @SinifDersID1 ='".$SinifDersID."';
            set @OgretmenID1 ='".$OgretmenID."';
            set @Tarih1 ='".$Tarih."';
            set @Tanim1 =N'".$Tanim."';
            set @Aciklama1 =N'".$Aciklama."';
            set @TeslimTarihi1 ='".$TeslimTarihi."';
            set @OdevTipID1 =".$OdevTipID.";

            set @NotIleDegerlendirilsin1 = ".$NotIleDegerlendirilsin.";
            set @DonemNotunaEtkiEtsin1 =".$DonemNotunaEtkiEtsin.";
            set @SentSms1 =0;
            set @SentEPosta1 =0;
          
            exec dbo.PRC_ODV_OdevTanimlari_Save 
                    @OdevTanimID=@p1,
                    @SinifDersID=@SinifDersID1,
                    @OgretmenID=@OgretmenID1,
                    @Tarih=@Tarih1,
                    @Tanim=@Tanim1,
                    @Aciklama=@Aciklama1,
                    @TeslimTarihi=@TeslimTarihi1,
                    @OdevTipID=@OdevTipID1,
                    @DosyaID=NULL,
                    @NotIleDegerlendirilsin=@NotIleDegerlendirilsin1,
                    @DonemNotunaEtkiEtsin=@DonemNotunaEtkiEtsin1,
                    @SentSms=@SentSms1,
                    @SentEPosta=@SentEPosta1; 
 
                declare @p2 xml
                set @p2=convert(xml,N";
           
        $sql = $sql. "'".$SendXmlData."')
                exec dbo.PRC_ODV_OdevTanimlari_Dagit @OdevTanimID= @p1 ,@OgrenciXML=@p2
  

            SET NOCOUNT OFF; 
                ";  
            
            $statement = $pdo->prepare($sql); 
      // echo debugPDO($sql, $params);
            $result = $statement->execute(); 
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
     * @ öğrenci karnesi 
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrenciKarnesi($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            } 
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
            
            $KisiID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciID']) && $params['OgrenciID'] != "")) {
                $KisiID = $params['OgrenciID'];
            }
            $findOgrenciseviyeIDValue= null ; 
            $findOgrenciseviyeID = $this->findOgrenciseviyeID(
                            array( 'KisiID' =>$KisiID, 'Cid' =>$cid,'Did' =>$did, ));
            if (\Utill\Dal\Helper::haveRecord($findOgrenciseviyeID)) {
                $findOgrenciseviyeIDValue = $findOgrenciseviyeID ['resultSet'][0]['OgrenciseviyeID'];
            }   
           
            $DonemID = 1;
            if ((isset($params['DonemID']) && $params['DonemID'] != "")) {
                $DonemID = $params['DonemID'];
            }
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
              
            $sql = "  
            SET NOCOUNT ON;  
                SELECT OgrenciID ,
                    OgrenciSeviyeID ,
                    DersHavuzuID ,
                    Numarasi ,
                    Adi ,
                    Soyadi ,
                    (Adi + ' ' + Soyadi) AS AdiSoyadi ,
                    DersKodu ,
                    DersAdi ,
                    DonemID ,
                    Donem1_DonemNotu ,
                    Donem2_DonemNotu ,
                    PuanOrtalamasi ,
                    Donem1_PuanOrtalamasi ,
                    Donem2_PuanOrtalamasi ,
                    Donem2_DonemNotu AS AktifDonemNotu ,
                    YetistirmeKursuNotu ,
                    YilSonuNotu ,
                    isnull(cast(YilSonuPuani as varchar(5)),'') as YilSonuPuani, 
                    YilsonuToplamAgirligi ,
                    [7] AS Sozlu1 ,
                    [8] AS Sozlu2 ,
                    [9] AS Sozlu3 ,
                    [10] AS Sozlu4 ,
                    [11] AS Sozlu5 ,
                    [12] AS Sozlu6 ,
                    isnull(cast(cast([1] as numeric(8,2)) as varchar(10)),'') AS Yazili1 ,
                    isnull(cast(cast([2] as numeric(8,2)) as varchar(10)),'') AS Yazili2 ,
                    isnull(cast(cast([3] as numeric(8,2)) as varchar(10)),'') AS Yazili3 ,
                    isnull(cast(cast([4] as numeric(8,2)) as varchar(10)),'') AS Yazili4 ,
                    isnull(cast(cast([5] as numeric(8,2)) as varchar(10)),'') AS Yazili5 ,
                    [6] AS Yazili6 ,
                    [13] AS Odev1 ,
                    [14] AS Odev2 ,
                    [15] AS Odev3 ,
                    [19] AS Proje1 ,
                    [20] AS Proje2 ,
                    [21] AS Proje3 ,
                    [35] AS Perf1 ,
                    [36] AS Perf2 ,
                    [37] AS Perf3 ,
                    [38] AS Perf4 ,
                    [39] AS Perf5 ,
                    [41] AS Perf1Odev ,
                    [42] AS Perf2Odev ,
                    [43] AS Perf3Odev ,
                    [44] AS Perf4Odev ,
                    [45] AS Perf5Odev ,
                    OdevAldi ,
                    ProjeAldi ,
                    OgrenciDersID ,
                    OgrenciDonemNotID ,  
                    PuanOrtalamasi ,
                    Hesaplandi ,
                    KanaatNotu ,
                    Sira ,
                    EgitimYilID ,
                    cast(HaftalikDersSaati as int) as HaftalikDersSaati  ,
                    Perf1OdevAldi ,
                    Perf2OdevAldi ,
                    Perf3OdevAldi ,
                    Perf4OdevAldi ,
                    Perf5OdevAldi ,
                    AltDers ,
                    YillikProjeAldi ,
                    YetistirmeKursunaGirecek ,
                    DersOgretmenAdi ,
                    DersOgretmenSoyadi ,
                    isPuanNotGirilsin ,
                    isPuanNotHesapDahil ,
                    AgirlikliYilSonuNotu ,
                    AgirlikliYilsonuPuani ,
                    PBYCOrtalama, 
                    DersSabitID,
                    K1,
                    K2,
                    K3,
                    K4,
                    K5,
                    K6,
                    K7,
                    K8,
                    K9,
                    K10,
                    K11,
                    K12,
                    K13,
                    K14,
                    K15,
                    K19,
                    K20,
                    K21,
                    K35,
                    K36,
                    K37,
                    K38,
                    K39,
                    K41,
                    K42,
                    K43,
                    K44,
                    K45
                FROM (SELECT 
                        YetistirmeKursuNotu ,
                        YilSonuNotu ,
                        YilSonuPuani ,
                        YilsonuToplamAgirligi ,
                        Hesaplandi ,
                        PuanOrtalamasi ,
                        PuanOrtalamasi AS Donem2_PuanOrtalamasi ,
                        Donem1_PuanOrtalamasi ,
                        ProjeAldi ,
                        SinifID ,
                        ODNB.DersHavuzuID ,
                        ODNB.OgrenciSeviyeID ,
                        ODNB.OgrenciDersID ,
                        OgrenciDonemNotID ,
                        Puan ,
                        SinavTanimID ,
                        Donem1_DonemNotu ,
                        OdevAldi ,
                        KanaatNotu ,
                        Donem2_DonemNotu ,
                        Numarasi ,
                        OgrenciID ,
                        Adi ,
                        Soyadi ,
                        DersKodu ,
                        DersAdi ,
                        DonemID ,
                        Sira ,
                        EgitimYilID ,
                        HaftalikDersSaati ,
                        Perf1OdevAldi ,
                        Perf2OdevAldi ,
                        Perf3OdevAldi ,
                        Perf4OdevAldi ,
                        Perf5OdevAldi ,
                        AltDers ,
                        ODNB.YillikProjeAldi ,
                        YetistirmeKursunaGirecek ,
                        DersSirasi = ISNULL(( SELECT Sira
                                              FROM ".$dbnamex."GNL_SinifDersleri SD
                                              WHERE SD.SinifID = ODNB.SinifID
                                                    AND SD.DersHavuzuID = ODNB.DersHavuzuID  ), 999) ,
                        DersOgretmenAdi ,
                        DersOgretmenSoyadi ,
                        isPuanNotGirilsin ,
                        isPuanNotHesapDahil ,
                        AgirlikliYilSonuNotu ,
                        AgirlikliYilsonuPuani ,
                        PBYCOrtalama, 
                        DersSabitID,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",1))  AS K1,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",2)) AS K2,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",3)) AS K3,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",4)) AS K4,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",5)) AS K5,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",6)) AS K6,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",7)) AS K7,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",8)) AS K8,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",9)) AS K9,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",10)) AS K10,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",11)) AS K11,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",12)) AS K12,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",13)) AS K13,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",14)) AS K14,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",15)) AS K15,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",19)) AS K19,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",20)) AS K20,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",21)) AS K21,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",35)) AS K35,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",36)) AS K36,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",37)) AS K37,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",38)) AS K38,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",39)) AS K39,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",41)) AS K41,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",42)) AS K42,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",43)) AS K43,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",44)) AS K44,
                        (Select ".$dbnamex."FNC_GNL_NotGirisKontrol(ODNB.SinifID,ODNB.DersHavuzuID,ODGT.OgrenciDersGrupTanimID,".$DonemID.",45)) AS K45
            
                FROM ".$dbnamex."OgrenciDersNotBilgileri_Donem".$DonemID." ODNB
                LEFT JOIN ".$dbnamex."GNL_OgrenciDersGruplari ODG ON ODG.OgrenciDersID = ODNB.OgrenciDersID
                LEFT JOIN ".$dbnamex."GNL_OgrenciDersGrupTanimlari ODGT ON ODGT.OgrenciDersGrupTanimID=ODG.OgrenciDersGrupTanimID AND ODG.OgrenciDersID = ODNB.OgrenciDersID
                WHERE isPuanNotGirilsin = 1
                ) p PIVOT
                ( MAX(Puan) FOR SinavTanimID IN (   [1], [2], [3], [4], [5], [6], [7], [8],
                                                    [9], [10], [11], [12], [13], [14], [15],
                                                    [19], [20], [21], [35], [36], [37], [38],
                                                    [39], [41], [42], [43], [44], [45] ) ) 
                AS pvt
                WHERE OgrenciSeviyeID = '".$findOgrenciseviyeIDValue."' AND
                    AltDers = 0
                ORDER BY DersSirasi, DersAdi ; 
             SET NOCOUNT OFF; 
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
     * @ kim kime mesaj gönderebilir. 
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function msjGonderilecekRoller($params = array()) {
        try {
           $cid = -1; // okii 
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            $KurumID= '00000000-0000-0000-0000-000000000000';
            if ((isset($params['KurumID']) && $params['KurumID'] != "")) {
                $KurumID = $params['KurumID'];
            } 
            $RolID= 0;
            if ((isset($params['RolID']) && $params['RolID'] != "")) {
                $RolID = $params['RolID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "  
            SET NOCOUNT ON;  
            declare @KurumID uniqueidentifier ,@defaulKurumID uniqueidentifier   ;  

            set @KurumID = '".$KurumID."' ; 
            set @defaulKurumID =  '00000000-0000-0000-0000-000000000000';   

            SELECT @KurumID = CASE 
                        WHEN (SELECT count(KurumID) as adet    
                                FROM [BILSANET_MOBILE].[dbo].[Mobile_MessageRolles] where KurumID = @KurumID ) =0 then @defaulKurumID
                        ELSE  @KurumID END ;  
             
            SELECT * FROM (           
            SELECT  
                NULL AS rolID,
                NULL AS sendRolID, 
                NULL AS KurumID,
                COALESCE(NULLIF(ax.[description],''),a.[description_eng]) AS RolAdi,
                1 as kontrol,
                0 as priority
            FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
            INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
            LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
            WHERE a.[main_group] = 1 and a.[first_group]  =9 and
                a.language_parent_id =0 
                UNION
            SELECT  
                nn.[rolID],
                [sendRolID], 
                [KurumID],
                 COALESCE(NULLIF(COALESCE(NULLIF(ax.RolAdi collate SQL_Latin1_General_CP1254_CI_AS,''''),ax.RolAdieng collate SQL_Latin1_General_CP1254_CI_AS),''''),rr.RolAdi) as RolAdi ,
                1 as kontrol,
                nn.priority
            FROM [BILSANET_MOBILE].[dbo].[Mobile_MessageRolles] nn 
            INNER JOIN ".$dbnamex."GNL_Roller rr ON rr.RolID = nn.sendRolID
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
            LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Roller_lng ax on (ax.language_parent_id = nn.[sendRolID] or ax.RolID = nn.[sendRolID] ) and  ax.language_id= lx.id  
                 
            WHERE  nn.[rolID] = ".$RolID." AND
                   nn.[KurumID] = @KurumID  
                   ) as dddd
            ORDER BY priority , sendRolID;
 
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
    
    /** 
     * @author Okan CIRAN
     * @ mesaj için okul listesi 
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function msjIcinOkulListesi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
             
            $SendrolID= 0;
            if ((isset($params['SendrolID']) && $params['SendrolID'] != "")) {
                $SendrolID = $params['SendrolID'];
            } 
            $RolID= 0;
            if ((isset($params['RolID']) && $params['RolID'] != "")) {
                $RolID = $params['RolID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            IF ($SendrolID == 8  &&  $SendrolID == 9) {$SendrolID =$RolID;  }
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "  
            SET NOCOUNT ON;  

            SELECT * FROM (
             SELECT      
                COALESCE(NULLIF(ax.[description],''),a.[description_eng]) AS aciklama, 	
                '00000000-0000-0000-0000-000000000000' AS ID, 
                NULL AS DersYiliID,
                1 AS kontrol 
            FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
            INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
            LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
            WHERE a.[main_group] = 1 and a.[first_group]  = 1 and
                a.language_parent_id =0 
            UNION  
            SELECT DISTINCT    
                COALESCE(NULLIF(COALESCE(NULLIF(golx.OkulAdi collate SQL_Latin1_General_CP1254_CI_AS,''''),golx.OkulAdieng collate SQL_Latin1_General_CP1254_CI_AS),''''),oo.OkulAdi) as aciklama, 	
                OKL.OkulID AS ID, 
                dy.DersYiliID,
                1 AS kontrol
            FROM ".$dbnamex."GNL_OkulKullanicilari OKL  
            INNER JOIN ".$dbnamex."GNL_OkulKullaniciRolleri OKR ON OKR.OkulKullaniciID = OKL.OkulKullaniciID 
            INNER JOIN ".$dbnamex."GNL_Roller R ON R.RolID = OKR.RolID
            INNER JOIN ".$dbnamex."[GNL_Okullar] oo ON oo.[OkulID] = okl.[OkulID] 
            INNER JOIN ".$dbnamex."[GNL_Kisiler] ki ON ki.KisiID = OKL.KisiID 
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.OkulID = OKL.OkulID AND DY.AktifMi =1 
            inner join ".$dbnamex."GNL_EgitimYillari EY ON EY.EgitimYilID = DY.EgitimYilID AND DY.AktifMi = 1
            LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue."  AND lx.deleted =0 AND lx.active =0
            LEFT JOIN BILSANET_MOBILE.dbo.Mobil_Okullar_Lng golx ON golx.OkulID = OKL.[OkulID] and golx.language_id = lx.id  
            WHERE lower(concat (ki.Adi collate SQL_Latin1_General_CP1254_CI_AS,' ',ki.Soyadi collate SQL_Latin1_General_CP1254_CI_AS)) != 'admin' AND 
                cast(getdate() AS date) between cast(dy.Donem1BaslangicTarihi AS date) AND cast(dy.Donem2BitisTarihi AS date) AND
                oo.KurumTurID < 500 AND 
                dy.EgitimYilID = (select max(EgitimYilID) FROM ".$dbnamex."GNL_DersYillari dyx WHERE dyx.OkulID = OKL.OkulID AND DY.AktifMi =1  ) and 
                OKR.RolID = ".$SendrolID."  
            ) AS ssss
            order by ID,UPPER(aciklama)
            
            SET NOCOUNT OFF;   
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
     * @ mesaj için okuldaki sınıf listesi 
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function msjIcinOkuldakiSinifListesi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
             
            $OkulID= 0;
            $addSQLWhere =NULL; 
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
                IF ($OkulID !='00000000-0000-0000-0000-000000000001' ){
                    $addSQLWhere =" DY.OkulID = '".$OkulID."' and  ";} 
            } 
            $SendrolID= 0;
            if ((isset($params['SendrolID']) && $params['SendrolID'] != "")) {
                $SendrolID = $params['SendrolID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "  
            SET NOCOUNT ON;  

            SELECT * FROM ( 
                SELECT 
                    NULL AS ID, 
                    NULL AS SinifKodu, 
                    'LÜTFEN SINIF SEÇİNİZ' AS aciklama,
                    -2 AS SeviyeID,
                    1 as kontrol
                FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
                INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                WHERE a.[main_group] = 1 and a.[first_group]  = 2 and
                    a.language_parent_id =0 
            UNION 
                SELECT  
                    '00000000-0000-0000-0000-000000000001' AS ID, 
                    NULL AS SinifKodu, 
                    COALESCE(NULLIF(ax.[description],''),a.[description_eng]) AS aciklama,
                    -1 AS SeviyeID,
                    1 as kontrol 
                FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
                INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                WHERE a.[main_group] = 1 and a.[first_group]  = 10 and
                    a.language_parent_id =0 AND
                    1 = ( SELECT count(1)  
					FROM BILSANET_SIVASGAZI.dbo.GNL_Siniflar SN 
					INNER JOIN BILSANET_SIVASGAZI.dbo.GNL_DersYillari DY ON DY.DersYiliID = SN.DersYiliID -- and DY.AktifMi =1  
					WHERE 
                                                ".$addSQLWhere."  
						SN.Sanal = 0 AND 
						cast(getdate() AS date) between cast(dy.Donem1BaslangicTarihi AS date) AND cast(dy.Donem2BitisTarihi AS date)
				  )
            UNION 
                SELECT
                    SN.SinifID as ID, 
                    SN.SinifKodu, 
                    SN.SinifAdi collate SQL_Latin1_General_CP1254_CI_AS as aciklama,
                    SN.SeviyeID,
                    1 as kontrol
                FROM ".$dbnamex."GNL_Siniflar SN 
                INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = SN.DersYiliID and DY.AktifMi =1 
                WHERE 
                    ".$addSQLWhere."
                    SN.Sanal = 0 AND 
                    dy.EgitimYilID = (select max(EgitimYilID) FROM ".$dbnamex."GNL_DersYillari dyx  where dyx.OkulID = DY.OkulID and DY.AktifMi =1  )   	
            ) as ssssd
            ORDER BY ID,SinifKodu,aciklama;
 
            
            SET NOCOUNT OFF;   
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
     * @ mesaj için ögrenci ya da veli listesi 
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function msjIcinSinifOgrenciVeliListesi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
             
            $SinifID= 0;
            $addSQLTum =NULL; 
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
                IF ($SinifID !='00000000-0000-0000-0000-000000000001' ){
                    $addSQLTum =" gos.SinifID = '".$SinifID."' and  ";} 
            } 
            $SendrolID= 0; 
            $addSQL ='SELECT distinct OgrenciID as ID, ogrenciadsoyad  AS aciklama , 0 AS kontrol from ( ';
            $orderSQL =' ORDER BY ogrenciadsoyad'; 
            if ((isset($params['SendrolID']) && $params['SendrolID'] != "")) {
                $SendrolID = $params['SendrolID']; 
                IF ($SendrolID == 8){
                    $addSQL ='SELECT distinct OgrenciID as ID, ogrenciadsoyad  collate SQL_Latin1_General_CP1254_CI_AS AS aciklama , 0 AS kontrol from ( ';
                    $orderSQL =' ORDER BY ogrenciadsoyad'; 
                 } ;
                 IF ($SendrolID == 9) 
                 {
                    $addSQL ='SELECT distinct YakinID as ID, veliadsoyad  collate SQL_Latin1_General_CP1254_CI_AS AS aciklama , 0 AS kontrol from ( ';
                    $orderSQL =' ORDER BY veliadsoyad'; 
                 } ;
            } 
            $RolID= 0; 
            if ((isset($params['RolID']) && $params['RolID'] != "")) {
                $RolID = $params['RolID']; 
            } 
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            $addWhereSQL =NULL;
            if ((isset($params['KisiId']) && $params['KisiId'] != "")) {
                $KisiId = $params['KisiId'];
                IF ($RolID ==8){
                    $addWhereSQL =" OKL.KisiID = '".$KisiId."' and ";  } 
                IF ($RolID ==9){
                    $addWhereSQL =" VELI.YakinID = '".$KisiId."' and ";  } 
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "   
            SET NOCOUNT ON; 
            ".$addSQL."
            SELECT   
                OKL.KisiID AS OgrenciID, 
                UPPER(CONCAT(ki.Adi collate SQL_Latin1_General_CP1254_CI_AS,' ',ki.Soyadi collate SQL_Latin1_General_CP1254_CI_AS)) AS ogrenciadsoyad ,
                VELI.YakinID ,
                UPPER(CONCAT(k.Adi collate SQL_Latin1_General_CP1254_CI_AS,' ',k.Soyadi collate SQL_Latin1_General_CP1254_CI_AS)) AS veliadsoyad ,
                0 as kontrol                
            FROM  ".$dbnamex."GNL_OkulKullanicilari OKL  
            INNER JOIN ".$dbnamex."GNL_OkulKullaniciRolleri OKR ON OKR.OkulKullaniciID = OKL.OkulKullaniciID 
            INNER JOIN ".$dbnamex."GNL_Roller R ON R.RolID = OKR.RolID
            inner join ".$dbnamex."[GNL_Okullar] oo ON oo.[OkulID] = okl.[OkulID] 
            inner join ".$dbnamex."[GNL_Kisiler] ki ON ki.KisiID = OKL.KisiID 
            inner join ".$dbnamex."GNL_DersYillari DY ON DY.OkulID = OKL.OkulID AND DY.AktifMi =1 
            inner join ".$dbnamex."GNL_OgrenciSeviyeleri gos ON gos.OgrenciID = OKL.KisiID 
            LEFT JOIN ".$dbnamex."GNL_OgrenciYakinlari VELI ON VELI.OgrenciID = OKL.KisiID
            LEFT JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = VELI.YakinID  
            WHERE  
                dy.EgitimYilID = (SELECT max(EgitimYilID) FROM ".$dbnamex."GNL_DersYillari dyx  where dyx.OkulID = DY.OkulID and DY.AktifMi =1) AND             
                ".$addSQLTum."
                ".$addWhereSQL."
                OKR.RolID = 8  
            ) as sss 
            ".$orderSQL." 
            SET NOCOUNT OFF;   
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
     * @ mesaj için ögretmen listesi 
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function msjIcinOgretmenListesi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }   
            
            $RolID= 0; 
            if ((isset($params['RolID']) && $params['RolID'] != "")) {
                $RolID = $params['RolID']; 
            } 
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            $addWhereSQL =NULL;
            if ((isset($params['KisiId']) && $params['KisiId'] != "")) {
                $KisiId = $params['KisiId'];
                IF ($RolID ==8){
                    $addWhereSQL =" WHERE os.OgrenciID = '".$KisiId."'  ";  } 
                IF ($RolID ==9){
                    $addWhereSQL =" WHERE VELI.YakinID = '".$KisiId."'  ";  } 
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
         
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "   
            SET NOCOUNT ON;  
            SELECT DISTINCT    
                '[ ' + dd.DersAdi  collate SQL_Latin1_General_CP1254_CI_AS+ ' ]  ' + K.Adi  collate SQL_Latin1_General_CP1254_CI_AS+ ' ' + K.Soyadi collate SQL_Latin1_General_CP1254_CI_AS AS aciklama, 
                 so.OgretmenID as ID, 
                 dd.DersAdi,
                 0 as kontrol    
            FROM ".$dbnamex."GNL_Siniflar gs
            INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri os ON gs.SinifID = os.SinifID 
            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri  so ON gs.SinifID = so.SinifID 
            INNER JOIN ".$dbnamex."OGT_Ogretmenler ogt ON so.OgretmenID = ogt.OgretmenID 
            INNER JOIN ".$dbnamex."GNL_Kisiler K ON ogt.OgretmenID = K.KisiID 
            INNER JOIN ".$dbnamex."GNL_DersHavuzlari dh ON so.DersHavuzuID = dh.DersHavuzuID 
            INNER JOIN ".$dbnamex."GNL_Dersler dd ON dh.DersID = dd.DersID
            LEFT JOIN ".$dbnamex."GNL_OgrenciYakinlari VELI ON VELI.OgrenciID = os.OgrenciID
            LEFT JOIN ".$dbnamex."GNL_Kisiler KV ON KV.KisiID = VELI.YakinID   
            ".$addWhereSQL."
            ORDER BY 
                '[ ' + dd.DersAdi collate SQL_Latin1_General_CP1254_CI_AS + ' ]  ' + K.Adi collate SQL_Latin1_General_CP1254_CI_AS + ' ' + K.Soyadi collate SQL_Latin1_General_CP1254_CI_AS;   
            SET NOCOUNT OFF;   
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
     * @ mesaj için personel listesi 
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function msjIcinPersonelListesi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
              
            $SendrolID= 0;
            if ((isset($params['SendrolID']) && $params['SendrolID'] != "")) {
                $SendrolID = $params['SendrolID'];
            }  
            
            $OkulID= 0;
            $addSQLWhere =NULL; 
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
                IF ($OkulID !='00000000-0000-0000-0000-000000000001' ){
                    $addSQLWhere =" DY.OkulID = '".$OkulID."' and  ";} 
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "   
            SET NOCOUNT ON; 
           
            SELECT distinct  
                OKL.KisiID as ID , 
                upper(concat (ki.Adi collate SQL_Latin1_General_CP1254_CI_AS,' ',ki.Soyadi collate SQL_Latin1_General_CP1254_CI_AS)) as aciklama, 
                0 as kontrol
            FROM  ".$dbnamex."GNL_OkulKullanicilari OKL  
            INNER JOIN ".$dbnamex."GNL_OkulKullaniciRolleri OKR ON OKR.OkulKullaniciID = OKL.OkulKullaniciID 
            INNER JOIN ".$dbnamex."GNL_Roller R ON R.RolID = OKR.RolID
            INNER JOIN ".$dbnamex."[GNL_Okullar] oo ON oo.[OkulID] = okl.OkulID 
            INNER JOIN ".$dbnamex."[GNL_Kisiler] ki on ki.KisiID = OKL.KisiID 
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.OkulID = OKL.OkulID  and DY.AktifMi =1  
             where  
              /*  dy.EgitimYilID = (SELECT max(EgitimYilID) FROM  GNL_DersYillari dyx  where dyx.OkulID = DY.OkulID and DY.AktifMi =1) AND  */ 
              cast(getdate() AS date) between cast(dy.Donem1BaslangicTarihi AS date) AND cast(dy.Donem2BitisTarihi AS date) AND
                ".$addSQLWhere."
                OKR.RolID = ".$SendrolID."  
            ORDER BY 
                upper(concat (ki.Adi collate SQL_Latin1_General_CP1254_CI_AS,' ',ki.Soyadi collate SQL_Latin1_General_CP1254_CI_AS) ) ;
 
            SET NOCOUNT OFF;   
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
     * @ mesaj tipleri 
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function mesajTipleri($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }      
            
            $RolID= 0;
            $addSQLWhere =NULL;
            if ((isset($params['RolID']) && $params['RolID'] != "")) {
                $RolID = $params['RolID'];  
                IF (($RolID == 8 ) ||  ($RolID == 9 )) {  $addSQLWhere = " WHERE sdsdsd.MesajTipID < 2 ";   }
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
         
            $sql = "  
             
            SET NOCOUNT ON;  
                SELECT 
                    sdsdsd.MesajTipID,
                    COALESCE(NULLIF(mtlx.[Aciklama],''),mtl.[AciklamaEng]) AS Aciklama 
                FROM (
                    SELECT  
                        mtl.MesajTipID  
                    FROM BILSANET_MOBILE.[dbo].[Mobile_MesajTipleri_lng]  mtl  
                    WHERE mtl.deleted =0 and mtl.active =0 and mtl.language_parent_id =0 AND
                        mtl.MesajTipID =0 
                union 
                    SELECT  
                        mt.MesajTipID  
                    FROM ".$dbnamex."[MSJ_MesajTipleri] mt
                ) as sdsdsd 
                INNER JOIN BILSANET_MOBILE.[dbo].[Mobile_MesajTipleri_lng] mtl ON mtl.MesajTipID= sdsdsd.MesajTipID AND mtl.deleted =0 AND mtl.active =0 AND mtl.language_parent_id =0 
                INNER JOIN BILSANET_MOBILE.[dbo].[Mobile_MesajTipleri_lng] mtlx ON mtlx.deleted =0 AND mtlx.active =0 AND (mtlx.MesajTipID =sdsdsd.MesajTipID) AND mtlx.language_id = ".$languageIdValue."                
                ".$addSQLWhere."  
                ORDER BY MesajTipID;
 
            SET NOCOUNT OFF;  
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
     * @ sınavdaki derslerin listesi
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenSinavDersleriListesi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
             
            $SinavID= 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavID']) && $params['SinavID'] != "")) {
                $SinavID = $params['SinavID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $sql = "   
                SET NOCOUNT ON;  
                IF OBJECT_ID('tempdb..#okiogrsinavderslistesi') IS NOT NULL DROP TABLE #okiogrsinavderslistesi; 

                declare  @SinavID1 UNIQUEIDENTIFIER; 

                set @SinavID1 = '".$SinavID."' ; /* 'F50FFA1C-2532-48C6-955C-6604092A8189';-- 'E8A62887-2769-41A3-A1C4-D6F455040677'; */ 

                SELECT 
                    DISTINCT SinavDersID  
                INTO #okiogrsinavderslistesi 
                FROM ".$dbnamex."SNV_SinavSorulari 
                WHERE 
                    SinavID = @SinavID1;
                
                SELECT * FROM ( 
                    SELECT  
                        '00000000-0000-0000-0000-000000000000' as SinavID,
                        NULL as BolumSabitID,
                        NULL as SinavTurID,
                        NULL as BolumKodu,
                        NULL as BolumAdi,
                        -1 as BolumKategoriID,
                        COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng] collate SQL_Latin1_General_CP1254_CI_AS)  as BolumKategoriAdi,
                        NULL as SinavDersSabitID,
                        -1 as Sira, 
                        NULL as DersSabitID, 
                        NULL as DersAdi, 
                        NULL as DersAciklama,
                        '00000000-0000-0000-0000-000000000000'SinavDersID,
                        '00000000-0000-0000-0000-000000000000'SinavKategoriID ,
                        NULL as DersSoruSayisi  
                    FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
                    INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                    WHERE a.[main_group] = 1 and a.[first_group]  = 9 and
                        a.language_parent_id =0 
                union 
                    SELECT
                        SNV.SinavID,
                        BS.BolumSabitID,
                        BS.SinavTurID,
                        BS.BolumKodu,
                        BS.BolumAdi,
                        BK.BolumKategoriID,
                        BK.BolumKategoriAdi,
                        SDS.SinavDersSabitID,
                        SDS.Sira, 
                        DS.DersSabitID, 
                        DS.DersSabitAdi as DersAdi, 
                        DS.DersSabitAdi as DersAciklama,
                        SD.SinavDersID,
                        SD.SinavKategoriID ,
                        ISNULL(CASE WHEN SD.SinavDersID IS NULL THEN SDS.SoruSayisi ELSE SD.DersSoruSayisi+(ISNULL(CASE WHEN SD.AcikUcluSoruSayisi IS NULL THEN 0 ELSE SD.AcikUcluSoruSayisi END,0)) END,0) AS DersSoruSayisi
                    FROM ".$dbnamex."SNV_Sinavlar SNV
                    INNER JOIN ".$dbnamex."SNV_BolumSabitleri BS ON BS.SinavTurID = SNV.SinavTurID
                    INNER JOIN ".$dbnamex."SNV_BolumKategorileri BK ON BK.BolumSabitID = BS.BolumSabitID
                    INNER JOIN ".$dbnamex."SNV_SinavDersSabitleri SDS ON SDS.BolumKategoriID = BK.BolumKategoriID 
                    INNER JOIN ".$dbnamex."GNL_DersSabitleri DS ON DS.DersSabitID = SDS.DersSabitID
                    INNER JOIN ".$dbnamex."SNV_SinavKategorileri SK ON SK.SinavID = SNV.SinavID AND SK.BolumKategoriID = BK.BolumKategoriID
                    INNER JOIN ".$dbnamex."SNV_SinavDersleri SD ON SD.SinavKategoriID = SK.SinavKategoriID AND SD.SinavDersSabitID = SDS.SinavDersSabitID
                    INNER JOIN #okiogrsinavderslistesi TSD ON TSD.SinavDersID = SD.SinavDersID  
                    WHERE 
                        SNV.SinavID = @SinavID1
                    ) as sssc    
                ORDER BY 
                     BolumKategoriID, Sira ;  
		 
                IF OBJECT_ID('tempdb..#okiogrsinavderslistesi') IS NOT NULL DROP TABLE #okiogrsinavderslistesi; 
                SET NOCOUNT OFF;  
 
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
     * @ ögretmenin yaptıgı sına girenögrenciler listesi
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenSinavaGirenSubeler($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
             
            $SinavID= 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavID']) && $params['SinavID'] != "")) {
                $SinavID = $params['SinavID'];
            } 
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgretmenID']) && $params['OgretmenID'] != "")) {
                $KisiID = $params['OgretmenID'];
            } 
            $OkulID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
          // 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "   
                SET NOCOUNT ON;  
                 SELECT * FROM  (  
                    SELECT  
                        NULL as IlAdi,
                        NULL as IlceAdi,
                        NULL as OkulAdi,
                        NULL as MEBKodu,
                        '00000000-0000-0000-0000-000000000000' as SinavOkulID,
                        '00000000-0000-0000-0000-000000000000' as OkulID,
                        COALESCE(NULLIF(ax.[description] collate SQL_Latin1_General_CP1254_CI_AS,''),a.[description_eng] collate SQL_Latin1_General_CP1254_CI_AS)  as SinifKodu,
                        NULL as OgrenciSayisi,
                        NULL as OkulOgrenciSayisi,
                        '00000000-0000-0000-0000-000000000000' as DersYiliID,
                        '00000000-0000-0000-0000-000000000000' as SinifID  
                    FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
                    INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                    WHERE a.[main_group] = 1 and a.[first_group]  = 7 and
                        a.language_parent_id =0 
                UNION 
                SELECT    
                    GIl.IlAdi,
                    GIlce.IlceAdi,
                    GNLO.OkulAdi,
                    GNLO.MEBKodu,
                    SNVO.SinavOkulID,
                    GNLO.OkulID,
                    isnull(SinifKodu, '-') SinifKodu,
                    CASE WHEN SinifKodu is null THEN 0 ELSE Count(*) END as OgrenciSayisi,
                    (select count(SinavOkulID) FROM ".$dbnamex."SNV_SinavOgrencileri S2 WHERE S2.SinavOkulID=SNVO.SinavOkulID) as OkulOgrenciSayisi,
                    ISNULL((select TOP 1 CAST(DersYiliID AS NVARCHAR(36))
						 FROM ".$dbnamex."SNV_SinavOgrencileri S3
						INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON S3.OgrenciSeviyeID = OS.OgrenciSeviyeID
						INNER JOIN ".$dbnamex."GNL_Siniflar SNF ON OS.SinifID = SNF.SinifID
						WHERE 
                                                    SNF.SinifKodu = SOGR.SinifKodu 
                                                    COLLATE Turkish_CI_AS
                                                    AND S3.SinavOkulID = SNVO.SinavOkulID),'-') as DersYiliID,
                    ISNULL((select TOP 1 CAST(SNF.SinifID AS NVARCHAR(36)) FROM ".$dbnamex."SNV_SinavOgrencileri S3
						INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON S3.OgrenciSeviyeID = OS.OgrenciSeviyeID
						INNER JOIN ".$dbnamex."GNL_Siniflar SNF ON OS.SinifID = SNF.SinifID
						WHERE SNF.SinifKodu = SOGR.SinifKodu COLLATE Turkish_CI_AS
                                                    AND S3.SinavOkulID = SNVO.SinavOkulID),'-') as SinifID

                FROM ".$dbnamex."SNV_SinavOkullari SNVO  
                INNER JOIN ".$dbnamex."GNL_OkulKullanicilari OK ON OK.OkulID = SNVO.OkulID AND OK.KisiID = '".$KisiID."'    /* 'CF822218-8FD1-4B95-A4C0-9A3113332B4F'--@KisiID -- ogretmen  */
                            AND SNVO.OkulID = '".$OkulID."' /* 'C79927D0-B3AD-40CD-80CF-DCA7D841FDBD' */ 
                INNER JOIN ".$dbnamex."GNL_Okullar GNLO ON GNLO.OkulID=SNVO.OkulID 
                LEFT JOIN ".$dbnamex."GNL_Adresler GA ON (GA.AdresID = GNLO.AdresID) 
                LEFT JOIN ".$dbnamex."GNL_Ilceler GIlce ON (GIlce.IlceID = GA.IlceID) 
                LEFT JOIN ".$dbnamex."GNL_Iller GIl ON (GIl.IlID = GIlce.IlID) 
                LEFT JOIN ".$dbnamex."SNV_SinavOgrencileri SOGR ON SOGR.SinavOkulID=SNVO.SinavOkulID 
                 WHERE SNVO.SinavID= '".$SinavID."'  /* 'C6C84DB4-BA8C-40EB-AD36-9CFBF6DEF89B' */ 
                GROUP BY GIl.IlAdi,GIlce.IlceAdi,GNLO.OkulAdi,GNLO.MEBKodu,SNVO.SinavOkulID,GNLO.OkulID,SinifKodu  
              /*    ORDER BY GIl.IlAdi,GIlce.IlceAdi,GNLO.OkulAdi,SinifKodu */ 
              ) as sssss
                ORDER BY sssss.IlAdi,sssss.IlceAdi,sssss.OkulAdi,sssss.SinifKodu

                SET NOCOUNT OFF;  
 
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
     * @ yönetici içinögretmen ödev listesi
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function kyOgretmenOdevListeleri($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            
            $OkulID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OkulID']) && $params['OkulID'] != "")) {
                $OkulID = $params['OkulID'];
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
          // 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "   
                SET NOCOUNT ON;  
 
                declare @DersYiliID UNIQUEIDENTIFIER,
                        @OkulID UNIQUEIDENTIFIER,
                        @SeviyeID INT,
                        @SinifID UNIQUEIDENTIFIER ; 
 
               /* set @DersYiliID = '0E210F64-D491-4027-BEE9-C9BD1E8699EF';*/
                set @OkulID = '".$OkulID."';
                set @SeviyeID = NULL;
                set @SinifID =NULL;
 
              
                SELECT 
                        O.OgretmenID,
                        K.Adi  collate SQL_Latin1_General_CP1254_CI_AS+ ' ' + K.Soyadi collate SQL_Latin1_General_CP1254_CI_AS AS AdiSoyadi,
                        B.Brans,
                        COUNT(DISTINCT OT.OdevTanimID) AS OdevSayisi,
                        COUNT(DISTINCT OO.OgrenciOdevID) AS OgrenciSayisi,
                        COUNT(DISTINCT (CASE WHEN OO.OgrenciGordu = 1 OR OdevOnayID = 2 THEN OO.OgrenciOdevID ELSE NULL END)) AS GorenSayisi,
                        COUNT(DISTINCT (CASE WHEN OO.OgrenciOnay = 1 OR OdevOnayID = 2 THEN OO.OgrenciOdevID ELSE NULL END)) AS YapanSayisi,
                        COUNT(DISTINCT (CASE WHEN OdevOnayID = 2 THEN OO.OgrenciOdevID ELSE NULL END)) AS OnaySayisi
                FROM ".$dbnamex."OGT_Ogretmenler O
                INNER JOIN ".$dbnamex."GNL_Kisiler K ON (K.KisiID = O.OgretmenID)
                INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri SO ON (SO.OgretmenID = O.OgretmenID)
                INNER JOIN ".$dbnamex."GNL_Siniflar S ON (S.SinifID = SO.SinifID)
                INNER JOIN ".$dbnamex."OGT_Branslar B ON (B.BransID = O.BransID) 
                INNER JOIN ".$dbnamex."GNL_DersYillari DY ON S.DersYiliID =  DY.DersYiliID  and dy.OkulID = @OkulID  AND DY.AktifMi =1  
                LEFT OUTER JOIN ".$dbnamex."ODV_OdevTanimlari OT ON (OT.OgretmenID = O.OgretmenID)
                LEFT OUTER JOIN ".$dbnamex."ODV_OgrenciOdevleri OO ON (OO.OdevTanimID = OT.OdevTanimID)
                WHERE
		(
                    (OT.OdevTanimID IS NULL) OR
                    (OT.OdevTanimID IN 
                        (
                            SELECT 
                                INFO_OT.OdevTanimID
                            FROM ".$dbnamex."ODV_OdevTanimlari INFO_OT
                            INNER JOIN ".$dbnamex."GNL_SinifDersleri INFO_SD ON (INFO_SD.SinifDersID = INFO_OT.SinifDersID)
                            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri INFO_SO ON (INFO_SO.SinifID = INFO_SD.SinifID AND INFO_SO.DersHavuzuID = INFO_SD.DersHavuzuID)
                            INNER JOIN ".$dbnamex."GNL_Siniflar INFO_S ON (INFO_S.SinifID = INFO_SD.SinifID)
                            INNER JOIN ".$dbnamex."GNL_DersHavuzlari INFO_DH ON (INFO_DH.DersHavuzuID = INFO_SD.DersHavuzuID)
                            INNER JOIN ".$dbnamex."GNL_Dersler INFO_D ON (INFO_D.DersID = INFO_DH.DersID)
                            WHERE
                                INFO_OT.OgretmenID = O.OgretmenID AND 
                                INFO_S.DersYiliID = S.DersYiliID AND
                                INFO_S.SeviyeID = S.SeviyeID AND
                                INFO_S.SinifID=S.SinifID 
						
                        )
                    )
		) AND 
		/*  S.DersYiliID = @DersYiliID AND */ 
                DY.EgitimYilID = (SELECT max(EgitimYilID) FROM ".$dbnamex."GNL_DersYillari dyx  where dyx.OkulID = dy.OkulID and dyx.AktifMi =1) AND 
		((@SeviyeID IS NOT NULL AND S.SeviyeID = @SeviyeID) OR @SeviyeID IS NULL OR @SeviyeID = 0) AND
                ((@SinifID IS NOT NULL AND S.SinifID = @SinifID) OR @SinifID IS NULL ) 
                       GROUP BY
                               O.OgretmenID,
                               K.Adi,
                               K.Soyadi,
                               B.Brans
                       ORDER BY 
                           AdiSoyadi  /*  K.Adi + ' ' + K.Soyadi */ 
	
                SET NOCOUNT OFF  
 
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
     * @ders ogretmen listesi 
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrenciVeliIcinOgretmenListesi($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }   
             
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';  
            if ((isset($params['KisiId']) && $params['KisiId'] != "")) {
                $KisiId = $params['KisiId']; 
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
         
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "   
            SET NOCOUNT ON;  
            SELECT DISTINCT    
                 K.Adi  collate SQL_Latin1_General_CP1254_CI_AS+ ' ' + K.Soyadi collate SQL_Latin1_General_CP1254_CI_AS AS aciklama, 
                 so.OgretmenID  , 
                 dd.DersAdi,
                 0 as kontrol    
            FROM ".$dbnamex."GNL_Siniflar gs
            INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri os ON gs.SinifID = os.SinifID 
            INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri  so ON gs.SinifID = so.SinifID 
            INNER JOIN ".$dbnamex."OGT_Ogretmenler ogt ON so.OgretmenID = ogt.OgretmenID 
            INNER JOIN ".$dbnamex."GNL_Kisiler K ON ogt.OgretmenID = K.KisiID 
            INNER JOIN ".$dbnamex."GNL_DersHavuzlari dh ON so.DersHavuzuID = dh.DersHavuzuID 
            INNER JOIN ".$dbnamex."GNL_Dersler dd ON dh.DersID = dd.DersID 
            WHERE os.OgrenciID = '".$KisiId."' 
            ORDER BY 
                 K.Adi  collate SQL_Latin1_General_CP1254_CI_AS+ ' ' + K.Soyadi collate SQL_Latin1_General_CP1254_CI_AS;   
            SET NOCOUNT OFF;   
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
     * @ ogrencinin aldıgı notlar
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrencininAldigiNotlar($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }   
             
            $KisiID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';  
            if ((isset($params['KisiID']) && $params['KisiID'] != "")) {
                $KisiID = $params['KisiID']; 
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            $DonemID = 1;
            if (isset($params['DonemID']) && $params['DonemID'] != "") {
                $DonemID = $params['DonemID'];
            } 
            $findOgrenciseviyeIDValue= null ; 
            $findOgrenciseviyeID = $this->findOgrenciseviyeID(
                            array( 'KisiID' =>$KisiID,  'Cid' =>$cid,'Did' =>$did, ));
            if (\Utill\Dal\Helper::haveRecord($findOgrenciseviyeID)) {
                $findOgrenciseviyeIDValue = $findOgrenciseviyeID ['resultSet'][0]['OgrenciseviyeID'];
            }  
         
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "   
            SET NOCOUNT ON;   
	 
            DECLARE  
                @OgrenciSeviyeID UNIQUEIDENTIFIER,
                @DonemID int; 
             
            set @OgrenciSeviyeID ='".$findOgrenciseviyeIDValue."';
            set @DonemID =".$DonemID.";
	

            
            SELECT  
                SINAV.SinavAciklamasi collate SQL_Latin1_General_CP1254_CI_AS ,
                cast(OP.Puan as numeric(18,2)) as Puan
            FROM ".$dbnamex."GNL_OgrenciSeviyeleri OS
            INNER JOIN ".$dbnamex."GNL_Siniflar S ON S.SinifID = OS.SinifID
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = S.DersYiliID
            INNER JOIN ".$dbnamex."GNL_Okullar OK ON OK.OkulID = DY.OkulID
            INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OkulID = OK.OkulID  AND OOB.OgrenciID = OS.OgrenciID
            INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = Os.OgrenciID
            INNER JOIN ".$dbnamex."SNV_SinavOgrencileri SO ON SO.OgrenciSeviyeID = OS.OgrenciSeviyeID
            INNER JOIN ".$dbnamex."OD_OgrenciPuanlari OP ON OP.SinavOgrenciID = SO.SinavOgrenciID
            INNER JOIN ".$dbnamex."SNV_SinavSiniflari SS ON SS.SinavSinifID = SO.SinavSinifID
            INNER JOIN ".$dbnamex."SNV_Sinavlar SINAV ON SINAV.SinavID = SS.SinavID
                                                     AND SinavTurID IN ( 300, 301 )
            WHERE SINAV.isOgrenciVeliSinavVisible = 1 AND
                OS.OgrenciSeviyeID = @OgrenciSeviyeID AND
                SINAV.NotDonemID = @DonemID
            ORDER BY SinavAciklamasi;
            
            SET NOCOUNT OFF;   
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
     * @ ogrencinin aldıgı  notlar sınav bazlı
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrencilerinAldigiNotlarSinavBazli($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }   
             
            $SinavID =  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';  
            if ((isset($params['SinavID']) && $params['SinavID'] != "")) {
                $SinavID = $params['SinavID']; 
            }  
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            }  
         
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "   
              
            SET NOCOUNT ON;   
	 
            DECLARE  
                @SinavID UNIQUEIDENTIFIER;  
            
            set @SinavID='".$SinavID."';
           
           
            SELECT
                oob.Numarasi ,
                concat(k.Adi collate SQL_Latin1_General_CP1254_CI_AS ,' ',k.Soyadi collate SQL_Latin1_General_CP1254_CI_AS) as adsoyad,
                SINAV.SinavAciklamasi  collate SQL_Latin1_General_CP1254_CI_AS,
                cast(OP.Puan as numeric(10,2)) as Puan
            FROM ".$dbnamex."GNL_OgrenciSeviyeleri OS
            INNER JOIN ".$dbnamex."GNL_Siniflar S ON S.SinifID = OS.SinifID
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = S.DersYiliID
            INNER JOIN ".$dbnamex."GNL_Okullar OK ON OK.OkulID = DY.OkulID
            INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OkulID = OK.OkulID  AND OOB.OgrenciID = OS.OgrenciID
            INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = Os.OgrenciID
            INNER JOIN ".$dbnamex."SNV_SinavOgrencileri SO ON SO.OgrenciSeviyeID = OS.OgrenciSeviyeID
            INNER JOIN ".$dbnamex."OD_OgrenciPuanlari OP ON OP.SinavOgrenciID = SO.SinavOgrenciID
            INNER JOIN ".$dbnamex."SNV_SinavSiniflari SS ON SS.SinavSinifID = SO.SinavSinifID
            INNER JOIN ".$dbnamex."SNV_Sinavlar SINAV ON SINAV.SinavID = SS.SinavID
                                                     AND SinavTurID IN ( 300, 301 )
            WHERE SINAV.isOgrenciVeliSinavVisible = 1 AND  
                    SS.SinavID =@SinavID
            ORDER BY adsoyad,SinavAciklamasi;
            
            SET NOCOUNT OFF;     
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
     * @ sinav sorulari
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogretmenSinavSorulariKDK($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
             
            $SinavDersID=  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavDersID']) && $params['SinavDersID'] != "")) {
                $SinavDersID = $params['SinavDersID'];
            } 
            $SinavOgrenciID=  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavOgrenciID']) && $params['SinavOgrenciID'] != "")) {
                $SinavOgrenciID = $params['SinavOgrenciID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
             
            $sql = "    
                    SET NOCOUNT ON;  

                    declare @SinavDersID1 UNIQUEIDENTIFIER ,@SinavOgrenciID UNIQUEIDENTIFIER; 
                    set @SinavDersID1 = '".$SinavDersID."';  
                    set @SinavOgrenciID = '".$SinavOgrenciID."'; 

                    SELECT
                        SKS.SinavKitapcikSoruID,
                        SKS.SinavKitapcikID,
                        SKS.SinavSoruID,
                        SKS.Sira,
                        cast(SS.SoruPuani as numeric(8,2)) as SoruPuani, 
                        '' AS OgrenciSoruPuani,
                        SORU.SoruTurID,
                        SD.SinavDersID,
                        SKTP.KitapcikTurID,
                        SO.SinavOgrenciID,
                        SOSC.SinavOgrenciSoruCevapID 
                    FROM ".$dbnamex."SNV_SinavKitapcikSorulari SKS
                    INNER JOIN ".$dbnamex."SNV_SinavKitapciklari SKTP ON SKS.SinavKitapcikID = SKTP.SinavKitapcikID
                    INNER JOIN ".$dbnamex."SNV_SinavSorulari SS ON SS.SinavSoruID = SKS.SinavSoruID
                    INNER JOIN ".$dbnamex."SB_Sorular SORU ON SORU.SoruID = SS.SoruID
                    INNER JOIN ".$dbnamex."SNV_SinavDersleri SD ON SD.SinavDersID = SS.SinavDersID 
                    INNER JOIN ".$dbnamex."SNV_SinavKategorileri SK ON SK.SinavKategoriID = SD.SinavKategoriID
                    LEFT JOIN ".$dbnamex."SNV_SinavOgrenciSoruCevaplari SOSC ON SOSC.SinavSoruID = SS.SinavSoruID 
                    LEFT JOIN ".$dbnamex."SNV_SinavOgrencileri SO ON SOSC.SinavOgrenciID = SO.SinavOgrenciID  
                    WHERE 
                            SS.SinavDersID = @SinavDersID1 AND 
                            SO.SinavOgrenciID = @SinavOgrenciID
                    ORDER BY 
                            SK.BolumKategoriID, 
                            SD.SinavDersSabitID, 
                            SKS.Sira; 

                SET NOCOUNT OFF;  
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
     * @author Okan CIRAN  -- kullanılmıyor
     * @ ogrenci odev detayını actıysa odev i okudu olarak isaretliyoruz.
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function ogrenciOdeviGordu($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();

            $OgrenciOdevID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciOdevID']) && $params['OgrenciOdevID'] != "")) {
                $OgrenciOdevID = $params['OgrenciOdevID'];
            }  
            
            $sql = "  
            SET NOCOUNT ON;  
 
  		exec ".$dbnamex."PRC_ODV_OgrenciOdevleri_SetGordu 
                        @OgrenciOdevID='".$OgrenciOdevID."'; 
	 
            SET NOCOUNT OFF;   
                "; 
            $statement = $pdo->prepare($sql); 
       //    $result = $statement->execute();
             $insertID =1;
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
     * @author Okan CIRAN  -- kullanılmıyor
     * @ ogretmen ogrencinin odevini onaylıyor
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function ogretmenOgrenciOdevOnay($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();

            $OgrenciOdevID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['OgrenciOdevID']) && $params['OgrenciOdevID'] != "")) {
                $OgrenciOdevID = $params['OgrenciOdevID'];
            }  
            $OdevOnayID= 0;
            if ((isset($params['OdevOnayID']) && $params['OdevOnayID'] != "")) {
                $OdevOnayID = $params['OdevOnayID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
 
  		exec ".$dbnamex."PRC_ODV_OgrenciOdevleri_SetGordu 
                        @OgrenciOdevID='".$OgrenciOdevID."'; 
                            okii..

                exec ".$dbnamex."PRC_ODV_OgrenciOdevleri_DegerlendirmeSave 
                        @OgrenciOdevID='".$OgrenciOdevID."',
                        @OgretmenDegerlendirme='&nbsp;',
                        @OdevOnayID=".$OdevOnayID."; 
	 
            SET NOCOUNT OFF;   
                "; 
            $statement = $pdo->prepare($sql); 
       //    $result = $statement->execute();
             $insertID =1;
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
     * @ odev onay tipleri
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function odevOnayTipleri($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
             
            $SinavDersID=  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavDersID']) && $params['SinavDersID'] != "")) {
                $SinavDersID = $params['SinavDersID'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
          
            $sql = "   
                    SET NOCOUNT ON;  
 

                    SELECT  
                        [OdevOnayID],
                        [OdevOnayı] as OdevOnayi
                    FROM ".$dbnamex."[ODV_OdevOnayTipleri] 
                    ORDER BY 
                           OdevOnayı; 

                SET NOCOUNT OFF;  
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
     * @ toplu Ogrenci Cevap
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function topluOgrenciCevap($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
             
            $SinavOkulID=  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavOkulID']) && $params['SinavOkulID'] != "")) {
                $SinavOkulID = $params['SinavOkulID'];
            } 
            $SinifKodu=  'CCCCC';
            if ((isset($params['SinifKodu']) && $params['SinifKodu'] != "")) {
                $SinifKodu = $params['SinifKodu'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
            
            $sql = "    
                SET NOCOUNT ON;   
                    
                declare 
                @sinavOkulID AS UNIQUEIDENTIFIER ,
                @SinifKodu AS NVARCHAR(20) = ''  collate SQL_Latin1_General_CP1254_CI_AS,
                @MyFields AS NVARCHAR(MAX)=''  collate SQL_Latin1_General_CP1254_CI_AS ,
                @NumarayaGoreSirala AS BIT = 1; 
                DECLARE @SQL NVARCHAR(MAX);

                set @sinavOkulID = '".$SinavOkulID."' ;  
                set @SinifKodu ='".$SinifKodu."';

                SELECT  
                    ROW_NUMBER() OVER(ORDER BY SOGR.OgrenciNumarasi) AS SiraNo, 
                    SOGR.SinavKitapcikID,
                    SK.KitapcikTurID,
                    SK.KitapcikAciklamasi,
                    SOGR.SinifKodu,
                    SNF.SinifID,
                    SOGR.SinavOgrenciID,
                    SOGR.SinavSinifID, 
                    SOGR.OgrenciSeviyeID,
                    OS.OgrenciID,
                    SOGR.SinavOkulID,
                    SOGR.TelafiSinavinaGirecekMi,
                    OOB.Numarasi AS OgrenciNumarasi,
                    K.KisiID,
                    K.Adi,
                    K.Soyadi,
                    K.Adi  collate SQL_Latin1_General_CP1254_CI_AS+ ' ' + K.Soyadi collate SQL_Latin1_General_CP1254_CI_AS AS AdiSoyadi,
                    ( SELECT STUFF(( SELECT ', ' + CONVERT(VARCHAR, KISI.Adi collate SQL_Latin1_General_CP1254_CI_AS) + ' '
                                      + CONVERT(VARCHAR, KISI.Soyadi collate SQL_Latin1_General_CP1254_CI_AS)
                                   FROM ".$dbnamex."GNL_Siniflar SNFO 
                                   INNER JOIN ".$dbnamex."GNL_SinifOgretmenleri SO ON SO.SinifID=SNFO.SinifID 
                                   INNER JOIN ".$dbnamex."GNL_Kisiler KISI ON KISI.KisiID=SO.OgretmenID                                               
                                   WHERE 
                                        SO.DersHavuzuID=SNV.YaziliStsSinavDersiDersHavuzuID AND 
                                        SO.OgretmenTurID=4 AND  
                                        SNFO.SinifID =SNF.SinifID 
                                 FOR
                                   XML PATH('')
                                 ), 1, 1, '')
                                           ) AS OgretmenAdiSoyadi,
                    K.TCKimlikNo,
                    '' AS isOgrenciDisaridan, 
                    0 AS TOPLAM_PUAN_1,
                    0 AS TOPLAM_PUAN_2,
                    0 AS NOTU,
                    0 AS TPS
               FROM ".$dbnamex."SNV_SinavOgrencileri SOGR
               INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON SOGR.OgrenciSeviyeID = OS.OgrenciSeviyeID
               INNER JOIN ".$dbnamex."GNL_Siniflar SNF ON OS.SinifID = SNF.SinifID
               INNER JOIN ".$dbnamex."GNL_DersYillari DY ON SNF.DersYiliID = DY.DersYiliID
               INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON DY.OkulID = OOB.OkulID AND OOB.OgrenciID = OS.OgrenciID
               INNER JOIN ".$dbnamex."SNV_SinavKitapciklari SK ON SOGR.SinavKitapcikID = SK.SinavKitapcikID
               INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = OS.OgrenciID
               INNER JOIN ".$dbnamex."SNV_Sinavlar SNV ON SNV.SinavID=SK.SinavID
               WHERE SOGR.SinavOkulID= @sinavOkulID  
               AND SOGR.SinifKodu= @SinifKodu  
               ORDER BY K.Adi,K.Soyadi; 

                SET NOCOUNT OFF;  
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
     * @author Okan CIRAN  -- kullanılmıyor
     * @ öğrencilerin sınav kitapçıkları kaydediliyor.
     * @version v 1.0  23.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function ogrenciSinavitapcikKaydet($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();

            $SinavOgrenciId = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavOgrenciId']) && $params['SinavOgrenciId'] != "")) {
                $SinavOgrenciId = $params['SinavOgrenciId'];
            }  
            $KitapcikTurID= 0;
            if ((isset($params['KitapcikTurID']) && $params['KitapcikTurID'] != "")) {
                $KitapcikTurID = $params['KitapcikTurID'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;  

                UPDATE SO 
                SET SO.SinavKitapcikID = sk1.SinavKitapcikID
                FROM ".$dbnamex."SNV_SinavOgrencileri SO
                INNER JOIN ".$dbnamex."SNV_SinavKitapciklari SK ON SK.SinavKitapcikID = SO.SinavKitapcikID
                INNER JOIN ".$dbnamex."SNV_SinavKitapciklari SK1 ON SK1.SinavID = SK.SinavID AND SK1.KitapcikTurID = '".$KitapcikTurID."'
                WHERE 
                    SO.SinavOgrenciID= '".$SinavOgrenciId."';
	 
            SET NOCOUNT OFF;   
                "; 
            $statement = $pdo->prepare($sql); 
       //    $result = $statement->execute();
             $insertID =1;
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
     * @ klasik sınav sonuclarını kaydeder.
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function ogrenciSinaviSonuclariKaydet($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
            $KitapcikTurID= 0;
            if ((isset($params['KitapcikTurID']) && $params['KitapcikTurID'] != "")) {
                $KitapcikTurID = $params['KitapcikTurID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
             
            $SendXmlData = '';
            $p2= '';
            if ((isset($params['XmlData']) && $params['XmlData'] != "")) {
                $p2 = $params['XmlData'];
                
                /*  
                 '<Nodes>
                    <Dugum SinavOgrenciSoruCevapID="4725b5de-a348-4130-9d84-1530b22fd558" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="2aa51fca-8a54-4bb0-a7e9-4336b7df387c" isDogru="True" AldigiPuan="12.00"/>
                    <Dugum SinavOgrenciSoruCevapID="99d19645-f6ea-4e19-93a6-b5b6a4a1a997" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="7fa152e1-bdb7-4068-b267-3829b1caec55" isDogru="True" AldigiPuan="12.00"/>
                    <Dugum SinavOgrenciSoruCevapID="b262a9e5-d6de-4b75-9370-1eed45a2ae74" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="4c32d1be-6849-4bd4-b534-10c14f635c13" isDogru="True" AldigiPuan="10.00"/>
                    <Dugum SinavOgrenciSoruCevapID="fd517314-006d-4045-82f3-5fcf38919ecd" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="ea9cb3da-7995-440e-99c8-2b0dd17ef506" isDogru="True" AldigiPuan="9.00"/>
                    <Dugum SinavOgrenciSoruCevapID="ea7c7f90-7e31-4693-a093-f153d2b235e3" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="3c278ff8-3155-4887-b1c6-8b884857d9c3" isDogru="True" AldigiPuan="10.00"/>
                    <Dugum SinavOgrenciSoruCevapID="4fbe812e-4eb9-4ba2-9e8b-8b85a51ac0ea" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="71fe7675-b484-4b34-9a3c-3e91d5f7960e" isDogru="True" AldigiPuan="6.00"/>
                    <Dugum SinavOgrenciSoruCevapID="4d8a5ece-db6b-4c9b-a021-8a84d93a586b" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="612e55a1-1545-43d7-9e47-aab5646294f4" isDogru="True" AldigiPuan="5.00"/>
                    <Dugum SinavOgrenciSoruCevapID="05459fa7-2ea8-4b1c-9887-cf74fdd45cca" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="5051ddb8-56c4-4545-abf6-5c5ed7116887" isDogru="True" AldigiPuan="8.00"/>
                    <Dugum SinavOgrenciSoruCevapID="19061f0b-d9bb-40bd-b093-e00a12a5f68f" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="9a71d34b-4a15-45e6-ab83-41e329efdf46" isDogru="True" AldigiPuan="6.00"/>
                    <Dugum SinavOgrenciSoruCevapID="3009efa4-30c0-41bd-ab8c-f178ab9822db" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="456f19d1-3233-4c20-a57e-8f76790f13f2" isDogru="True" AldigiPuan="6.00"/>
                    <Dugum SinavOgrenciSoruCevapID="e54cdfc9-d3c7-4289-b284-9bd86c04d9f7" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="5c9a2530-1d22-4b32-984a-a620eba8c84b" isDogru="True" AldigiPuan="6.00"/>
                    <Dugum SinavOgrenciSoruCevapID="c3e611a6-c64a-43f6-8084-084989576ce2" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="2a5cc3ca-83ef-409d-9d13-77aa0ce12387" isDogru="True" AldigiPuan="5.00"/>
                    <Dugum SinavOgrenciSoruCevapID="3f9526fd-0b20-4365-9ba7-1c92f7e209c9" SinavOgrenciID="6e5a5e09-bb4b-4f53-a712-d1a9ff5aaa09" SinavSoruID="8a159ee3-7663-4d8d-a9e8-04aee4d74684" isDogru="True" AldigiPuan="5.00"/>	 
                </Nodes>'
                  
                 */
            $XmlData = ' '; 
            $dataValue = NULL;
            $SinavOgrenciSoruCevapID = NULL;
            $ogrenciid = NULL;
            $soruid = NULL;
            $puan = NULL;
            if ((isset($params['XmlData']) && $params['XmlData'] != "")) {
                $XmlData = $params['XmlData'];
                $dataValue =  json_decode($XmlData, true);
                
             //   print_r( "////////////"); 
             //   print_r($dataValue[0]['SinavOgrenciSoruCevapID'] );    print_r( "/////"); 
            //    print_r($dataValue[0]['ogrenciid']   );    print_r( "///////"); 
            //    print_r($dataValue[0]['soruid']  );    print_r( "////////"); 
             //   print_r($dataValue[0]['puan']   );    print_r( "///////"); 
              // echo($dataValue[0]['id']   ); 
             //     print_r( $dataValue[0]["yok"] ); 
             // print_r( "////////////"); 
                    foreach ($dataValue as $std) {                      
                        if ($std  != null) {
                        //   print_r($std ); 
                        $SinavOgrenciSoruCevapID = $std ['SinavOgrenciSoruCevapID'] ; 
                        $ogrenciid = $std ['ogrenciid'] ; 
                        $soruid = $std ['soruid'] ; 
                        $puan = $std ['puan'] ;  
                        if (( $puan != "")) {
                          //  print_r(htmlentities('<Ogrenci><OgrenciID>').$dataValue[0][0]).htmlentities('</OgrenciID><DevamsizlikKodID>').$dataValue[0][1].htmlentities('</DevamsizlikKodID> ' )  ; 
                      // echo( '<Ogrenci><OgrenciID>'.$std[0].'</OgrenciID><DevamsizlikKodID>'.$devamsizlikKodID.'</DevamsizlikKodID><Aciklama/></Ogrenci>' ); 
                         $SendXmlData =$SendXmlData.'<Dugum SinavOgrenciSoruCevapID="'.$SinavOgrenciSoruCevapID.'" SinavOgrenciID="'.$ogrenciid.'" SinavSoruID="'.$soruid.'" isDogru="True" AldigiPuan="'.$puan.'"/>' ;  
                        }}
                    }
                  
               $SendXmlData = '<Nodes>'.$SendXmlData.'</Nodes>';
            }  
          //     echo(  $SendXmlData);
            } 
            $sql = "  
            SET NOCOUNT ON;   
            
            DECLARE @p1 xml;  
            
            UPDATE SO 
            SET SO.SinavKitapcikID = sk1.SinavKitapcikID
            FROM ".$dbnamex."SNV_SinavOgrencileri SO
            INNER JOIN ".$dbnamex."SNV_SinavKitapciklari SK ON SK.SinavKitapcikID = SO.SinavKitapcikID
            INNER JOIN ".$dbnamex."SNV_SinavKitapciklari SK1 ON SK1.SinavID = SK.SinavID AND SK1.KitapcikTurID = '".$KitapcikTurID."'
            WHERE 
                SO.SinavOgrenciID= '".$ogrenciid."';


            
            set @p1=convert(xml,N"; 
           
            $sql = $sql. "'".$SendXmlData."')
         
            exec PRC_SNV_SinavOgrenciSoruCevaplari_Save_DersCevaplari @XMLData=@p1

            SET NOCOUNT OFF; 
                ";  
            
            $statement = $pdo->prepare($sql); 
     //  echo debugPDO($sql, $params);
     //     $result = $statement->execute(); 
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
     * @ ogretmen klasik sınav sonuclarını onaylar.
     * @version v 1.0  25.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */ 
    public function ogrenciSinaviSonuclariOnay($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);
            $pdo->beginTransaction();

            $SinavID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavID']) && $params['SinavID'] != "")) {
                $SinavID = $params['SinavID'];
            }  
            $SinifID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinifID']) && $params['SinifID'] != "")) {
                $SinifID = $params['SinifID'];
            }  
            $Onay= 0;
            if ((isset($params['Onay']) && $params['Onay'] != "")) {
                $Onay = $params['Onay'];
            } 
            
            $sql = "  
            SET NOCOUNT ON;  
            declare 
                @SinavID uniqueidentifier,
                @SinifID nvarchar(36) =''  collate SQL_Latin1_General_CP1254_CI_AS,
                @Onay bit ; 
            set @SinavID = '".$SinavID ."';
            set @SinifID= '".$SinifID."';
            set @Onay='".$Onay."' ; 

            UPDATE ".$dbnamex."SNV_YaziliSinavSinifOnaylari 
            SET Onay = @Onay 
            WHERE SinavID = @SinavID AND 
            SinifID = @SinifID; 
	 
            SET NOCOUNT OFF;   
                "; 
            $statement = $pdo->prepare($sql); 
       //    $result = $statement->execute();
            $insertID =1;
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
     * @ mesaj için okul listesi 
     * @version v 1.0  10.10.2017
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function sinavdaKullanilanKitaplar($params = array()) {
        try {
           $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }    
             
            $SinavOkulID=  'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            if ((isset($params['SinavOkulID']) && $params['SinavOkulID'] != "")) {
                $SinavOkulID = $params['SinavOkulID'];
            } 
            $SinifKodu=  'CCCCC';
            if ((isset($params['SinifKodu']) && $params['SinifKodu'] != "")) {
                $SinifKodu = $params['SinifKodu'];
            } 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue); 
          
            $sql = "   
                SET NOCOUNT ON;   
                    
                declare 
                @sinavOkulID AS UNIQUEIDENTIFIER ,
                @SinifKodu AS NVARCHAR(20) = ''  collate SQL_Latin1_General_CP1254_CI_AS,
                @MyFields AS NVARCHAR(MAX) =''  collate SQL_Latin1_General_CP1254_CI_AS,
                @NumarayaGoreSirala AS BIT = 1; 
                DECLARE @SQL NVARCHAR(MAX); 
                
                set @sinavOkulID = '".$SinavOkulID."' ; 
                set @SinifKodu ='".$SinifKodu."';

              /*  set @sinavOkulID = 'F700A96B-4628-41E5-A261-D7834983CF4D' ; 
                 set @SinifKodu ='5KAR1'; */ 



                SELECT * FROM ( 
                    SELECT  
                        '00000000-0000-0000-0000-000000000000' as SinavKitapcikID,
                        -1 as KitapcikTurID,
                        COALESCE(NULLIF(ax.[description],''),a.[description_eng]) AS KitapcikAciklamasi 
                    FROM [BILSANET_MOBILE].[dbo].[sys_specific_definitions] a
                    INNER JOIN BILSANET_MOBILE.dbo.sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                    LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =647 AND lx.deleted =0 AND lx.active =0
                    LEFT JOIN [BILSANET_MOBILE].[dbo].[sys_specific_definitions]  ax on (ax.language_parent_id = a.[id] or ax.[id] = a.[id] ) and  ax.language_id= lx.id  
                    WHERE a.[main_group] = 1 and a.[first_group]  = 9 and
                        a.language_parent_id =0 
                union  
                SELECT  distinct
                    SOGR.SinavKitapcikID,
                    SK.KitapcikTurID,
                    SK.KitapcikAciklamasi 
               FROM ".$dbnamex."SNV_SinavOgrencileri SOGR
               INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON SOGR.OgrenciSeviyeID = OS.OgrenciSeviyeID
               INNER JOIN ".$dbnamex."GNL_Siniflar SNF ON OS.SinifID = SNF.SinifID
               INNER JOIN ".$dbnamex."GNL_DersYillari DY ON SNF.DersYiliID = DY.DersYiliID 
               INNER JOIN ".$dbnamex."SNV_SinavKitapciklari SK ON SOGR.SinavKitapcikID = SK.SinavKitapcikID 
               INNER JOIN ".$dbnamex."SNV_Sinavlar SNV ON SNV.SinavID=SK.SinavID
               WHERE SOGR.SinavOkulID= @sinavOkulID  
               AND SOGR.SinifKodu= @SinifKodu 
               ) as asdasd
               ORDER BY  KitapcikTurID; 

                SET NOCOUNT OFF;  
                 
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
   
    ///////// numeric alanı unutma 
  /*
   
  
     
       
   */
}
