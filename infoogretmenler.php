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
 * @since 2.10.2015
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
* @since 31-01-2018
 */
$app->get("/FillOkulOgretmenleriCmb_infoOgretmenler/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('infoOgretmenlerBLL'); 
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    } 
    
    $vOkulId = NULL;
    if (isset($_GET['OkulId'])) {
        $stripper->offsetSet('OkulId', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['OkulId']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('OkulId')) {$vOkulId = $stripper->offsetGet('OkulId')->getFilterValue(); }
     
    $resCombobox = $BLL->FillOkulOgretmenleriCmb(array(                  
            'okulId' => $vOkulId ,  
            ));

    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" =>  html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" =>  "",  
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" =>  html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => '', //  html_entity_decode($menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    } 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
* @since 31-01-2018
 */
$app->get("/pkFillOgretmenler_infoOgretmenler/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoOgretmenlerBLL');

    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillNobetBilgileri_infonobetprogrami" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];
    $vPage = NULL;
    if (isset($_GET['page'])) {
        $stripper->offsetSet('page', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['page']));
    }
    $vRows = NULL;
    if (isset($_GET['rows'])) {
        $stripper->offsetSet('rows', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                $app, $_GET['rows']));
    }
    $vSort = NULL;
    if (isset($_GET['sort'])) {
        $stripper->offsetSet('sort', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                $app, $_GET['sort']));
    }
    $vOrder = NULL;
    if (isset($_GET['order'])) {
        $stripper->offsetSet('order', $stripChainerFactory->get(stripChainers::FILTER_ONLY_ORDER, 
                $app, $_GET['order']));
    }
    $filterRules = null;
    if (isset($_GET['filterRules'])) {
        $stripper->offsetSet('filterRules', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_JASON_LVL1, 
                $app, $_GET['filterRules']));
    }

    $vOkulId = NULL;
    if (isset($_GET['OkulId'])) {
        $stripper->offsetSet('OkulId', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['OkulId']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('OkulId')) {$vOkulId = $stripper->offsetGet('OkulId')->getFilterValue(); }
    
    if ($stripper->offsetExists('page')) {
        $vPage = $stripper->offsetGet('page')->getFilterValue();
    }
    if ($stripper->offsetExists('rows')) {
        $vRows = $stripper->offsetGet('rows')->getFilterValue();
    }
    if ($stripper->offsetExists('sort')) {
        $vSort = $stripper->offsetGet('sort')->getFilterValue();
    }
    if ($stripper->offsetExists('order')) {
        $vOrder = $stripper->offsetGet('order')->getFilterValue();
    }
    if ($stripper->offsetExists('filterRules')) {
        $filterRules = $stripper->offsetGet('filterRules')->getFilterValue();
    }
    
    $resDataGrid = $BLL->FillOgretmenler(array( 
        'pk' => $pk,
        'okulId' => $vOkulId,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
        'filterRules' => $filterRules,
    ));
  /*  $resTotalRowCount = $BLL->fillNobetBilgileriRtc(array( 
        'pk' => $pk,
        'filterRules' => $filterRules,
    ));
   * 
   */
  //  $counts=0;
    $flows = array(); 
    if (isset($resDataGrid[0]['id'])) {
        foreach ($resDataGrid as $flow) {
            $flows[] = array(
                "Id" => intval($flow["id"]),
                "Ad" => html_entity_decode($flow["ad"]), 
                "Soyad" => html_entity_decode($flow["soyad"]),
                "SbGorevId" => ($flow["sbGorevId"]),
                "Gorevadi" => html_entity_decode($flow["gorevadi"]),
                "SbBransId" => $flow["sbBransId"],
                "BransAdi" => html_entity_decode($flow["bransAdi"]),
                "OgretmenTipId" => $flow["ogretmenTipId"],
                "Ogretmentipi" => html_entity_decode($flow["ogretmentipi"]),
                "YonetimKadrosumu" => $flow["yonetimKadrosumu"],
                "YonetimKadrosumuText" => html_entity_decode($flow["yonetimKadrosumuText"]),
                "Dogumtarihi" => $flow["dogumtarihi"],
                "Tc" => html_entity_decode($flow["tc"]),
                "Active" => intval($flow["active"]),
                "okulId" => intval($flow["okulId"]),
                "Okuladi" => html_entity_decode($flow["okuladi"]),
               "attributes" =>  "",  
                 
            );
        }
      //  $counts = $resTotalRowCount[0]['count'];
    }


    $app->response()->header("Content-Type", "application/json"); 
    $resultArray = array();
   // $resultArray['total'] = $counts;
    $resultArray  = $flows; 
    $app->response()->body(json_encode($resultArray));
});

 
 /**x
 *  * Okan CIRAN
* @since 31-01-2018
 */
