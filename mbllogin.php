<?php

// test commit for branch slim2
require 'vendor/autoload.php';


use \Services\Filter\Helper\FilterFactoryNames as stripChainers;

/* $app = new \Slim\Slim(array(
  'mode' => 'development',
  'debug' => true,
  'log.enabled' => true,
  )); */

$app = new \Slim\SlimExtended(array(
    'mode' => 'development',
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::INFO,
    'exceptions.rabbitMQ' => true,
    'exceptions.rabbitMQ.logging' => \Slim\SlimExtended::LOG_RABBITMQ_FILE,
    'exceptions.rabbitMQ.queue.name' => \Slim\SlimExtended::EXCEPTIONS_RABBITMQ_QUEUE_NAME
        ));
 
/**
 * "Cross-origion resource sharing" kontrolüne izin verilmesi için eklenmiştir
 * @author Okan CIRAN
 * @since 25.10.2017
 */
$res = $app->response();
$res->header('Access-Control-Allow-Origin', '*');
$res->header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
$app->add(new \Slim\Middleware\MiddlewareInsertUpdateDeleteLog());
$app->add(new \Slim\Middleware\MiddlewareHMAC());
$app->add(new \Slim\Middleware\MiddlewareSecurity());
$app->add(new \Slim\Middleware\MiddlewareMQManager());
$app->add(new \Slim\Middleware\MiddlewareBLLManager());
$app->add(new \Slim\Middleware\MiddlewareDalManager());
$app->add(new \Slim\Middleware\MiddlewareServiceManager());
$app->add(new \Slim\Middleware\MiddlewareMQManager());

 
 
/**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/gnlKullaniciMebKoduFindByTcKimlikNo_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vtc = NULL; 
    if (isset($_GET['tc'])) {
        $stripper->offsetSet('tc', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['tc']));
    }
    $vcid = NULL;
   if (isset($_GET['CID'])) {
        $stripper->offsetSet('CID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['CID']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('tc')) {
        $vtc = $stripper->offsetGet('tc')->getFilterValue();
    }
    if ($stripper->offsetExists('CID')) {
        $vcid = $stripper->offsetGet('CID')->getFilterValue();
    }
   
    $resDataInsert = $BLL->gnlKullaniciMebKoduFindByTcKimlikNo(array( 
        'url' => $_GET['url'], 
        'tc' => $vtc, 
        'Cid' => $vcid, 
        'Did' => $vDid,
        ));
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($resDataInsert));
}
);

 
/**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/gnlKullaniciFindForLoginByTcKimlikNo_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vtc = NULL;     
    if (isset($_GET['tc'])) {
        $stripper->offsetSet('tc', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['tc']));
    }
    $vsifre = NULL;
    if (isset($_GET['sifre'])) {
        $stripper->offsetSet('sifre', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sifre']));
    }
    $vDeviceID = NULL;     
    if (isset($_GET['deviceID'])) {
        $stripper->offsetSet('deviceID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['deviceID']));
    } 
    
    $stripper->strip();
    
    if ($stripper->offsetExists('tc')) {
        $vtc = $stripper->offsetGet('tc')->getFilterValue();
    }
    if ($stripper->offsetExists('sifre')) {
        $vsifre = $stripper->offsetGet('sifre')->getFilterValue();
    }
    if ($stripper->offsetExists('deviceID')) {
        $vDeviceID = $stripper->offsetGet('deviceID')->getFilterValue();
    }
   
    $resDataInsert = $BLL->gnlKullaniciFindForLoginByTcKimlikNo(array( 
        'url' => $_GET['url'], 
        'tc' => $vtc,  
        'sifre' => $vsifre, 
        'DeviceID' => $vDeviceID, 
        
        ));
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($resDataInsert));
}
);
 

/**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/mobilfirstdata_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vtc = NULL;   
    if (isset($_GET['tc'])) {
        $stripper->offsetSet('tc', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['tc']));
    } 
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
     
    $stripper->strip();
     
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('tc')) {
        $vtc = $stripper->offsetGet('tc')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }  
     
    $resDataInsert = $BLL->mobilfirstdata(array( 
                'url' => $_GET['url'], 
                'kisiId' => $vkisiId,  
                'tcno' => $vtc,  
                'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(
            "OkulKullaniciID" => $menu["OkulKullaniciID"],
            "OkulID" => $menu["OkulID"],
            "KisiID" => $menu["KisiID"],
            "RolID" =>  ($menu["RolID"]),
            "OkulAdi" => html_entity_decode($menu["OkulAdi"]), 
            "MEBKodu" => html_entity_decode($menu["MEBKodu"]), 
            "ePosta" => html_entity_decode($menu["ePosta"]),
            "DersYiliID" =>  ($menu["DersYiliID"]),
            "EgitimYilID" =>  ($menu["EgitimYilID"]),
            "EgitimYili" =>  ($menu["EgitimYili"]), 
            "DonemID" =>  ($menu["DonemID"]), 
            "KurumID" =>  ($menu["KurumID"]), 
            "proxy" =>  ($menu["serverproxy"]), 
            "cid" =>  ($menu["cid"]),
            "did" =>  ($menu["database_id"]),
            
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus)); 
    
}
);
  
/**
 * Okan CIRAN
 * @since 26-09-2017 
 */
$app->get("/mobilMenu_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers();
     
    $vRolID = NULL;
    if (isset($_GET['RolID'])) {
        $stripper->offsetSet('RolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['RolID']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    }   
    $stripper->strip(); 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    if ($stripper->offsetExists('RolID')) 
        {$vRolID = $stripper->offsetGet('RolID')->getFilterValue(); }  
    
    $resDataMenu = $BLL->mobilMenu(array(      
                                            'RolID' => $vRolID,  
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
            "ID" => $menu["ID"],
            "MenuID" => $menu["MenuID"],
           /* "ParentID" => $menu["ParentID"],*/
            "MenuAdi" => html_entity_decode($menu["MenuAdi"]),
            "Aciklama" => html_entity_decode($menu["Aciklama"]),
            "URL" => $menu["URL"],
            "SubDivision" => $menu["SubDivision"],
            "ImageURL" => $menu["ImageURL"], 
            "divid" => $menu["divid"], 
            "iconcolor" => $menu["iconcolor"], 
            "iconclass" => $menu["iconclass"], 
            "collapse" => $menu["collapse"],  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 

/**
 * Okan CIRAN
 * @since 26-09-2017 
 */
$app->get("/gnlKisiOkulListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }   
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did'])); 
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    $resDataMenu = $BLL->gnlKisiOkulListesi(array(      
                                            'kisiId' => $vkisiId, 
                                            'Cid' => $vCid,
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "OkulID" => $menu["OkulID"], 
             "OkulAdi" => html_entity_decode($menu["OkulAdi"]), 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/ogretmenDersProgrami_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vOkulID = NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    }
      
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
     if ($stripper->offsetExists('languageID')) {
        $vLanguageID = $stripper->offsetGet('languageID')->getFilterValue();
    }
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    }
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    }
    
    $resDataInsert = $BLL->ogretmenDersProgrami(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'LanguageID' => $vLanguageID,  
        'OkulID' => $vOkulID,  
        'dersYiliID' => $vdersYiliID,   
        'Cid' => $vCid, 
        'Did' => $vDid, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "HaftaGunu" => $menu["HaftaGunu"],
            "DersSirasi" => $menu["DersSirasi"],
            "SinifDersID" => $menu["SinifDersID"], 
            "DersKodu" => html_entity_decode($menu["DersKodu"]), 
            "DersAdi" => html_entity_decode($menu["DersAdi"]), 
            "SinifKodu" => html_entity_decode($menu["SinifKodu"]), 
             "Aciklama" => html_entity_decode($menu["Aciklama"]), 
         
            "SubeGrupID" =>  ($menu["SubeGrupID"]),
            "BaslangicSaati" =>  ($menu["BaslangicSaati"]),
            "BitisSaati" =>  ($menu["BitisSaati"]), 
            "DersBaslangicBitisSaati" =>  ($menu["DersBaslangicBitisSaati"]), 
            "SinifOgretmenID" =>  ($menu["SinifOgretmenID"]),
            "DersHavuzuID" =>  ($menu["DersHavuzuID"]),
            "SinifID" =>  ($menu["SinifID"]),
            "DersID" =>  ($menu["DersID"]),
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
    
     
} 
);



/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/OgretmenProgramindakiDersler_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vOkulID = NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    }
      
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
     if ($stripper->offsetExists('languageID')) {
        $vLanguageID = $stripper->offsetGet('languageID')->getFilterValue();
    }
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    }
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    }
    
    $resDataInsert = $BLL->OgretmenProgramindakiDersler(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'LanguageID' => $vLanguageID,  
        'OkulID' => $vOkulID,  
        'dersYiliID' => $vdersYiliID,   
        'Cid' => $vCid, 
        'Did' => $vDid, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
           
            "SinifDersID" => $menu["SinifDersID"], 
            "SinifID" =>  ($menu["SinifID"]),
            "DersAdi" => html_entity_decode($menu["DersAdi"]),  
            "Aciklama" => html_entity_decode($menu["Aciklama"]), 
          
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
    
     
} 
);

  
/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/ogretmenDersProgramiDersSaatleri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vsinifID = NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    }
    $vtarih = NULL;     
    if (isset($_GET['tarih'])) {
        $stripper->offsetSet('tarih', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['tarih']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('sinifID')) {
        $vsinifID = $stripper->offsetGet('sinifID')->getFilterValue();
    }
    if ($stripper->offsetExists('tarih')) {
        $vtarih = $stripper->offsetGet('tarih')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->ogretmenDersProgramiDersSaatleri(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'sinifID' => $vsinifID, 
        'tarih' => $vtarih,   
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            
            "BaslangicSaati" => $menu["BaslangicSaati"],
            "BitisSaati" => $menu["BitisSaati"],
            "DersSirasi" => $menu["DersSirasi"], 
            "DersKodu" => html_entity_decode($menu["DersKodu"]), 
            "DersAdi" => html_entity_decode($menu["DersAdi"]), 
            "Aciklama" => html_entity_decode($menu["Aciklama"]),  
            "DersID" =>  ($menu["DersID"]),
            "HaftaGunu" =>  html_entity_decode($menu["HaftaGunu"]),
                        
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
    
     
} 
);

/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/ogretmenDersPrgDersSaatleriOgrencileri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vsinifID = NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    }
    $vtarih = NULL;     
    if (isset($_GET['tarih'])) {
        $stripper->offsetSet('tarih', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['tarih']));
    }
    $vdersSirasi= NULL;     
    if (isset($_GET['dersSirasi'])) {
        $stripper->offsetSet('dersSirasi', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['dersSirasi']));
    } 
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('sinifID')) {
        $vsinifID = $stripper->offsetGet('sinifID')->getFilterValue();
    }
    if ($stripper->offsetExists('tarih')) {
        $vtarih = $stripper->offsetGet('tarih')->getFilterValue();
    }
    if ($stripper->offsetExists('dersSirasi')) {
        $vdersSirasi = $stripper->offsetGet('dersSirasi')->getFilterValue();
    }
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->ogretmenDersPrgDersSaatleriOgrencileri(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'sinifID' => $vsinifID, 
        'tarih' => $vtarih,  
        'dersSirasi' => $vdersSirasi,  
        'dersYiliID' => $vdersYiliID,   
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "OgrenciID" => $menu["OgrenciID"],
            "Tarih" => $menu["Tarih"],
            "DersSirasi" => $menu["DersSirasi"], 
            "DersYiliID" =>  ($menu["DersYiliID"]),
            "Numarasi" => html_entity_decode($menu["Numarasi"]),
            "Adsoyad" => html_entity_decode($menu["adsoyad"] ), 
          //  "Adi" => html_entity_decode($menu["Adi"] ), 
          //  "Soyadi" => html_entity_decode($menu["Soyadi"]),  
          //  "TCKimlikNo" =>  html_entity_decode($menu["TCKimlikNo"]),
            "CinsiyetID" =>  html_entity_decode($menu["CinsiyetID"]),
            "DevamsizlikKodID" =>  html_entity_decode($menu["DevamsizlikKodID"]),
            "Aciklama" =>  html_entity_decode($menu["Aciklama"]), 
            "Fotograf" =>  ($menu["Fotograf"]),  
                        
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);

