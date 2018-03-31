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
$app->get("/FillNobetBilgileriNowCmb_infonobetprogrami/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('infoNobetProgramiBLL'); 
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    } 
    $resCombobox = $BLL->fillNobetBilgileriNowCmb();

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
$app->get("/pkFillNobetBilgileri_infonobetprogrami/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoNobetProgramiBLL');

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

    $stripper->strip();  
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
    
    $resDataGrid = $BLL->fillNobetBilgileri(array( 
        'pk' => $pk,
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
                "OgrId" => intval($flow["ogrId"]), 
                "Adsoyad" => html_entity_decode($flow["adsoyad"]),
                "NobetYeri" => html_entity_decode($flow["nobetYeri"]),
                "Bastar" => $flow["bastar"],
                "Bittar" => $flow["bittar"],
                "Active" => intval($flow["active"]),
                "Deleted" => intval($flow["deleted"]),
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


/**
 *  * Okan CIRAN
* @since 31-01-2018
 */
$app->get("/pkFillNobetBilgileriDshBrd_infonobetprogrami/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoNobetProgramiBLL');

    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillNobetBilgileriDshBrd_infonobetprogrami" end point, X-Public variable not found');
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

    $stripper->strip();  
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
    
    $resDataGrid = $BLL->fillNobetBilgileriDshBrd(array( 
        'pk' => $pk,
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
                "OgrId" => intval($flow["ogrId"]), 
                "Adsoyad" => html_entity_decode($flow["adsoyad"]),
                "NobetYeri" => html_entity_decode($flow["nobetYeri"]),
                "Bastar" => $flow["bastar"],
                "Bittar" => $flow["bittar"],
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
$app->get("/pkUpdateMakeActiveOrPassive_infonobetprogrami/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoNobetProgramiBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkUpdateMakeActiveOrPassive_infonobetprogrami" end point, X-Public variable not found');
    }
    $Pk = $headerParams['X-Public'];      
    $vId = NULL;
    if (isset($_GET['id'])) {
        $stripper->offsetSet('id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('id')) {$vId = $stripper->offsetGet('id')->getFilterValue(); }
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
$app->get("/pkInsert_infonobetprogrami/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoNobetProgramiBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsert_infonobetprogrami" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vOgrId = NULL;
    if (isset($_GET['OgrId'])) {
         $stripper->offsetSet('OgrId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['OgrId']));
    }
    $vNobetYeri = NULL;
    if (isset($_GET['NobetYeri'])) {
         $stripper->offsetSet('NobetYeri',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['NobetYeri']));
    }
    $vBastar = NULL;
    if (isset($_GET['Bastar'])) {
         $stripper->offsetSet('Bastar',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Bastar']));
    }
    $vBittar= NULL;
    if (isset($_GET['Bittar'])) {
         $stripper->offsetSet('Bittar',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Bittar']));
    }
   
    $stripper->strip();
    if($stripper->offsetExists('OgrId')) $vOgrId = $stripper->offsetGet('OgrId')->getFilterValue();
    if($stripper->offsetExists('NobetYeri')) $vNobetYeri = $stripper->offsetGet('NobetYeri')->getFilterValue();
    if($stripper->offsetExists('Bastar')) $vBastar = $stripper->offsetGet('Bastar')->getFilterValue();
    if($stripper->offsetExists('Bittar')) $vBittar = $stripper->offsetGet('Bittar')->getFilterValue();
      
    $resDataInsert = $BLL->insert(array(
            'nobetYeri' => $vNobetYeri,       
            'ogrId' => $vOgrId,          
            'bastar' => $vBastar,
            'bittar' => $vBittar,
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
    $BLL = $app->getBLLManager()->get('infoNobetProgramiBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdate_infonobetprogrami" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vOgrId = NULL;
    if (isset($_GET['OgrId'])) {
         $stripper->offsetSet('OgrId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['OgrId']));
    }
    $vNobetYeri = NULL;
    if (isset($_GET['NobetYeri'])) {
         $stripper->offsetSet('NobetYeri',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['NobetYeri']));
    }
    $vBastar = NULL;
    if (isset($_GET['Bastar'])) {
         $stripper->offsetSet('Bastar',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Bastar']));
    }
    $vBittar= NULL;
    if (isset($_GET['Bittar'])) {
         $stripper->offsetSet('Bittar',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Bittar']));
    } 
    $vId = NULL;
    if (isset($_GET['Id'])) {
         $stripper->offsetSet('Id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Id']));
    }
   
    $stripper->strip();
    if($stripper->offsetExists('OgrId')) $vOgrId = $stripper->offsetGet('OgrId')->getFilterValue();
    if($stripper->offsetExists('NobetYeri')) $vNobetYeri = $stripper->offsetGet('NobetYeri')->getFilterValue();
    if($stripper->offsetExists('Bastar')) $vBastar = $stripper->offsetGet('Bastar')->getFilterValue();
    if($stripper->offsetExists('Bittar')) $vBittar = $stripper->offsetGet('Bittar')->getFilterValue();
    if($stripper->offsetExists('Id')) $vId = $stripper->offsetGet('Id')->getFilterValue();  
    $resDataInsert = $BLL->update(array(
            'id' => $vId,  
            'nobetYeri' => $vNobetYeri,       
            'OgrId' => $vOgrId,          
            'Bastar' => $vBastar,
            'Bittar' => $vBittar,
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
    $BLL = $app->getBLLManager()->get('infoNobetProgramiBLL');   
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