$app->get("/pkUpdateMakeActiveOrPassive_infoOgretmenler/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoOgretmenlerBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkUpdateMakeActiveOrPassive_infoOgretmenler" end point, X-Public variable not found');
    }
    $Pk = $headerParams['X-Public'];      
    $vId = NULL;
    if (isset($_GET['Id'])) {
        $stripper->offsetSet('Id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Id']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('Id')) {$vId = $stripper->offsetGet('Id')->getFilterValue(); }
    $resData = $BLL->makeActiveOrPassive(array(                  
            'id' => $vId ,    
            'pk' => $Pk,        
            ));
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resData));
}
); 

/**
 *  * Okan CIRAN
* @since 31-01-2018
 */
$app->get("/pkInsert_infoOgretmenler/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoOgretmenlerBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsert_infoOgretmenler" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vOkulId = NULL;
    if (isset($_GET['OkulId'])) {
        $stripper->offsetSet('OkulId', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['OkulId']));
    } 
    $vAd = NULL;
    if (isset($_GET['Ad'])) {
         $stripper->offsetSet('Ad',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Ad']));
    }
    $vSoyad = NULL;
    if (isset($_GET['Soyad'])) {
         $stripper->offsetSet('Soyad',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Soyad']));
    }
    $vTc = NULL;
    if (isset($_GET['Tc'])) {
         $stripper->offsetSet('Tc',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Tc']));
    }
    $vSbGorevId = NULL;
    if (isset($_GET['SbGorevId'])) {
         $stripper->offsetSet('SbGorevId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['SbGorevId']));
    } 
    $vSbBransId= NULL;
    if (isset($_GET['SbBransId'])) {
         $stripper->offsetSet('SbBransId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['SbBransId']));
    }  
    $vOgretmenTipId= NULL;
    if (isset($_GET['OgretmenTipId'])) {
         $stripper->offsetSet('OgretmenTipId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['OgretmenTipId']));
    }   
    $vDogumtarihi= NULL;
    if (isset($_GET['Dogumtarihi'])) {
         $stripper->offsetSet('Dogumtarihi',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Dogumtarihi']));
    }
   
    $stripper->strip();
    if($stripper->offsetExists('OkulId')) $vOkulId = $stripper->offsetGet('OkulId')->getFilterValue();
    if($stripper->offsetExists('Ad')) $vAd = $stripper->offsetGet('Ad')->getFilterValue();
    if($stripper->offsetExists('Soyad')) $vSoyad = $stripper->offsetGet('Soyad')->getFilterValue();
    if($stripper->offsetExists('Tc')) $vTc = $stripper->offsetGet('Tc')->getFilterValue();
    
     if($stripper->offsetExists('SbGorevId')) $vSbGorevId = $stripper->offsetGet('SbGorevId')->getFilterValue();
    if($stripper->offsetExists('SbBransId')) $vSbBransId = $stripper->offsetGet('SbBransId')->getFilterValue();
    if($stripper->offsetExists('OgretmenTipId')) $vOgretmenTipId = $stripper->offsetGet('OgretmenTipId')->getFilterValue();
    if($stripper->offsetExists('Dogumtarihi')) $vDogumtarihi = $stripper->offsetGet('Dogumtarihi')->getFilterValue();
      
    $resDataInsert = $BLL->insert(array(
            'okulId' => $vOkulId,       
            'ad' => $vAd,          
            'soyad' => $vSoyad,
            'tc' => $vTc, 
            'sbGorevId' => $vSbGorevId,       
            'sbBransId' => $vSbBransId,          
            'ogretmenTipId' => $vOgretmenTipId,
            'dogumtarihi' => $vDogumtarihi,
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
/**
 *  * Okan CIRAN
* @since 31-01-2018
 */
$app->get("/pkUpdate_infonobetprogrami/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoOgretmenlerBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdate_infonobetprogrami" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['Id'])) {
         $stripper->offsetSet('Id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Id']));
    }
   
    $vOkulId = NULL;
    if (isset($_GET['OkulId'])) {
        $stripper->offsetSet('OkulId', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['OkulId']));
    } 
    $vAd = NULL;
    if (isset($_GET['Ad'])) {
         $stripper->offsetSet('Ad',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Ad']));
    }
    $vSoyad = NULL;
    if (isset($_GET['Soyad'])) {
         $stripper->offsetSet('Soyad',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Soyad']));
    }
    $vTc = NULL;
    if (isset($_GET['Tc'])) {
         $stripper->offsetSet('Tc',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Tc']));
    }
    $vSbGorevId = NULL;
    if (isset($_GET['SbGorevId'])) {
         $stripper->offsetSet('SbGorevId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['SbGorevId']));
    } 
    $vSbBransId= NULL;
    if (isset($_GET['SbBransId'])) {
         $stripper->offsetSet('SbBransId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['SbBransId']));
    }  
    $vOgretmenTipId= NULL;
    if (isset($_GET['OgretmenTipId'])) {
         $stripper->offsetSet('OgretmenTipId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['OgretmenTipId']));
    }   
    $vDogumtarihi= NULL;
    if (isset($_GET['Dogumtarihi'])) {
         $stripper->offsetSet('Dogumtarihi',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Dogumtarihi']));
    }
   
    $stripper->strip();
    if($stripper->offsetExists('OkulId')) $vOkulId = $stripper->offsetGet('OkulId')->getFilterValue();
    if($stripper->offsetExists('Ad')) $vAd = $stripper->offsetGet('Ad')->getFilterValue();
    if($stripper->offsetExists('Soyad')) $vSoyad = $stripper->offsetGet('Soyad')->getFilterValue();
    if($stripper->offsetExists('Tc')) $vTc = $stripper->offsetGet('Tc')->getFilterValue();
    
     if($stripper->offsetExists('SbGorevId')) $vSbGorevId = $stripper->offsetGet('SbGorevId')->getFilterValue();
    if($stripper->offsetExists('SbBransId')) $vSbBransId = $stripper->offsetGet('SbBransId')->getFilterValue();
    if($stripper->offsetExists('OgretmenTipId')) $vOgretmenTipId = $stripper->offsetGet('OgretmenTipId')->getFilterValue();
    if($stripper->offsetExists('Dogumtarihi')) $vDogumtarihi = $stripper->offsetGet('Dogumtarihi')->getFilterValue();
      
     
    if($stripper->offsetExists('Id')) $vId = $stripper->offsetGet('Id')->getFilterValue();  
    $resDataInsert = $BLL->update(array(
            'id' => $vId,  
            'okulId' => $vOkulId,       
            'ad' => $vAd,          
            'soyad' => $vSoyad,
            'tc' => $vTc, 
            'sbGorevId' => $vSbGorevId,       
            'sbBransId' => $vSbBransId,          
            'ogretmenTipId' => $vOgretmenTipId,
            'dogumtarihi' => $vDogumtarihi,
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
/**
 *  * Okan CIRAN
* @since 31-01-2018
 */
 
$app->get("/pkDelete_infonobetprogrami/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoOgretmenlerBLL');   
    $headerParams = $app->request()->headers();
    $Pk = $headerParams['X-Public'];  
    $vId = NULL;
    if (isset($_GET['Id'])) {
        $stripper->offsetSet('Id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Id']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('Id')) {$vId = $stripper->offsetGet('Id')->getFilterValue(); }  
    $resDataDeleted = $BLL->Delete(array(                  
            'id' => $vId ,    
            'pk' => $Pk,        
            ));
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataDeleted));
}
); 

$app->run();