/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/ogretmenVeliRandevulari_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }   
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->ogretmenVeliRandevulari(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "VeliRandevuID" => $menu["VeliRandevuID"],
            "SinifOgretmenID" => $menu["SinifOgretmenID"],
            "VeliID" => $menu["VeliID"], 
            "BasZamani" =>  ($menu["BasZamani"]),
            "BitZamani" =>  ($menu["BitZamani"]), 
            "Aciklama" => html_entity_decode($menu["Aciklama"]), 
            "Onay" =>  ($menu["Onay"]),  
            "Ogretmen_Adi" =>  html_entity_decode($menu["Ogretmen_Adi"]),
            "Ogretmen_Soyadi" =>  html_entity_decode($menu["Ogretmen_Soyadi"]),
            "Ogrenci_Adi" =>  html_entity_decode($menu["Ogrenci_Adi"]),
            "Ogrenci_Soyadi" =>  html_entity_decode($menu["Ogrenci_Soyadi"]),  
            "Veli_Adi" =>  html_entity_decode($menu["Veli_Adi"]),
            "Veli_Soyadi" =>  html_entity_decode($menu["Veli_Soyadi"]),  
             "DersAdi" =>  html_entity_decode($menu["DersAdi"]),
            "Ders_Ogretmen" =>  html_entity_decode($menu["Ders_Ogretmen"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus)); 
} 
);


/** 
 *  * Okan CIRAN
 * @since 05.10.2017
 * rest servislere eklendi
 */
$app->get("/InsertDevamsizlik_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('mblLoginBLL');  
    $headerParams = $app->request()->headers();
      
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vDersYiliID= NULL;     
    if (isset($_GET['DersYiliID'])) {
        $stripper->offsetSet('DersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['DersYiliID']));
    } 
    $vSinifID = NULL;     
    if (isset($_GET['SinifID'])) {
        $stripper->offsetSet('SinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['SinifID']));
    }
    $vDersID = NULL;     
    if (isset($_GET['DersID'])) {
        $stripper->offsetSet('DersID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['DersID']));
    }
    $vDersSirasi= NULL;     
    if (isset($_GET['DersSirasi'])) {
        $stripper->offsetSet('DersSirasi', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['DersSirasi']));
    }  
    $vDonemID = NULL;     
    if (isset($_GET['DonemID'])) {
        $stripper->offsetSet('DonemID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['DonemID']));
    } 
    $vOkulOgretmenID = NULL;     
    if (isset($_GET['OkulOgretmenID'])) {
        $stripper->offsetSet('OkulOgretmenID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['OkulOgretmenID']));
    } 
    $vTarih = NULL;     
    if (isset($_GET['Tarih'])) {
        $stripper->offsetSet('Tarih', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['Tarih']));
    }
    $vXmlData = NULL;     
    if (isset($_GET['XmlData'])) {
        $stripper->offsetSet('XmlData', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_JASON_LVL1, 
                $app, $_GET['XmlData']));
    }
    $vSinifDersID = NULL;     
    if (isset($_GET['SinifDersID'])) {
        $stripper->offsetSet('SinifDersID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['SinifDersID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('DersYiliID')) {
        $vDersYiliID = $stripper->offsetGet('DersYiliID')->getFilterValue();
    }
    if ($stripper->offsetExists('SinifID')) {
        $vSinifID = $stripper->offsetGet('SinifID')->getFilterValue();
    }
    if ($stripper->offsetExists('DersID')) {
        $vDersID = $stripper->offsetGet('DersID')->getFilterValue();
    }
    if ($stripper->offsetExists('DersSirasi')) {
        $vDersSirasi = $stripper->offsetGet('DersSirasi')->getFilterValue();
    } 
    if ($stripper->offsetExists('DonemID')) {
        $vDonemID = $stripper->offsetGet('DonemID')->getFilterValue();
    }
    if ($stripper->offsetExists('OkulOgretmenID')) {
        $vOkulOgretmenID = $stripper->offsetGet('OkulOgretmenID')->getFilterValue();
    }
    if ($stripper->offsetExists('Tarih')) {
        $vTarih = $stripper->offsetGet('Tarih')->getFilterValue();
    }
    if ($stripper->offsetExists('XmlData')) {
        $vXmlData = $stripper->offsetGet('XmlData')->getFilterValue();
    }
    if ($stripper->offsetExists('SinifDersID')) {
        $vSinifDersID = $stripper->offsetGet('SinifDersID')->getFilterValue();
    }
$vXmlData=$_GET['XmlData']  ; 
//print_r($vXmlData);
    $resDataInsert = $BLL->insertDevamsizlik(array(
            'OgretmenID' => $vKisiId,  
            'DersYiliID' => $vDersYiliID, 
            'SinifID' => $vSinifID, 
            'DersID' => $vDersID,  
            'DersSirasi' => $vDersSirasi,  
            'DonemID' => $vDonemID, 
            'OkulOgretmenID' => $vOkulOgretmenID,  
            'SinifDersID' => $vSinifDersID,  
            'Tarih' => $vTarih,  
            'XmlData' => $vXmlData,    
            'Cid' => $vCid, 
            'Did' => $vDid,
             ));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert)); 
    
}
);


/**
 *  * Okan CIRAN
 * @since 09.10.2017
 */
$app->get("/VeliOgrencileri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }   
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->veliOgrencileri(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'dersYiliID' => $vdersYiliID,  
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "OgrenciID" => $menu["OgrenciID"],
            "SinifID" => $menu["SinifID"],
            "DersYiliID" => $menu["DersYiliID"], 
            "SinifKodu" =>  ($menu["SinifKodu"]),            
            "SinifAdi" => html_entity_decode($menu["SinifAdi"]), 
            "Numarasi" =>  ($menu["Numarasi"]),  
            "OgrenciOkulBilgiID" =>   ($menu["OgrenciOkulBilgiID"]),
            "KisiID" =>   ($menu["KisiID"]),
            "CinsiyetID" =>   ($menu["CinsiyetID"]),
            "Adi" =>  html_entity_decode($menu["Adi"]),             
            "Soyadi" =>  html_entity_decode($menu["Soyadi"]),
            "AdiSoyadi" =>  html_entity_decode($menu["Adi_Soyadi"]),             
            "TCKimlikNo" =>  html_entity_decode($menu["TCKimlikNo"]),
            "ePosta" =>   ($menu["ePosta"]), 
            "OkulID" =>   ($menu["OkulID"]), 
            "OgrenciSeviyeID" =>   ($menu["OgrenciSeviyeID"]), 
            "Fotograf" =>   ($menu["Fotograf"]), 
        );
    } 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus)); 
    
} 
);


/**
 *  * Okan CIRAN
 * @since 09.10.2017
 */
$app->get("/OgrenciDevamsizlikListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }    
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->ogrenciDevamsizlikListesi(array( 
        'url' => $_GET['url'], 
        'kisiId' => $vkisiId,  
        'dersYiliID' => $vdersYiliID,  
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "OgrenciDevamsizlikID" => $menu["OgrenciDevamsizlikID"],
            "DersYiliID" => $menu["DersYiliID"],
            "OgrenciID" => $menu["OgrenciID"], 
         //   "DevamsizlikKodID" =>  ($menu["DevamsizlikKodID"]),  
            "DevamsizlikPeriyodID" =>  ($menu["DevamsizlikPeriyodID"]),  
            "Tarih" => $menu["Tarih"], 
            "rownum" =>  ($menu["rownum"]),  
            "DevamsizlikPeriyodID" =>  ($menu["DevamsizlikPeriyodID"]), 
            "DevamsizlikAdi" => html_entity_decode($menu["DevamsizlikAdi"]), 
            "GunKarsiligi" => html_entity_decode($menu["GunKarsiligi"]), 
            "Aciklama" => html_entity_decode($menu["Aciklama"]),  
             "OgrenciseviyeID" => $menu["OgrenciseviyeID"],
             "OzurluDevamsiz1" => $menu["OzurluDevamsiz1"],
             "OzursuzDevamsiz1" => $menu["OzursuzDevamsiz1"],
             "OzurluDevamsiz2" => $menu["OzurluDevamsiz2"],
             "OzursuzDevamsiz2" => $menu["OzursuzDevamsiz2"],
             
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus)); 
} 
);



/**
 *  * Okan CIRAN
 * @since 09.10.2017
 */
$app->get("/Kurumyoneticisisubelistesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }            
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->kurumyoneticisisubelistesi(array( 
        'url' => $_GET['url'],  
        'DersYiliID' => $vdersYiliID,   
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "SinifID" => $menu["SinifID"],
            "DersYiliID" => $menu["DersYiliID"],
            "SeviyeID" => $menu["SeviyeID"], 
            "SinifKodu" =>  ($menu["SinifKodu"]),            
            "SinifAdi" => html_entity_decode($menu["SinifAdi"]), 
            "Sanal" =>  ($menu["Sanal"]),  
            "SubeGrupID" =>   ($menu["SubeGrupID"]),
            "SeviyeKodu" =>   ($menu["SeviyeKodu"]), 
            "SinifOgretmeni" =>  html_entity_decode($menu["SinifOgretmeni"]),             
            "MudurYardimcisi" =>  html_entity_decode($menu["MudurYardimcisi"]),
            "Aciklama" =>  html_entity_decode($menu["Aciklama"]),             
            
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus)); 
      
} 
);


/**
 *  * Okan CIRAN
 * @since 09.10.2017
 */
$app->get("/Kysubeogrencilistesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vSinifID= NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    }   
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinifID')) {
        $vSinifID = $stripper->offsetGet('sinifID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->kysubeogrencilistesi(array( 
        'url' => $_GET['url'],  
        'SinifID' => $vSinifID,   
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "OgrenciSeviyeID" => $menu["OgrenciSeviyeID"],
            "OgrenciID" => $menu["OgrenciID"],
            "SinifID" => $menu["SinifID"], 
            "OgrenciID" =>  ($menu["OgrenciID"]),            
            "Numarasi" => html_entity_decode($menu["Numarasi"]), 
            "KisiID" =>  ($menu["KisiID"]),  
            "CinsiyetID" =>   ($menu["CinsiyetID"]), 
            "Adi" =>  html_entity_decode($menu["Adi"]),             
            "Soyadi" =>  html_entity_decode($menu["Soyadi"]),
            "TCKimlikNo" =>  html_entity_decode($menu["TCKimlikNo"]),             
            "ePosta" =>  html_entity_decode($menu["ePosta"]),             
            "SeviyeID" =>   ($menu["SeviyeID"]),
            "Aciklama" =>  html_entity_decode($menu["Aciklama"]),   
            "Fotograf" =>   ($menu["Fotograf"]), 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);
 

/**
 *  * Okan CIRAN
 * @since 09.10.2017
 */
$app->get("/KySubeOgrenciDersListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vOgrenciSeviyeID= NULL;     
    if (isset($_GET['ogrenciSeviyeID'])) {
        $stripper->offsetSet('ogrenciSeviyeID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogrenciSeviyeID']));
    }   
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('ogrenciSeviyeID')) {
        $vOgrenciSeviyeID = $stripper->offsetGet('ogrenciSeviyeID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    $resDataInsert = $BLL->KySubeOgrenciDersListesi(array( 
        'url' => $_GET['url'],  
        'OgrenciSeviyeID' => $vOgrenciSeviyeID, 
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "DersAdi" =>  html_entity_decode($menu["DersAdi"]), 
            "HaftalikDersSaati" =>   ($menu["HaftalikDersSaati"]), 
            "Donem1_DonemNotu" =>   ($menu["Donem1_DonemNotu"]),             
            "Donem2_DonemNotu" =>   ($menu["Donem2_DonemNotu"]),
            "YilSonuNotu" =>   ($menu["YilSonuNotu"]), 
            "YilSonuPuani" =>   ($menu["YilSonuPuani"]), 
            
         //   "OgrenciSeviyeID" => $menu["OgrenciSeviyeID"],
         //   "DersHavuzuID" => $menu["DersHavuzuID"],
         //   "Numarasi" => $menu["Numarasi"], 
        //    "AdiSoyadi" =>  html_entity_decode($menu["AdiSoyadi"]),            
        //    "DersKodu" =>  ($menu["Numarasi"]), 
           
        //    "DonemID" =>   ($menu["DonemID"]), 
           
         //   "PuanOrtalamasi" =>   ($menu["PuanOrtalamasi"]),             
        //    "Donem1_PuanOrtalamasi" =>   ($menu["Donem1_PuanOrtalamasi"]),             
        //    "Donem2_PuanOrtalamasi" =>   ($menu["Donem2_PuanOrtalamasi"]),
         //   "AktifDonemNotu" =>   ($menu["AktifDonemNotu"]),   
        //    "YetistirmeKursuNotu" =>   ($menu["YetistirmeKursuNotu"]),             
        //    "YetistirmeKursuNotu" =>   ($menu["YetistirmeKursuNotu"]), 
          
           
        //    "YilsonuToplamAgirligi" =>   ($menu["YilsonuToplamAgirligi"]), 
        //    "OdevAldi" =>    ($menu["OdevAldi"]), 
        //    "ProjeAldi" =>   ($menu["ProjeAldi"]), 
        //    "OgrenciDersID" =>   ($menu["OgrenciDersID"]), 
        //    "OgrenciDonemNotID" =>   ($menu["OgrenciDonemNotID"]), 
         //   "PuanOrtalamasi" =>   ($menu["PuanOrtalamasi"]), 
         //   "Hesaplandi" =>   ($menu["Hesaplandi"]), 
         //   "KanaatNotu" =>   ($menu["KanaatNotu"]), 
        //    "Sira" =>   ($menu["Sira"]), 
         //   "EgitimYilID" =>   ($menu["EgitimYilID"]), 
            
         //   "Perf1OdevAldi" =>   ($menu["Perf1OdevAldi"]), 
        //    "Perf2OdevAldi" =>   ($menu["Perf2OdevAldi"]), 
         //   "Perf3OdevAldi" =>   ($menu["Perf3OdevAldi"]), 
         //   "Perf4OdevAldi" =>   ($menu["Perf4OdevAldi"]), 
        //    "Perf5OdevAldi" =>   ($menu["Perf5OdevAldi"]), 
        //    "AltDers" =>   ($menu["AltDers"]), 
        //    "YillikProjeAldi" =>   ($menu["YillikProjeAldi"]), 
         //   "YetistirmeKursunaGirecek" =>  ($menu["YetistirmeKursunaGirecek"]),             
        //    "OgretmenAdiSoyadi" =>   html_entity_decode($menu["OgretmenAdiSoyadi"]),
        //    "isPuanNotGirilsin" =>   ($menu["isPuanNotGirilsin"]),
       //     "isPuanNotHesapDahil" =>   ($menu["isPuanNotHesapDahil"]),
         //   "AgirlikliYilSonuNotu" =>   ($menu["AgirlikliYilSonuNotu"]),
         //   "AgirlikliYilsonuPuani" =>   ($menu["AgirlikliYilsonuPuani"]),
         //   "PBYCOrtalama" =>   ($menu["PBYCOrtalama"]),
        //    "DersSabitID" =>   ($menu["DersSabitID"]), 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);

/**
 *  * Okan CIRAN
 * @since 09.10.2017
 */
$app->get("/Ogretmensinavlistesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vKisiId = NULL;     
    if (isset($_GET['kisiID'])) {
        $stripper->offsetSet('kisiID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiID']));
    }
    $vOgretmenID= NULL;     
    if (isset($_GET['ogretmenID'])) {
        $stripper->offsetSet('ogretmenID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogretmenID']));
    } 
    $vOkulID = NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    }
    $vEgitimYilID = NULL;     
    if (isset($_GET['egitimYilID'])) {
        $stripper->offsetSet('egitimYilID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['egitimYilID']));
    }          
    
     $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $vGrid= 0;
    if (isset($_GET['grid'])) {
        $stripper->offsetSet('grid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['grid']));
    } 
    
    
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiID')) {
        $vKisiId = $stripper->offsetGet('kisiID')->getFilterValue();
    }
    if ($stripper->offsetExists('ogretmenID')) {
        $vOgretmenID = $stripper->offsetGet('ogretmenID')->getFilterValue();
    }
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    }
    if ($stripper->offsetExists('egitimYilID')) {
        $vEgitimYilID = $stripper->offsetGet('egitimYilID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    if ($stripper->offsetExists('grid')) 
        {$vGrid = $stripper->offsetGet('grid')->getFilterValue(); }   
   
    $resDataInsert = $BLL->Ogretmensinavlistesi(array( 
        'url' => $_GET['url'],  
        'KisiID' => $vKisiId,  
        'OgretmenID' => $vOgretmenID,  
        'OkulID' => $vOkulID,  
        'EgitimYilID' => $vEgitimYilID,   
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        'Grid' => $vGrid,
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "Donem" =>  html_entity_decode($menu["Donem"]), 
            "SinavTarihi" =>   ($menu["SinavTarihi"]), 
            "SinavBitisTarihi" =>   ($menu["SinavBitisTarihi"]),             
            "SinavTurAdi" =>   html_entity_decode($menu["SinavTurAdi"]),
            "SinavKodu" =>   html_entity_decode($menu["SinavKodu"]), 
            "SinavAciklamasi" =>   html_entity_decode($menu["SinavAciklamasi"]), 
            "SinavID" =>   ($menu["SinavID"]), 
             
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus)); 
} 
);


/**
 *  * Okan CIRAN
 * @since 09.10.2017
 */
$app->get("/Yakinisinavlistesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vKisiId = NULL;     
    if (isset($_GET['kisiID'])) {
        $stripper->offsetSet('kisiID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiID']));
    }
    $vOgretmenID= NULL;     
    if (isset($_GET['ogretmenID'])) {
        $stripper->offsetSet('ogretmenID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogretmenID']));
    } 
    $vOkulID = NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    }
    $vEgitimYilID = NULL;     
    if (isset($_GET['egitimYilID'])) {
        $stripper->offsetSet('egitimYilID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['egitimYilID']));
    }   
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiID')) {
        $vKisiId = $stripper->offsetGet('kisiID')->getFilterValue();
    }
    if ($stripper->offsetExists('ogretmenID')) {
        $vOgretmenID = $stripper->offsetGet('ogretmenID')->getFilterValue();
    }
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    }
    if ($stripper->offsetExists('egitimYilID')) {
        $vEgitimYilID = $stripper->offsetGet('egitimYilID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->yakinisinavlistesi(array( 
        'url' => $_GET['url'],  
        'KisiID' => $vKisiId,  
        'OgretmenID' => $vOgretmenID,  
        'OkulID' => $vOkulID,  
        'EgitimYilID' => $vEgitimYilID,  
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "Donem" =>  html_entity_decode($menu["Donem"]), 
            "SinavTarihi" =>   ($menu["SinavTarihi"]), 
            "SinavBitisTarihi" =>   ($menu["SinavBitisTarihi"]),             
            "SinavTurAdi" =>   html_entity_decode($menu["SinavTurAdi"]),
            "SinavKodu" =>   html_entity_decode($menu["SinavKodu"]), 
            "SinavAciklamasi" =>   html_entity_decode($menu["SinavAciklamasi"]), 
   
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus)); 
     
} 
);

/**
 *  * Okan CIRAN
 * @since 23.10.2017
 */
$app->get("/KurumYoneticisiSinavListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vKisiId = NULL;     
    if (isset($_GET['kisiID'])) {
        $stripper->offsetSet('kisiID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiID']));
    }
    $vOgretmenID= NULL;     
    if (isset($_GET['ogretmenID'])) {
        $stripper->offsetSet('ogretmenID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogretmenID']));
    } 
    $vOkulID = NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    }
    $vEgitimYilID = NULL;     
    if (isset($_GET['egitimYilID'])) {
        $stripper->offsetSet('egitimYilID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['egitimYilID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiID')) {
        $vKisiId = $stripper->offsetGet('kisiID')->getFilterValue();
    }
    if ($stripper->offsetExists('ogretmenID')) {
        $vOgretmenID = $stripper->offsetGet('ogretmenID')->getFilterValue();
    }
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    }
    if ($stripper->offsetExists('egitimYilID')) {
        $vEgitimYilID = $stripper->offsetGet('egitimYilID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->kurumYoneticisiSinavListesi(array( 
        'url' => $_GET['url'],  
        'KisiID' => $vKisiId,  
        'OgretmenID' => $vOgretmenID,  
        'OkulID' => $vOkulID,  
        'EgitimYilID' => $vEgitimYilID,  
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "Donem" =>  html_entity_decode($menu["Donem"]), 
            "SinavTarihi" =>   ($menu["SinavTarihi"]), 
            "SinavBitisTarihi" =>   ($menu["SinavBitisTarihi"]),             
            "SinavTurAdi" =>   html_entity_decode($menu["SinavTurAdi"]),
            "SinavKodu" =>   html_entity_decode($menu["SinavKodu"]), 
            "SinavAciklamasi" =>   html_entity_decode($menu["SinavAciklamasi"]), 
   
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);


/**
 *  * Okan CIRAN
 * @since 23.10.2017
 */
$app->get("/GelenMesajListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vKisiId = NULL;     
    if (isset($_GET['kisiID'])) {
        $stripper->offsetSet('kisiID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiID')) {
        $vKisiId = $stripper->offsetGet('kisiID')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
      
    $resDataInsert = $BLL->gelenMesajListesi(array( 
        'url' => $_GET['url'],  
        'KisiID' => $vKisiId, 
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "MesajID" =>   ($menu["MesajID"]), 
            "ReceiverID" =>   ($menu["ReceiverID"]), 
            "Okundu" =>   ($menu["Okundu"]),             
            "OkunduguTarih" =>    ($menu["OkunduguTarih"]),
            "Silindi" =>    ($menu["Silindi"]), 
            "MesajOncelikID" =>   ($menu["MesajOncelikID"]), 
            "Tarih" =>   ($menu["Tarih"]), 
            "SenderID" =>   ($menu["SenderID"]), 
            "RowNum" =>   ($menu["RowNum"]),  
            "Konu" =>   html_entity_decode($menu["Konu"]), 
            "Mesaj" =>   html_entity_decode($menu["Mesaj"]), 
            "SenderAdi" =>   html_entity_decode($menu["SenderAdi"]), 
            "SenderSoyadi" =>   html_entity_decode($menu["SenderSoyadi"]), 
            "SenderAdiSoyadi" =>   html_entity_decode($menu["SenderAdiSoyadi"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);

/**
 *  * Okan CIRAN
 * @since 23.10.2017
 */
$app->get("/GidenMesajListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vKisiId = NULL;     
    if (isset($_GET['kisiID'])) {
        $stripper->offsetSet('kisiID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiID')) {
        $vKisiId = $stripper->offsetGet('kisiID')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
      
    $resDataInsert = $BLL->gidenMesajListesi(array( 
        'url' => $_GET['url'],  
        'KisiID' => $vKisiId,  
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(   
            "MesajID" =>   ($menu["MesajID"]), 
            "MesajOncelikID" =>   ($menu["MesajOncelikID"]), 
            "Konu" =>   html_entity_decode($menu["Konu"]), 
            "Tarih" =>   ($menu["Tarih"]), 
            "ReceiverNames" =>   html_entity_decode($menu["ReceiverNames"]), 
            "RowNum" =>   ($menu["RowNum"]),             
          
           
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);
/**
 *  * Okan CIRAN
 * @since 23.10.2017
 */
$app->get("/GelenMesajDetay_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vMesajID = NULL;     
    if (isset($_GET['mesajID'])) {
        $stripper->offsetSet('mesajID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['mesajID']));
    } 
    $vKisiId = NULL;     
    if (isset($_GET['kisiID'])) {
        $stripper->offsetSet('kisiID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue(); }
    if ($stripper->offsetExists('kisiID')) 
        {$vKisiId = $stripper->offsetGet('kisiID')->getFilterValue(); }      
    if ($stripper->offsetExists('mesajID')) {
        $vMesajID = $stripper->offsetGet('mesajID')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
      
    $resDataInsert = $BLL->GelenMesajDetay(array( 
        'url' => $_GET['url'],  
        'MesajID' => $vMesajID,  
        'KisiID' => $vKisiId,   
        'Cid' => $vCid,
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "MesajID" =>   ($menu["MesajID"]), 
         //   "ReceiverID" =>   ($menu["ReceiverID"]), 
          //  "Okundu" =>   ($menu["Okundu"]),             
          //  "OkunduguTarih" =>    ($menu["OkunduguTarih"]),
          //  "Silindi" =>    ($menu["Silindi"]), 
         //   "MesajOncelikID" =>   ($menu["MesajOncelikID"]), 
            "Tarih" =>   ($menu["Tarih"]), 
        //    "SenderID" =>   ($menu["SenderID"]), 
         //   "RowNum" =>   ($menu["RowNum"]),  
            "Konu" =>   html_entity_decode($menu["Konu"]), 
            "Mesaj" =>   html_entity_decode($menu["Mesaj"]), 
        //    "SenderAdi" =>   html_entity_decode($menu["SenderAdi"]), 
        //    "SenderSoyadi" =>   html_entity_decode($menu["SenderSoyadi"]), 
            "SenderAdiSoyadi" =>   html_entity_decode($menu["SenderAdiSoyadi"]), 
             
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);
/**
 *  * Okan CIRAN
 * @since 24.10.2017
 */
$app->get("/OdevListesiOgretmen_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vOgretmenID= NULL;     
    if (isset($_GET['ogretmenID'])) {
        $stripper->offsetSet('ogretmenID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogretmenID']));
    }  
        
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }  
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    } 
    if ($stripper->offsetExists('ogretmenID')) {
        $vOgretmenID = $stripper->offsetGet('ogretmenID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    $resDataInsert = $BLL->odevListesiOgretmen(array( 
        'url' => $_GET['url'],  
        'OgretmenID' => $vOgretmenID,   
        'DersYiliID' => $vdersYiliID,    
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "OdevTanimID" =>   ($menu["OdevTanimID"]), 
            "OgretmenAdi" =>   html_entity_decode($menu["OgretmenAdi"]), 
            "SinifKodu" =>   html_entity_decode($menu["SinifKodu"]), 
            "SeviyeID" =>   ($menu["SeviyeID"]), 
            "SeviyeAdi" =>   html_entity_decode($menu["SeviyeAdi"]), 
            "DersBilgisi" =>   html_entity_decode($menu["DersBilgisi"]), 
            "Tanim" =>   html_entity_decode($menu["Tanim"]),
            "Tarih" =>   ($menu["Tarih"]),             
            "TeslimTarihi" =>    ($menu["TeslimTarihi"]),
            "OdevTipID" =>    ($menu["OdevTipID"]), 
            "TanimDosyaAdi" =>   html_entity_decode($menu["TanimDosyaAdi"]), 
            "TanimDosyaID" =>   ($menu["TanimDosyaID"]), 
            "TanimYuklemeTarihi" =>   ($menu["TanimYuklemeTarihi"]), 
            "TanimDosya" =>   ($menu["TanimDosya"]), 
            "TanimBoyut" =>   ($menu["TanimBoyut"]),
            "VerildigiOgrenciSayisi" =>   ($menu["VerildigiOgrenciSayisi"]), 
            "BakanOgrenciSayisi" =>   ($menu["BakanOgrenciSayisi"]),  
            "YapanOgrenciSayisi" =>   ($menu["YapanOgrenciSayisi"]),  
            "OnayOgrenciSayisi" =>   ($menu["OnayOgrenciSayisi"]),  
             
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);
 
 
/**
 *  * Okan CIRAN
 * @since 24.10.2017
 */
$app->get("/OdevListesiOgrenciveYakin_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    
    $vOgrenciID= NULL;     
    if (isset($_GET['ogrenciID'])) {
        $stripper->offsetSet('ogrenciID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogrenciID']));
    }  
        
    $vEgitimYilID= NULL;     
    if (isset($_GET['egitimYilID'])) {
        $stripper->offsetSet('egitimYilID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['egitimYilID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('egitimYilID')) {
        $vEgitimYilID = $stripper->offsetGet('egitimYilID')->getFilterValue();
    }
  
    if ($stripper->offsetExists('ogrenciID')) {
        $vOgrenciID = $stripper->offsetGet('ogrenciID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    $resDataInsert = $BLL->odevListesiOgrenciveYakin(array( 
        'url' => $_GET['url'],  
        'OgrenciID' => $vOgrenciID,   
        'EgitimYilID' => $vEgitimYilID, 
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "OgrenciOdevID" =>   ($menu["OdevTanimID"]), 
          //  "OgrenciID" =>   ($menu["OgrenciID"]), 
          // "OdevTanimID" =>   ($menu["OdevTanimID"]),   
            "OgrenciCevap" =>   html_entity_decode($menu["OgrenciCevap"]), 
            "OgrenciGordu" =>    ($menu["OgrenciGordu"]),
         //   "OgrenciOnay" =>    ($menu["OgrenciOnay"]),
            "OgrenciTeslimTarihi" =>   ($menu["OgrenciTeslimTarihi"]), 
            "OgretmenDegerlendirme" =>   html_entity_decode($menu["OgretmenDegerlendirme"]), 
        //    "OdevOnayID" =>   ($menu["OdevOnayID"]),  
            "OgretmenAdi" =>   html_entity_decode($menu["OgretmenAdi"]),  
            "DersAdi" =>   html_entity_decode($menu["DersAdi"]),  
            "Tanim" =>   html_entity_decode($menu["Tanim"]), 
            "Tarih" =>   ($menu["Tarih"]), 
            "TeslimTarihi" =>   ($menu["TeslimTarihi"]),  
            "Aciklama" =>   html_entity_decode($menu["Aciklama"]), 
            
        );
    } 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);



/**
 *  * Okan CIRAN
 * @since 24.10.2017
 */
$app->get("/OdevListesiKurumYoneticisi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
     
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }   
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataInsert = $BLL->odevListesiKurumYoneticisi(array( 
        'url' => $_GET['url'],   
        'DersYiliID' => $vdersYiliID,  
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "OdevTanimID" =>   ($menu["OdevTanimID"]), 
            "OgretmenAdi" =>   html_entity_decode($menu["OgretmenAdi"]), 
            "SinifKodu" =>   html_entity_decode($menu["SinifKodu"]),  
            "SeviyeAdi" =>   html_entity_decode($menu["SeviyeAdi"]), 
            "DersBilgisi" =>   html_entity_decode($menu["DersBilgisi"]), 
            "Tanim" =>   html_entity_decode($menu["Tanim"]),
            "Tarih" =>   ($menu["Tarih"]),             
            "TeslimTarihi" =>    ($menu["TeslimTarihi"]), 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);

/**
 *  * Okan CIRAN
 * @since 24.10.2017
 */
$app->get("/OgretmenDersProgramiListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
     
    $vdersYiliID= NULL;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    } 
    $vOgretmenID= NULL;     
    if (isset($_GET['ogretmenID'])) {
        $stripper->offsetSet('ogretmenID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogretmenID']));
    }  
    $vDonemID= NULL;     
    if (isset($_GET['donemID'])) {
        $stripper->offsetSet('donemID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['donemID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    }
    if ($stripper->offsetExists('ogretmenID')) {
        $vOgretmenID = $stripper->offsetGet('ogretmenID')->getFilterValue();
    }
    if ($stripper->offsetExists('donemID')) {
        $vDonemID = $stripper->offsetGet('donemID')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataInsert = $BLL->ogretmenDersProgramiListesi(array( 
        'url' => $_GET['url'],   
        'DersYiliID' => $vdersYiliID,   
        'OgretmenID' => $vOgretmenID,   
        'DonemID' => $vDonemID,   
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "HaftaGunu" =>   ($menu["HaftaGunu"]), 
            "DersSirasi" =>    ($menu["DersSirasi"]), 
            "SinifDersID" =>    ($menu["SinifDersID"]),  
            "DersAdi" =>   html_entity_decode($menu["DersAdi"]), 
            "SinifKodu" =>   html_entity_decode($menu["SinifKodu"]), 
            "SubeGrupID" =>    ($menu["SubeGrupID"]),
            "BaslangicSaati" =>   ($menu["BaslangicSaati"]),             
            "BitisSaati" =>    ($menu["BitisSaati"]),  
            "DersBaslangicBitisSaati" =>    ($menu["DersBaslangicBitisSaati"]), 
            "SinifOgretmenID" =>    ($menu["SinifOgretmenID"]), 
            "DersHavuzuID" =>    ($menu["DersHavuzuID"]), 
            "SinifID" =>    ($menu["SinifID"]), 
            "DersID" =>    ($menu["DersID"]),  
            "aciklama" =>   html_entity_decode($menu["Aciklama"]), 
            
            
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);
 
/**
 *  * Okan CIRAN
 * @since 24.10.2017
 */
$app->get("/OgrenciVeYakiniDersProgramiListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
     
    $vogrenciSeviyeID= NULL;     
    if (isset($_GET['ogrenciSeviyeID'])) {
        $stripper->offsetSet('ogrenciSeviyeID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogrenciSeviyeID']));
    } 
    $vKisiID= NULL;     
    if (isset($_GET['ogrenciID'])) {
        $stripper->offsetSet('ogrenciID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogrenciID']));
    } 
    $vsinifID= NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    }  
    $vDonemID= NULL;     
    if (isset($_GET['donemID'])) {
        $stripper->offsetSet('donemID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['donemID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('ogrenciSeviyeID')) {
        $vogrenciSeviyeID = $stripper->offsetGet('ogrenciSeviyeID')->getFilterValue();
    }
    if ($stripper->offsetExists('ogrenciID')) {
        $vKisiID = $stripper->offsetGet('ogrenciID')->getFilterValue();
    }
    if ($stripper->offsetExists('sinifID')) {
        $vsinifID = $stripper->offsetGet('sinifID')->getFilterValue();
    }
    if ($stripper->offsetExists('donemID')) {
        $vDonemID = $stripper->offsetGet('donemID')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataInsert = $BLL->ogrenciVeYakiniDersProgramiListesi(array( 
        'url' => $_GET['url'],   
        'OgrenciSeviyeID' => $vogrenciSeviyeID,   
        'OgrenciID' => $vKisiID,  
        'SinifID' => $vsinifID,   
        'DonemID' => $vDonemID,  
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "BaslangicSaati" =>   ($menu["BaslangicSaati"]), 
            "BitisSaati" =>    ($menu["BitisSaati"]), 
            "DersSaati" =>    ($menu["DersSaati"]),  
            "DersSirasi" =>    ($menu["DersSirasi"]),
            "Gun1_ders" =>   html_entity_decode($menu["Gun1_ders"]), 
            "Gun2_ders" =>   html_entity_decode($menu["Gun2_ders"]),  
            "Gun3_ders" =>   html_entity_decode($menu["Gun3_ders"]), 
            "Gun4_ders" =>   html_entity_decode($menu["Gun4_ders"]), 
            "Gun5_ders" =>   html_entity_decode($menu["Gun5_ders"]), 
            "Gun6_ders" =>   html_entity_decode($menu["Gun6_ders"]), 
            "Gun7_ders" =>   html_entity_decode($menu["Gun7_ders"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);
 
/**
 * Okan CIRAN
 * @since 26-09-2017 
 */
$app->get("/KurumPersoneliSinifListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vdersYiliID = '-1';     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
        $resDataMenu = $BLL->kurumPersoneliSinifListesi(array(      
                                            'DersYiliID' => $vdersYiliID,  
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "SinifID" => $menu["SinifID"], 
             "SinifKodu" => html_entity_decode($menu["SinifKodu"]), 
             "SinifAdi" => html_entity_decode($menu["SinifAdi"]), 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 
/**
 *  * Okan CIRAN
 * @since 24.10.2017
 */
$app->get("/KurumPersoneliDersProgramiListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
      
    $vsinifID= NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    }  
    $vDonemID= NULL;     
    if (isset($_GET['donemID'])) {
        $stripper->offsetSet('donemID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['donemID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }  
    if ($stripper->offsetExists('sinifID')) {
        $vsinifID = $stripper->offsetGet('sinifID')->getFilterValue();
    }
    if ($stripper->offsetExists('donemID')) {
        $vDonemID = $stripper->offsetGet('donemID')->getFilterValue();
    }
    
    $resDataInsert = $BLL->kurumPersoneliDersProgramiListesi(array( 
        'url' => $_GET['url'],    
        'SinifID' => $vsinifID,   
        'DonemID' => $vDonemID,  
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(  
            "BaslangicSaati" =>   ($menu["BaslangicSaati"]), 
            "BitisSaati" =>    ($menu["BitisSaati"]), 
            "DersSaati" =>    ($menu["DersSaati"]),  
            "DersSirasi" =>    ($menu["DersSirasi"]),
            "Gun1_ders" =>   html_entity_decode($menu["Gun1_ders"]), 
            "Gun2_ders" =>   html_entity_decode($menu["Gun2_ders"]),  
            "Gun3_ders" =>   html_entity_decode($menu["Gun3_ders"]), 
            "Gun4_ders" =>   html_entity_decode($menu["Gun4_ders"]), 
            "Gun5_ders" =>   html_entity_decode($menu["Gun5_ders"]), 
            "Gun6_ders" =>   html_entity_decode($menu["Gun6_ders"]), 
            "Gun7_ders" =>   html_entity_decode($menu["Gun7_ders"]),  
            
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
} 
);

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/SinifSeviyeleriCombo_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vdersYiliID= null;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    } 
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
        $resDataMenu = $BLL->sinifSeviyeleriCombo(array(      
                                            'DersYiliID' => $vdersYiliID,  
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "SeviyeID" => $menu["SeviyeID"], 
             "SeviyeAdi" => html_entity_decode($menu["SeviyeAdi"]),   
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/SinifSeviyeleri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vdersYiliID= null;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    }
    $vseviyeID= null;     
    if (isset($_GET['seviyeID'])) {
        $stripper->offsetSet('seviyeID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['seviyeID']));
    } 
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    } 
     if ($stripper->offsetExists('seviyeID')) {
        $vseviyeID = $stripper->offsetGet('seviyeID')->getFilterValue();
    }  
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->sinifSeviyeleri(array(      
                                            'DersYiliID' => $vdersYiliID, 
                                            'SeviyeID' => $vseviyeID, 
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "SinifID" => $menu["SinifID"], 
             "DersYiliID" => $menu["DersYiliID"], 
             "SeviyeID" => $menu["SeviyeID"], 
             "SinifKodu" => html_entity_decode($menu["SinifKodu"]),   
             "SinifAdi" => html_entity_decode($menu["SinifAdi"]),   
             "SinifMevcudu" => $menu["SinifMevcudu"],  
             "HaftalikDersSaati" => html_entity_decode($menu["HaftalikDersSaati"]),   
                 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});



/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/GnlProfil_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vKisiId = NULL;     
    if (isset($_GET['kisiID'])) {
        $stripper->offsetSet('kisiID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiID')) {
        $vKisiId = $stripper->offsetGet('kisiID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
        $resDataMenu = $BLL->gnlProfil(array(      
                                        'KisiID' => $vKisiId, 
                                        'Cid' => $vCid, 
                                        'Did' => $vDid,
                                        'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "KisiID" => $menu["KisiID"], 
             "CinsiyetID" => $menu["CinsiyetID"], 
             "TCKimlikNo" => $menu["TCKimlikNo"], 
             "Adi" => html_entity_decode($menu["Adi"]),   
             "Soyadi" => html_entity_decode($menu["Soyadi"]),   
             "ePosta" => $menu["ePosta"],  
             "AdiSoyadi" => html_entity_decode($menu["AdiSoyadi"]),   
             "Yasamiyor" => $menu["Yasamiyor"], 
             "TCKimlikNo" => $menu["TCKimlikNo"], 
            // EPostaSifresi 
                 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/KurumVePersonelDevamsizlik_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vtarih = NULL;     
    if (isset($_GET['tarih'])) {
        $stripper->offsetSet('tarih', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['tarih']));
    } 
    $vdersYiliID= null;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    } 
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('tarih')) {
        $vtarih = $stripper->offsetGet('tarih')->getFilterValue();
    }
     if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->kurumVePersonelDevamsizlik(array(      
                                            'Tarih' => $vtarih,  
                                            'DersYiliID' => $vdersYiliID, 
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
            "Tarih" => $menu["Tarih"],  
            "Adi" => html_entity_decode($menu["Adi"]),   
            "Soyadi" => html_entity_decode($menu["Soyadi"]), 
            "Adsoyad" => html_entity_decode($menu["adsoyad"] ),
            "Numarasi" => $menu["Numarasi"], 
            "OgrenciDevamsizlikID" => $menu["OgrenciDevamsizlikID"], 
            "DersYiliID" => $menu["DersYiliID"],  
            "DevamsizlikKodID" => $menu["DevamsizlikKodID"], 
            "DevamsizlikPeriyodID" => $menu["DevamsizlikPeriyodID"], 
            "Aciklama" => html_entity_decode($menu["Aciklama"]),  
            "DevamsizlikKodu" => html_entity_decode($menu["DevamsizlikKodu"]),  
            "DevamsizlikAdi" => html_entity_decode($menu["DevamsizlikAdi"]),  
            "DevamsizlikPeriyodu" => html_entity_decode($menu["DevamsizlikPeriyodu"]),  
            "rownum" => $menu["rownum"],   
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});


/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MuhBorcluSozlesmeleri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vOgrenciID = NULL;     
    if (isset($_GET['ogrenciID'])) {
        $stripper->offsetSet('ogrenciID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogrenciID']));
    }   
    $vdersYiliID= null;     
    if (isset($_GET['dersYiliID'])) {
        $stripper->offsetSet('dersYiliID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['dersYiliID']));
    } 
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('dersYiliID')) {
        $vdersYiliID = $stripper->offsetGet('dersYiliID')->getFilterValue();
    } 
    if ($stripper->offsetExists('ogrenciID')) {
        $vOgrenciID = $stripper->offsetGet('ogrenciID')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->muhBorcluSozlesmeleri(array(      
                                            'OgrenciID' => $vOgrenciID,  
                                            'DersYiliID' => $vdersYiliID,   
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
            "OdenecekTutarKDVHaric" => $menu["OdenecekTutarKDVHaric"],  
            "OdenecekKDV" => $menu["OdenecekKDV"],  
             "OdenecekTutarKDVDahil" => $menu["OdenecekTutarKDVDahil"],  
             "YakinTuru" => html_entity_decode($menu["YakinTuru"]),  
             "BorcluBanka" => html_entity_decode($menu["BorcluBanka"]),  
             "TaahhutnameNo" => $menu["TaahhutnameNo"],  
             "IslemNumarasi" => $menu["IslemNumarasi"],  
             "OdemeSekliAciklama" =>html_entity_decode( $menu["OdemeSekliAciklama"]),  
             "TaahhutnameTarihi" => $menu["TaahhutnameTarihi"],  
             "ToplamTutar" => $menu["ToplamTutar"],  
             "Pesinat" => $menu["Pesinat"],  
             "NetTutar" => $menu["NetTutar"],  
             "ToplamOdenen" => $menu["ToplamOdenen"],  
             "KalanTutar" => $menu["KalanTutar"],  
             "ToplamIndirim" => $menu["ToplamIndirim"],  
             "ToplamIndirimYuzdesi" => $menu["ToplamIndirimYuzdesi"],  
             "IndirimliTutar" => $menu["IndirimliTutar"],  
             "PesinatOdemeTarihi" => $menu["PesinatOdemeTarihi"],
             "PesinatAlindi" => $menu["PesinatAlindi"], 
            "SozlesmelerAciklama" => html_entity_decode($menu["SozlesmelerAciklama"]),   
            "BorcluAdiSoyadi" => html_entity_decode($menu["BorcluAdiSoyadi"]), 
            "TaksitSayisi" => $menu["TaksitSayisi"],
            "BorcluSozlesmeID" => $menu["BorcluSozlesmeID"],
             
             
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});


/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MuhBorcluOdemePlani_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vBorcluSozlesmeID = NULL;     
    if (isset($_GET['borcluSozlesmeID'])) {
        $stripper->offsetSet('borcluSozlesmeID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['borcluSozlesmeID']));
    }  
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('borcluSozlesmeID')) {
        $vBorcluSozlesmeID = $stripper->offsetGet('borcluSozlesmeID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataMenu = $BLL->muhBorcluOdemePlani(array(      
                                            'BorcluSozlesmeID' => $vBorcluSozlesmeID,  
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "TaksitNo" => $menu["TaksitNo"],  
             "OdemeTarihi" => $menu["OdemeTarihi"],             
             "TaksitTutari" => $menu["TaksitTutari"],  
             "Odendi" =>  ($menu["Odendi"]),  
             "OdemeAciklamasi" => html_entity_decode($menu["OdemeAciklamasi"]),  
             "Odendi_aciklama" => html_entity_decode($menu["Odendi_aciklama"]),  
             "OdenenTutar" => $menu["OdenenTutar"],  
              
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/DashboarddataDersProgrami_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vRolId = NULL;     
    if (isset($_GET['rolId'])) {
        $stripper->offsetSet('rolId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['rolId']));
    }
    $vKurumID = NULL;     
    if (isset($_GET['kurumID'])) {
        $stripper->offsetSet('kurumID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kurumID']));
    }
    
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('kurumID')) {
        $vKurumID = $stripper->offsetGet('kurumID')->getFilterValue();
    } 
    if ($stripper->offsetExists('rolId')) {
        $vRolId = $stripper->offsetGet('rolId')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataMenu = $BLL->dashboarddataDersProgrami(array(      
                                            'KisiID' => $vkisiId,   
                                            'KurumID' => $vKurumID, 
                                            'RolID' => $vRolId,  
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
            "DersSaati" => $menu["DersSaati"],   
            "SinifAdi" => html_entity_decode($menu["SinifAdi"]),  
            "ogretmen" => html_entity_decode($menu["ogretmen"]),   
            "ogrenci" => html_entity_decode($menu["ogrenci"]),   
             
            "Alan1" => html_entity_decode($menu["Alan1"]),  
            "Alan2" => html_entity_decode($menu["Alan2"]),  
            "Alan3" => html_entity_decode($menu["Alan3"]),  
            "Alan4" => html_entity_decode($menu["Alan4"]),  
            
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/DashboardIconCounts_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vkisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    }
    $vRolId = NULL;     
    if (isset($_GET['rolId'])) {
        $stripper->offsetSet('rolId', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolId']));
    }
    
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
     if ($stripper->offsetExists('kisiId')) {
        $vkisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('rolId')) {
        $vRolId = $stripper->offsetGet('rolId')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataMenu = $BLL->dashboardIconCounts(array(      
                                            'KisiID' => $vkisiId,   
                                            'RolID' => $vRolId,  
                                            'Cid' => $vCid,
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "adet" => $menu["adet"],   
             "tip" => html_entity_decode($menu["tip"]),  
             "aciklama" => html_entity_decode($menu["aciklama"]),   
              "url" => $menu["url"],
             "pageurl" => $menu["pageurl"],
        );
    }
 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

 /** 
 *  * Okan CIRAN
 * @since 05.10.2017
 * rest servislere eklendi
 */
$app->get("/SendMesajDefault_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('mblLoginBLL');  
    $headerParams = $app->request()->headers();
      
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    } 
    $vReceiveKisiID = NULL;     
    if (isset($_GET['receiveKisiID'])) {
        $stripper->offsetSet('receiveKisiID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['receiveKisiID']));
    } 
    $vKonu = NULL;     
    if (isset($_GET['konu'])) {
        $stripper->offsetSet('konu', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['konu']));
    }
    $vMesaj = NULL;     
    if (isset($_GET['mesaj'])) {
        $stripper->offsetSet('mesaj', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['mesaj']));
    } 
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vMesajTipID = NULL;   
    if (isset($_GET['mesajTipID'])) {
        $stripper->offsetSet('mesajTipID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['mesajTipID']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
    if ($stripper->offsetExists('konu')) 
        {$vKonu = $stripper->offsetGet('konu')->getFilterValue();         
    }  
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('receiveKisiID')) {
        $vReceiveKisiID = $stripper->offsetGet('receiveKisiID')->getFilterValue();
    }
    if ($stripper->offsetExists('mesaj')) {
        $vMesaj = $stripper->offsetGet('mesaj')->getFilterValue();
    } 
    if ($stripper->offsetExists('mesajTipID')) {
        $vMesajTipID = $stripper->offsetGet('mesajTipID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
   
    $resDataInsert = $BLL->sendMesajDefault(array(
            'KisiID' => $vKisiId,  
            'ReceiveKisiID' => $vReceiveKisiID,  
            'Konu' => $vKonu, 
            'Mesaj' => $vMesaj,  
            'MesajTipID' => $vMesajTipID, 
            'Cid' => $vCid,
            'Did' => $vDid,
            'LanguageID' => $vLanguageID, 
             ));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert)); 
    
}
);


/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MuhYapilacakTahsilatlarA_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vKurumID = NULL;     
    if (isset($_GET['kurumID'])) {
        $stripper->offsetSet('kurumID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kurumID']));
    } 
    
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
     if ($stripper->offsetExists('kurumID')) {
        $vKurumID = $stripper->offsetGet('kurumID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
      
    $resDataMenu = $BLL->muhYapilacakTahsilatlarA(array(      
                                            'KurumID' => $vKurumID,   
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
            "Tahsilat" => $menu["Tahsilat"],   
            "Gelecek" => html_entity_decode($menu["Gelecek"]),  
            "TahsilatAciklama" => html_entity_decode($menu["TahsilatAciklama"]),   
            "GelecekAciklama" => html_entity_decode($menu["GelecekAciklama"]),   
            "YapilacakTahsilat" => $menu["YapilacakTahsilat"],
            "YapilanTahsilat" => $menu["YapilanTahsilat"],
            "ToplamPesinat" => $menu["ToplamPesinat"],  
        );
    }
 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MuhYapilacakTahsilatlarB_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vKurumID = NULL;     
    if (isset($_GET['kurumID'])) {
        $stripper->offsetSet('kurumID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kurumID']));
    } 
    
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
     if ($stripper->offsetExists('kurumID')) {
        $vKurumID = $stripper->offsetGet('kurumID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
   
    $resDataMenu = $BLL->muhYapilacakTahsilatlarB(array(      
                                            'KurumID' => $vKurumID,   
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array( 
            "Tahsilat" => html_entity_decode($menu["Tahsilat"]),  
            "Adet" => html_entity_decode($menu["Adet"]),   
            "Aciklama" => html_entity_decode($menu["Aciklama"]),   
            "ToplamTutar" => $menu["ToplamTutar"], 
        );
    }
 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});


/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MuhYapilacakTahsilatlarC_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $vKurumID = NULL;     
    if (isset($_GET['kurumID'])) {
        $stripper->offsetSet('kurumID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kurumID']));
    } 
    
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
     if ($stripper->offsetExists('kurumID')) {
        $vKurumID = $stripper->offsetGet('kurumID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
      
    $resDataMenu = $BLL->muhYapilacakTahsilatlarC(array(      
                                            'KurumID' => $vKurumID,   
                                            'Cid' => $vCid, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array( 
            "Aciklama" => html_entity_decode($menu["Aciklama"]),  
            "TaksitTutari" => html_entity_decode($menu["TaksitTutari"]),   
            "TaksitNo" => html_entity_decode($menu["TaksitNo"]),   
            "TCKimlikNo" => html_entity_decode($menu["TCKimlikNo"]), 
            "OgrenciAdi" => html_entity_decode($menu["OgrenciAdi"]),  
        );
    }
 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OdevTipleri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    $resDataMenu = $BLL->odevTipleri(array(  
                                            'Cid' => $vCid,
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "OdevTipID" => $menu["OdevTipID"],  
             "OdevTipi" => html_entity_decode($menu["OdevTipi"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MesajTipleri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    $resDataMenu = $BLL->mesajTipleri(array(  
                                            'Cid' => $vCid, 
                                            'RolID' => $vRolID,
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "MesajTipID" => $menu["MesajTipID"],  
             "Aciklama" => html_entity_decode($menu["Aciklama"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});


 /** 
 *  * Okan CIRAN
 * @since 05.10.2017
 * rest servislere eklendi
 */
$app->get("/OdevAtama_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('mblLoginBLL');  
    $headerParams = $app->request()->headers();
      
 
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vSinifDersID = NULL;     
    if (isset($_GET['sinifDersID'])) {
        $stripper->offsetSet('sinifDersID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifDersID']));
    } 
    $vOgretmenID = NULL;     
    if (isset($_GET['ogretmenID'])) {
        $stripper->offsetSet('ogretmenID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogretmenID']));
    }
    $vTarih = NULL;     
    if (isset($_GET['tarih'])) {
        $stripper->offsetSet('tarih', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['tarih']));
    } 
    $vTanim = NULL;     
    if (isset($_GET['tanim'])) {
        $stripper->offsetSet('tanim', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['tanim']));
    } 
    $vAciklama = NULL;     
    if (isset($_GET['aciklama'])) {
        $stripper->offsetSet('aciklama', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['aciklama']));
    } 
    $vTeslimTarihi = NULL;     
    if (isset($_GET['teslimTarihi'])) {
        $stripper->offsetSet('teslimTarihi', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['teslimTarihi']));
    } 
    $vOdevTipID = NULL;     
    if (isset($_GET['odevTipID'])) {
        $stripper->offsetSet('odevTipID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['odevTipID']));
    } 
    $vNotIleDegerlendirilsin = NULL;     
    if (isset($_GET['notIleDegerlendirilsin'])) {
        $stripper->offsetSet('notIleDegerlendirilsin', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['notIleDegerlendirilsin']));
    } 
    $vDonemNotunaEtkiEtsin = NULL;     
    if (isset($_GET['donemNotunaEtkiEtsin'])) {
        $stripper->offsetSet('donemNotunaEtkiEtsin', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['donemNotunaEtkiEtsin']));
    } 
    $vXmlData = NULL;     
    if (isset($_GET['XmlData'])) {
        $stripper->offsetSet('XmlData', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_JASON_LVL1, 
                $app, $_GET['XmlData']));
    }
    
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
    if ($stripper->offsetExists('sinifDersID')) 
        {$vSinifDersID = $stripper->offsetGet('sinifDersID')->getFilterValue();         
    }  
    if ($stripper->offsetExists('ogretmenID')) {
        $vOgretmenID = $stripper->offsetGet('ogretmenID')->getFilterValue();
    }
    if ($stripper->offsetExists('tarih')) {
        $vTarih = $stripper->offsetGet('tarih')->getFilterValue();
    } 
    if ($stripper->offsetExists('tanim')) {
        $vTanim = $stripper->offsetGet('tanim')->getFilterValue();
    } 
    if ($stripper->offsetExists('aciklama')) {
        $vAciklama = $stripper->offsetGet('aciklama')->getFilterValue();
    } 
    if ($stripper->offsetExists('teslimTarihi')) {
        $vTeslimTarihi = $stripper->offsetGet('teslimTarihi')->getFilterValue();
    } 
    if ($stripper->offsetExists('odevTipID')) {
        $vOdevTipID = $stripper->offsetGet('odevTipID')->getFilterValue();
    }
    if ($stripper->offsetExists('notIleDegerlendirilsin')) {
        $vNotIleDegerlendirilsin = $stripper->offsetGet('notIleDegerlendirilsin')->getFilterValue();
    }
    if ($stripper->offsetExists('donemNotunaEtkiEtsin')) {
        $vDonemNotunaEtkiEtsin = $stripper->offsetGet('donemNotunaEtkiEtsin')->getFilterValue();
    }
    if ($stripper->offsetExists('XmlData')) {
        $vXmlData = $stripper->offsetGet('XmlData')->getFilterValue();
    }
    $resDataInsert = $BLL->odevAtama(array(
            'SinifDersID' => $vSinifDersID,  
            'OgretmenID' => $vOgretmenID, 
            'Tarih' => $vTarih,  
            'Tanim' => $vTanim,  
            'Aciklama' => $vAciklama,  
            'TeslimTarihi' => $vTeslimTarihi,  
            'OdevTipID' => $vOdevTipID,  
            'NotIleDegerlendirilsin' => $vNotIleDegerlendirilsin,  
            'DonemNotunaEtkiEtsin' => $vDonemNotunaEtkiEtsin,  
            'Cid' => $vCid, 
            'XmlData' => $vXmlData, 
            'Did' => $vDid,
             ));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert)); 
    
}
);


/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OgrenciKarnesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vogrenciID= NULL;     
    if (isset($_GET['ogrenciID'])) {
        $stripper->offsetSet('ogrenciID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogrenciID']));
    } 
    $vDonemID= 1;     
    if (isset($_GET['donemID'])) {
        $stripper->offsetSet('donemID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['donemID']));
    } 
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
    if ($stripper->offsetExists('ogrenciID')) {
        $vogrenciID = $stripper->offsetGet('ogrenciID')->getFilterValue();
    }
    if ($stripper->offsetExists('donemID')) {
        $vDonemID = $stripper->offsetGet('donemID')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    $resDataMenu = $BLL->ogrenciKarnesi(array(  
                                            'Cid' => $vCid, 
                                            'Did' => $vDid, 
                                            'OgrenciID' => $vogrenciID, 
                                            'DonemID' => $vDonemID, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "DersAdi" => html_entity_decode($menu["DersAdi"]),  
             "HaftalikDersSaati" => $menu["HaftalikDersSaati"],  
             "YilSonuPuani" =>  ($menu["YilSonuPuani"]),  
             "Yazili1" =>  ($menu["Yazili1"]),
             "Yazili2" =>  ($menu["Yazili2"]),
             "Yazili3" =>  ($menu["Yazili3"]),
             "Yazili4" =>  ($menu["Yazili4"]), 
             "Yazili5" =>  ($menu["Yazili5"]),
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 


/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MsjGonderilecekRoller_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vKurumID= NULL;     
    if (isset($_GET['kurumID'])) {
        $stripper->offsetSet('kurumID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kurumID']));
    } 
    $vSendrolID= NULL;     
    if (isset($_GET['sendrolID'])) {
        $stripper->offsetSet('sendrolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['sendrolID']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    }
    if ($stripper->offsetExists('sendrolID')) {
        $vSendrolID = $stripper->offsetGet('sendrolID')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
    if ($stripper->offsetExists('kurumID')) {
        $vKurumID = $stripper->offsetGet('kurumID')->getFilterValue();
    }  
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    $resDataMenu = $BLL->msjGonderilecekRoller(array(  
                                            'Cid' => $vCid, 
                                            'KurumID' => $vKurumID, 
                                            'SendrolID' => $vSendrolID, 
                                            'RolID' => $vRolID, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "sendRolID" => $menu["sendRolID"],  
             "kontrol" => $menu["kontrol"], 
             "RolAdi" => html_entity_decode($menu["RolAdi"]),   
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});
 

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MsjIcinOkulListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vSendrolID= NULL;     
    if (isset($_GET['sendrolID'])) {
        $stripper->offsetSet('sendrolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['sendrolID']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    }
    if ($stripper->offsetExists('sendrolID')) {
        $vSendrolID = $stripper->offsetGet('sendrolID')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }  
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    $resDataMenu = $BLL->msjIcinOkulListesi(array(  
                                            'Cid' => $vCid,  
                                            'RolID' => $vRolID, 
                                            'SendrolID' => $vSendrolID, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
            "ID" => $menu["ID"],  
            "aciklama" => html_entity_decode($menu["aciklama"]),  
            "kontrol" => $menu["kontrol"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MsjIcinOkuldakiSinifListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vOkulID= NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    } 
    $vSendrolID= NULL;     
    if (isset($_GET['sendrolID'])) {
        $stripper->offsetSet('sendrolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['sendrolID']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    }
    if ($stripper->offsetExists('sendrolID')) {
        $vSendrolID = $stripper->offsetGet('sendrolID')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    $resDataMenu = $BLL->msjIcinOkuldakiSinifListesi(array(  
                                            'Cid' => $vCid,  
                                            'OkulID' => $vOkulID, 
                                            'SendrolID' => $vSendrolID,
                                            'RolID' => $vRolID,
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "ID" => $menu["ID"],  
             "aciklama" => html_entity_decode($menu["aciklama"]),  
             "kontrol" => $menu["kontrol"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MsjIcinSinifOgrenciVeliListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vSinifID= NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    }  
    $vSendrolID= NULL;     
    if (isset($_GET['sendrolID'])) {
        $stripper->offsetSet('sendrolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['sendrolID']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    } 
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    }
    if ($stripper->offsetExists('sendrolID')) {
        $vSendrolID = $stripper->offsetGet('sendrolID')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }  
    if ($stripper->offsetExists('sinifID')) {
        $vSinifID = $stripper->offsetGet('sinifID')->getFilterValue();
    }
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    }
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    $resDataMenu = $BLL->MsjIcinSinifOgrenciVeliListesi(array(  
                                            'Cid' => $vCid,  
                                            'RolID' => $vRolID, 
                                            'SendrolID' => $vSendrolID, 
                                            'SinifID' => $vSinifID, 
                                            'KisiId' => $vKisiId, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "ID" => $menu["ID"],  
             "aciklama" => html_entity_decode($menu["aciklama"]),  
             "kontrol" => $menu["kontrol"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});


/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MsjIcinPersonelListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vSendrolID= NULL;     
    if (isset($_GET['sendrolID'])) {
        $stripper->offsetSet('sendrolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['sendrolID']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    } 
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    }
    if ($stripper->offsetExists('sendrolID')) {
        $vSendrolID = $stripper->offsetGet('sendrolID')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    
    $resDataMenu = $BLL->msjIcinPersonelListesi(array(  
                                            'Cid' => $vCid,  
                                            'RolID' => $vRolID,  
                                            'SendrolID' => $vSendrolID, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "ID" => $menu["ID"],  
             "aciklama" => html_entity_decode($menu["aciklama"]),  
             "kontrol" => $menu["kontrol"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});



/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/MsjIcinOgretmenListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vSendrolID= NULL;     
    if (isset($_GET['sendrolID'])) {
        $stripper->offsetSet('sendrolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['sendrolID']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    }
    if ($stripper->offsetExists('sendrolID')) {
        $vSendrolID = $stripper->offsetGet('sendrolID')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->msjIcinOgretmenListesi(array(  
                                            'Cid' => $vCid,  
                                            'RolID' => $vRolID,  
                                            'SendrolID' => $vSendrolID, 
                                            'KisiId' => $vKisiId, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "ID" => $menu["ID"],  
             "aciklama" => html_entity_decode($menu["aciklama"]),  
             "kontrol" => $menu["kontrol"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/Msjcombo1_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    } 
    $vKurumID = NULL;     
    if (isset($_GET['kurumID'])) {
        $stripper->offsetSet('kurumID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kurumID']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('kurumID')) {
        $vKurumID = $stripper->offsetGet('kurumID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->msjcombo1(array(  
                                            'Cid' => $vCid,  
                                            'RolID' => $vRolID,   
                                            'KisiId' => $vKisiId, 
                                            'KurumID' => $vKurumID,
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "sendRolID" => $menu["sendRolID"],  
             "RolAdi" => html_entity_decode($menu["RolAdi"]),  
             "kontrol" => $menu["kontrol"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/Msjcombo2_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vSendrolID= NULL;     
    if (isset($_GET['sendrolID'])) {
        $stripper->offsetSet('sendrolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['sendrolID']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    } 
    $vKurumID = NULL;     
    if (isset($_GET['kurumID'])) {
        $stripper->offsetSet('kurumID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kurumID']));
    }  
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    } 
    if ($stripper->offsetExists('sendrolID')) {
        $vSendrolID = $stripper->offsetGet('sendrolID')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('kurumID')) {
        $vKurumID = $stripper->offsetGet('kurumID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->msjcombo2(array(  
                                            'Cid' => $vCid,  
                                            'RolID' => $vRolID,  
                                            'SendrolID' => $vSendrolID, 
                                            'KisiId' => $vKisiId, 
                                            'KurumID' => $vKurumID, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "ID" => $menu["ID"],  
             "aciklama" => html_entity_decode($menu["aciklama"]),  
             "kontrol" => $menu["kontrol"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/Msjcombo3_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vSendrolID= NULL;     
    if (isset($_GET['sendrolID'])) {
        $stripper->offsetSet('sendrolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['sendrolID']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    } 
    $vOkulID = NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    } 
    if ($stripper->offsetExists('sendrolID')) {
        $vSendrolID = $stripper->offsetGet('sendrolID')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->msjcombo3(array(  
                                            'Cid' => $vCid,  
                                            'RolID' => $vRolID,  
                                            'SendrolID' => $vSendrolID, 
                                            'KisiId' => $vKisiId, 
                                            'OkulID' => $vOkulID, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "ID" => $menu["ID"],  
             "aciklama" => html_entity_decode($menu["aciklama"]),  
             "kontrol" => $menu["kontrol"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/Msjcombo4_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vSendrolID= NULL;     
    if (isset($_GET['sendrolID'])) {
        $stripper->offsetSet('sendrolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['sendrolID']));
    }
    $vRolID= NULL;     
    if (isset($_GET['rolID'])) {
        $stripper->offsetSet('rolID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rolID']));
    }
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    } 
    $vSinifID = NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    }  
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('rolID')) {
        $vRolID = $stripper->offsetGet('rolID')->getFilterValue();
    } 
    if ($stripper->offsetExists('sendrolID')) {
        $vSendrolID = $stripper->offsetGet('sendrolID')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinifID')) {
        $vSinifID = $stripper->offsetGet('sinifID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->msjcombo4(array(  
                                            'Cid' => $vCid,  
                                            'RolID' => $vRolID,  
                                            'SendrolID' => $vSendrolID, 
                                            'KisiId' => $vKisiId, 
                                            'SinifID' => $vSinifID, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "ID" => $menu["ID"],  
             "aciklama" => html_entity_decode($menu["aciklama"]),  
             "kontrol" => $menu["kontrol"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/Ogretmensubelistesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
     
    $vKisiId = NULL;     
    if (isset($_GET['ogretmenID'])) {
        $stripper->offsetSet('ogretmenID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogretmenID']));
    } 
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('ogretmenID')) {
        $vKisiId = $stripper->offsetGet('ogretmenID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }    
    
    $resDataMenu = $BLL->ogretmensubelistesi(array(  
                                            'Cid' => $vCid, 
                                            'OgretmenID' => $vKisiId,  
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "SinifID" => $menu["SinifID"],  
             "Aciklama" => html_entity_decode($menu["Aciklama"]), 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OgretmenSinavDersleriListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
     
    $vSinavID = NULL;     
    if (isset($_GET['sinavID'])) {
        $stripper->offsetSet('sinavID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinavID']));
    } 
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinavID')) {
        $vSinavID = $stripper->offsetGet('sinavID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    
    $resDataMenu = $BLL->ogretmenSinavDersleriListesi(array(  
                                            'Cid' => $vCid, 
                                            'SinavID' => $vSinavID,  
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "SinavDersID" => $menu["SinavDersID"],  
             "BolumKategoriAdi" => html_entity_decode($menu["BolumKategoriAdi"]), 
             "DersAdi" => html_entity_decode($menu["DersAdi"]),
             "DersSoruSayisi" =>  ($menu["DersSoruSayisi"]),
           // "DersSoruSayisi" => html_entity_decode($menu["DersSoruSayisi"]),
            
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OgretmenSinavaGirenSubeler_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
     
    $vSinavID = NULL;     
    if (isset($_GET['sinavID'])) {
        $stripper->offsetSet('sinavID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinavID']));
    } 
    $vKisiId = NULL;     
    if (isset($_GET['ogretmenID'])) {
        $stripper->offsetSet('ogretmenID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogretmenID']));
    } 
    $vOkulID = NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinavID')) {
        $vSinavID = $stripper->offsetGet('sinavID')->getFilterValue();
    } 
     if ($stripper->offsetExists('ogretmenID')) {
        $vKisiId = $stripper->offsetGet('ogretmenID')->getFilterValue();
    } 
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    
    $resDataMenu = $BLL->OgretmenSinavaGirenSubeler(array(  
                                            'Cid' => $vCid, 
                                            'SinavID' => $vSinavID,  
                                            'OgretmenID' => $vKisiId, 
                                            'OkulID' => $vOkulID, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
             "SinifKodu" => html_entity_decode($menu["SinifKodu"]),  
             "IlAdi" => html_entity_decode($menu["IlAdi"]), 
             "IlceAdi" => html_entity_decode($menu["IlceAdi"]),
             "OkulAdi" =>  html_entity_decode($menu["OkulAdi"]),
             "SinavOkulID" => $menu["SinavOkulID"],  
             "OgrenciSayisi" => $menu["OgrenciSayisi"],  
             "OkulOgrenciSayisi" => $menu["OkulOgrenciSayisi"],  
             "MEBKodu" => html_entity_decode($menu["MEBKodu"]),
             "DersYiliID" => $menu["DersYiliID"],  
             "SinifID" => $menu["SinifID"],   
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 
/**
 *  * Okan CIRAN
 * @since 03.10.2017
 */
$app->get("/KyOgretmenOdevListeleri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
     
    $vOkulID = NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    }
       
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
   $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) {
        $vLanguageID = $stripper->offsetGet('languageID')->getFilterValue();
    }
    
   
    $resDataInsert = $BLL->kyOgretmenOdevListeleri(array( 
        'url' => $_GET['url'],  
        'LanguageID' => $vLanguageID,  
        'OkulID' => $vOkulID,    
        'Cid' => $vCid, 
        'Did' => $vDid,
        'LanguageID' => $vLanguageID, 
        ));
 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array( 
            
            "OdevSayisi" => $menu["OdevSayisi"],
            "OgrenciSayisi" => $menu["OgrenciSayisi"],
            "GorenSayisi" => $menu["GorenSayisi"], 
            "AdiSoyadi" => html_entity_decode($menu["AdiSoyadi"]), 
            "Brans" => html_entity_decode($menu["Brans"]), 
            "YapanSayisi" =>  ($menu["YapanSayisi"]),
            "OnaySayisi" =>  ($menu["OnaySayisi"]),
            
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
    
    
    
    
} 
);
 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OgrenciVeliIcinOgretmenListesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->ogrenciVeliIcinOgretmenListesi(array(  
                                            'Cid' => $vCid,   
                                            'KisiId' => $vKisiId, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
            "OgretmenID" => $menu["OgretmenID"],  
            "aciklama" => html_entity_decode($menu["aciklama"]),  
            "DersAdi" => html_entity_decode($menu["DersAdi"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
  
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OgrencininAldigiNotlar_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vKisiId = NULL;     
    if (isset($_GET['kisiId'])) {
        $stripper->offsetSet('kisiId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiId']));
    } 
    $vDonemID = NULL;     
    if (isset($_GET['donemID'])) {
        $stripper->offsetSet('donemID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['donemID']));
    } 
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiId')) {
        $vKisiId = $stripper->offsetGet('kisiId')->getFilterValue();
    } 
    if ($stripper->offsetExists('donemID')) {
        $vDonemID = $stripper->offsetGet('donemID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->ogrencininAldigiNotlar(array(  
                                            'Cid' => $vCid,   
                                            'KisiID' => $vKisiId, 
                                            'DonemID' => $vDonemID,  
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
           
            "Aciklamasi" => html_entity_decode($menu["SinavAciklamasi"]),  
            "Puan" => html_entity_decode($menu["Puan"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OgrencilerinAldigiNotlarSinavBazli_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vSinavID = NULL;     
    if (isset($_GET['sinavID'])) {
        $stripper->offsetSet('sinavID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinavID']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinavID')) {
        $vSinavID = $stripper->offsetGet('sinavID')->getFilterValue();
    }  
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->ogrencilerinAldigiNotlarSinavBazli(array(  
                                            'Cid' => $vCid,   
                                            'SinavID' => $vSinavID, 
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array( 
            "Numarasi" => html_entity_decode($menu["Numarasi"]),  
            "adsoyad" => html_entity_decode($menu["adsoyad"]),  
            "Aciklamasi" => html_entity_decode($menu["SinavAciklamasi"]),  
            "Puan" => html_entity_decode($menu["Puan"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
});
 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OgretmenSinavSorulariKDK_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vSinavDersID = NULL;     
    if (isset($_GET['sinavDersID'])) {
        $stripper->offsetSet('sinavDersID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinavDersID']));
    } 
    $vSinavOgrenciID = NULL;     
    if (isset($_GET['sinavOgrenciID'])) {
        $stripper->offsetSet('sinavOgrenciID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinavOgrenciID']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
     if ($stripper->offsetExists('languageID')) {
        $vLanguageID = $stripper->offsetGet('languageID')->getFilterValue();
    }
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinavDersID')) {
        $vSinavDersID = $stripper->offsetGet('sinavDersID')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinavOgrenciID')) {
        $vSinavOgrenciID = $stripper->offsetGet('sinavOgrenciID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    
    $resDataMenu = $BLL->ogretmenSinavSorulariKDK(array(  
                                            'Cid' => $vCid,   
                                            'SinavDersID' => $vSinavDersID,  
                                            'Did' => $vDid,
                                            'SinavOgrenciID' => $vSinavOgrenciID,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array( 
            "SinavKitapcikSoruID" =>  ($menu["SinavKitapcikSoruID"]),  
            "SinavKitapcikID" =>  ($menu["SinavKitapcikID"]),  
            "SinavSoruID" => ($menu["SinavSoruID"]),  
            "Sira" => ($menu["Sira"]),  
            "SoruPuani" => ($menu["SoruPuani"]),  
            "OgrenciSoruPuani" => ($menu["OgrenciSoruPuani"]),  
            "SoruTurID" => ($menu["SoruTurID"]),   
            "KitapcikTurID" => ($menu["KitapcikTurID"]),  
             "SinavOgrenciID" => ($menu["SinavOgrenciID"]),  
             "SinavOgrenciSoruCevapID" => ($menu["SinavOgrenciSoruCevapID"]),  
             
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
}); 

 /** 
 *  * Okan CIRAN
 * @since 05.10.2017
 * rest servislere eklendi
 */
$app->get("/OgrenciOdeviGordu_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('mblLoginBLL');  
    $headerParams = $app->request()->headers();
      
 
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vOgrenciOdevID = NULL;     
    if (isset($_GET['ogrenciOdevID'])) {
        $stripper->offsetSet('ogrenciOdevID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['ogrenciOdevID']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
    if ($stripper->offsetExists('ogrenciOdevID')) 
        {$vSinifDersID = $stripper->offsetGet('ogrenciOdevID')->getFilterValue();         
    }   
    
    $resDataInsert = $BLL->ogrenciOdeviGordu(array(
            'OgrenciOdevID' => $vOgrenciOdevID,  
            'Cid' => $vCid,  
            'Did' => $vDid,
             ));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert)); 
    
}
);
 
 
/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OdevOnayTipleri_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }   
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
      
    $resDataMenu = $BLL->odevOnayTipleri(array(  
                                            'Cid' => $vCid,   
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array( 
            "OdevOnayID" =>  ($menu["OdevOnayID"]),  
            "OdevOnayi" => html_entity_decode ($menu["OdevOnayi"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
}); 



/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/TopluOgrenciCevap_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vSinavOkulID = NULL;     
    if (isset($_GET['sinavOkulID'])) {
        $stripper->offsetSet('sinavOkulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinavOkulID']));
    } 
    $vSinifKodu = NULL;     
    if (isset($_GET['sinifKodu'])) {
        $stripper->offsetSet('sinifKodu', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifKodu']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
     
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinavOkulID')) {
        $vSinavOkulID = $stripper->offsetGet('sinavOkulID')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinifKodu')) {
        $vSinifKodu = $stripper->offsetGet('sinifKodu')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
     
    
    $resDataMenu = $BLL->topluOgrenciCevap(array(  
                                            'Cid' => $vCid,   
                                            'SinavOkulID' => $vSinavOkulID,  
                                            'SinifKodu' => $vSinifKodu,  
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array( 
            "SiraNo" =>  ($menu["SiraNo"]),  
            "SinavKitapcikID" =>  ($menu["SinavKitapcikID"]),  
            "KitapcikTurID" => ($menu["KitapcikTurID"]),  
            "KitapcikAciklamasi" => html_entity_decode($menu["KitapcikAciklamasi"]),  
            "SinifKodu" => ($menu["SinifKodu"]),  
            "SinifID" => ($menu["SinifID"]),  
            "SinavOgrenciID" => ($menu["SinavOgrenciID"]),   
            "SinavSinifID" => ($menu["SinavSinifID"]),  
             "OgrenciSeviyeID" => ($menu["OgrenciSeviyeID"]),  
             "OgrenciID" => ($menu["OgrenciID"]),  
             "SinavOkulID" => ($menu["SinavOkulID"]),  
             "TelafiSinavinaGirecekMi" => ($menu["TelafiSinavinaGirecekMi"]),  
             "OgrenciNumarasi" => ($menu["OgrenciNumarasi"]),  
             "KisiID" => ($menu["KisiID"]),  
             "AdiSoyadi" => html_entity_decode($menu["AdiSoyadi"]),  
             "OgretmenAdiSoyadi" => html_entity_decode($menu["OgretmenAdiSoyadi"]),  
             "TCKimlikNo" => ($menu["TCKimlikNo"]), 
             "TOPLAM_PUAN_1" => ($menu["TOPLAM_PUAN_1"]), 
             "TOPLAM_PUAN_2" => ($menu["TOPLAM_PUAN_2"]), 
             "NOTU" => ($menu["NOTU"]), 
             "TPS" => ($menu["TPS"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
}); 
 
 

/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/SinavdaKullanilanKitaplar_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vSinavOkulID = NULL;     
    if (isset($_GET['sinavOkulID'])) {
        $stripper->offsetSet('sinavOkulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinavOkulID']));
    } 
    $vSinifKodu = NULL;     
    if (isset($_GET['sinifKodu'])) {
        $stripper->offsetSet('sinifKodu', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifKodu']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
     $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
     
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinavOkulID')) {
        $vSinavOkulID = $stripper->offsetGet('sinavOkulID')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinifKodu')) {
        $vSinifKodu = $stripper->offsetGet('sinifKodu')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
    
    $resDataMenu = $BLL->SinavdaKullanilanKitaplar(array(  
                                            'Cid' => $vCid,   
                                            'SinavOkulID' => $vSinavOkulID,  
                                            'SinifKodu' => $vSinifKodu,
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array( 
            
            "SinavKitapcikID" =>  ($menu["SinavKitapcikID"]),  
            "KitapcikTurID" => ($menu["KitapcikTurID"]),  
            "KitapcikAciklamasi" => html_entity_decode($menu["KitapcikAciklamasi"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
}); 

$app->get("/OgrenciSinavitapcikKaydet_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('mblLoginBLL');  
    $headerParams = $app->request()->headers();
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vSinavOgrenciId = NULL;     
    if (isset($_GET['sinavOgrenciId'])) {
        $stripper->offsetSet('sinavOgrenciId', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinavOgrenciId']));
    } 
    $vKitapcikTurID = NULL;     
    if (isset($_GET['kitapcikTurID'])) {
        $stripper->offsetSet('kitapcikTurID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['kitapcikTurID']));
    }  
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('sinavOgrenciId')) {
        $vSinavOgrenciId = $stripper->offsetGet('sinavOgrenciId')->getFilterValue();
    } 
    if ($stripper->offsetExists('kitapcikTurID')) {
        $vKitapcikTurID = $stripper->offsetGet('kitapcikTurID')->getFilterValue();
    } 
      
    $resDataInsert = $BLL->ogrenciSinavitapcikKaydet(array(  
                                            'Cid' => $vCid,   
                                            'SinavOgrenciId' => $vSinavOgrenciId,  
                                            'KitapcikTurID' => $vKitapcikTurID,  
                                            'Did' => $vDid,
                                           ) ); 
    
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert)); 
    
}
); 

 /** 
 *  * Okan CIRAN
 * @since 05.10.2017
 * rest servislere eklendi
 */
$app->get("/OgrenciSinaviSonuclariOnay_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('mblLoginBLL');  
    $headerParams = $app->request()->headers();
      
 
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    } 
    $vSinavID = NULL;     
    if (isset($_GET['sinavID'])) {
        $stripper->offsetSet('sinavID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinavID']));
    } 
     $vSinifID = NULL;     
    if (isset($_GET['sinifID'])) {
        $stripper->offsetSet('sinifID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sinifID']));
    } 
     $vOnay = NULL;     
    if (isset($_GET['onay'])) {
        $stripper->offsetSet('onay', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['onay']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    }
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    }
    if ($stripper->offsetExists('sinavID')) 
        {$vSinavID = $stripper->offsetGet('sinavID')->getFilterValue();         
    }  
    if ($stripper->offsetExists('sinifID')) 
        {$vSinifID = $stripper->offsetGet('sinifID')->getFilterValue();         
    }  
     if ($stripper->offsetExists('onay')) 
        {$vOnay= $stripper->offsetGet('onay')->getFilterValue();         
    }  
    
    $resDataInsert = $BLL->ogrenciSinaviSonuclariOnay(array(
            'SinavID' => $vSinavID,  
            'SinifID' => $vSinifID,  
            'Onay' => $vOnay,  
            'Cid' => $vCid,  
            'Did' => $vDid,
             ));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert)); 
    
}
);

 /** 
 *  * Okan CIRAN
 * @since 05.10.2017
 * rest servislere eklendi
 */
$app->get("/OgrenciSinaviSonuclariKaydet_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('mblLoginBLL');  
    $headerParams = $app->request()->headers();
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vXmlData = NULL;     
    if (isset($_GET['XmlData'])) {
        $stripper->offsetSet('XmlData', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL1, 
                $app, $_GET['XmlData']));
    } 
    
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vKitapcikTurID = NULL;     
    if (isset($_GET['kitapcikTurID'])) {
        $stripper->offsetSet('kitapcikTurID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['kitapcikTurID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('XmlData')) {
        $vXmlData = $stripper->offsetGet('XmlData')->getFilterValue();
    } 
    if ($stripper->offsetExists('kitapcikTurID')) {
        $vKitapcikTurID = $stripper->offsetGet('kitapcikTurID')->getFilterValue();
    }
     
    $vXmlData  =$_GET['XmlData'];
    $resDataInsert = $BLL->OgrenciSinaviSonuclariKaydet(array(  
                                            'Cid' => $vCid,   
                                            'XmlData' => $vXmlData,
                                            'Did' => $vDid,
                                            'KitapcikTurID' => $vKitapcikTurID,
                                           ) ); 
     
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert)); 
    
}
); 


/**
 * Okan CIRAN
 * @since 25-10-2017 
 */
$app->get("/OgrencininSinavlistesi_mbllogin/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();        
    $BLL = $app->getBLLManager()->get('mblLoginBLL'); 
    $headerParams = $app->request()->headers(); 
      
    $vCid = NULL;   
    if (isset($_GET['cid'])) {
        $stripper->offsetSet('cid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['cid']));
    }  
    $vOkulID = NULL;     
    if (isset($_GET['okulID'])) {
        $stripper->offsetSet('okulID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['okulID']));
    } 
    $vKisiID = NULL;     
    if (isset($_GET['kisiID'])) {
        $stripper->offsetSet('kisiID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['kisiID']));
    }  
    $vDid = NULL;   
    if (isset($_GET['did'])) {
        $stripper->offsetSet('did', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['did']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    $stripper->strip();
    if ($stripper->offsetExists('did')) {
        $vDid = $stripper->offsetGet('did')->getFilterValue();
    } 
    if ($stripper->offsetExists('cid')) {
        $vCid = $stripper->offsetGet('cid')->getFilterValue();
    } 
    if ($stripper->offsetExists('okulID')) {
        $vOkulID = $stripper->offsetGet('okulID')->getFilterValue();
    } 
    if ($stripper->offsetExists('kisiID')) {
        $vKisiID = $stripper->offsetGet('kisiID')->getFilterValue();
    } 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }   
      
    $resDataMenu = $BLL->OgrencininSinavlistesi(array(  
                                            'Cid' => $vCid,   
                                            'OkulID' => $vOkulID,  
                                            'KisiID' => $vKisiID,
                                            'Did' => $vDid,
                                            'LanguageID' => $vLanguageID, 
                                           ) ); 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(  
            "SinavID" =>  ($menu["SinavID"]),  
            "OkulID" => ($menu["OkulID"]),  
            "OkulOgretmenID" =>  ($menu["OkulOgretmenID"]),  
            "SinavTurID" =>  ($menu["SinavTurID"]),  
            "sinavTurTanim" => html_entity_decode($menu["sinavTurTanim"]),  
            "SeviyeID" =>  ($menu["SeviyeID"]),  
            "SinavKodu" => html_entity_decode($menu["SinavKodu"]),  
            "SinavAciklamasi" => html_entity_decode($menu["SinavAciklamasi"]),  
            "SinavTarihi" =>  ($menu["SinavTarihi"]),  
            "SinavBitisTarihi" =>  ($menu["SinavBitisTarihi"]),  
            "SinavSuresi" =>  ($menu["SinavSuresi"]),  
            "SinavOgrenciID" => html_entity_decode($menu["SinavOgrenciID"]),  
            "ogretmen" => html_entity_decode($menu["ogretmen"]), 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
  
}); 

$app->run();
