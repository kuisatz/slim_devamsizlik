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


 

/**x
 *  * Okan CIRAN
 * @since 14.06.2017
 */ 
$app->get("/pkUpdate_InfoDuyuru/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();   
    $BLL = $app->getBLLManager()->get('InfoDuyuruBLL'); 
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkUpdate_InfoDuyuru" end point, X-Public variable not found');
    }
    $Pk = $headerParams['X-Public'];    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    }          
    $vName = NULL;
    if (isset($_GET['name'])) {
         $stripper->offsetSet('name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name']));
    }   
    $vExamBase = NULL;
    if (isset($_GET['exam_base'])) {
        $stripper->offsetSet('exam_base', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['exam_base']));
    }  
    $vDescription = NULL;
    if (isset($_GET['description'])) {
        $stripper->offsetSet('description', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }  
    $vCorporationId = 0;
    if (isset($_GET['corporation_id'])) {
         $stripper->offsetSet('corporation_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['corporation_id']));
    }   
   $vExamDate = NULL;
    if (isset($_GET['exam_date'])) { 
    //    $d = new DateTime($_GET['exam_date'], new DateTimeZone('Europe/Istanbul')); 
     //    $d = ($d->getTimestamp());
        $stripper->offsetSet('exam_date', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                    $app, $_GET['exam_date']));
    }
     
    
    if ($stripper->offsetExists('id')) {
        $vId = $stripper->offsetGet('id')->getFilterValue();
    }  
    if ($stripper->offsetExists('name')) {
        $vName = $stripper->offsetGet('name')->getFilterValue();
    }    
    if ($stripper->offsetExists('exam_base')) {
        $vExamBase= $stripper->offsetGet('exam_base')->getFilterValue();
    }           
    if ($stripper->offsetExists('description')) {
        $vDescription = $stripper->offsetGet('description')->getFilterValue();
    }  
    if ($stripper->offsetExists('corporation_id')) {
        $vCorporationId = $stripper->offsetGet('corporation_id')->getFilterValue();
    } 
    if ($stripper->offsetExists('exam_date')) {
        $vExamDate = $stripper->offsetGet('exam_date')->getFilterValue();
    } 
    
    

    $resData = $BLL->update(array( 
            "name" => $vName , 
            "exam_base" => ($vExamBase), 
            "description" => $vDescription,  
            "corporation_id" => $vCorporationId, 
            "exam_date" => $vExamDate,  
            "id" => $vId,   
            'pk' => $Pk,        
            ));

    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resData));
}
); 

/**x
 *  * Okan CIRAN
 * @since 14.06.2017
 */
$app->get("/pkInsert_InfoDuyuru/", function () use ($app ) {  
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();   
    $BLL = $app->getBLLManager()->get('InfoDuyuruBLL'); 
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkInsert_InfoDuyuru" end point, X-Public variable not found');
    }
    $Pk = $headerParams['X-Public'];    
    $vParentId = 0;
    if (isset($_GET['parent_id'])) {
         $stripper->offsetSet('parent_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['parent_id']));
    }          
    $vName = NULL;
    if (isset($_GET['name'])) {
         $stripper->offsetSet('name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name']));
    }   
    $vExamBase = NULL;
    if (isset($_GET['exam_base'])) {
        $stripper->offsetSet('exam_base', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['exam_base']));
    }  
    $vDescription = NULL;
    if (isset($_GET['description'])) {
        $stripper->offsetSet('description', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }   
    $vCorporationId = 0;
    if (isset($_GET['corporation_id'])) {
         $stripper->offsetSet('corporation_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['corporation_id']));
    }   
   
   $vExamDate = NULL;
    if (isset($_GET['exam_date'])) { 
    //    $d = new DateTime($_GET['exam_date'], new DateTimeZone('Europe/Istanbul')); 
     //    $d = ($d->getTimestamp());
        $stripper->offsetSet('exam_date', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                    $app, $_GET['exam_date']));
    }
     
    $stripper->strip(); 
    if ($stripper->offsetExists('parent_id')) {
        $vParentId = $stripper->offsetGet('parent_id')->getFilterValue();
    }  
    if ($stripper->offsetExists('name')) {
        $vName = $stripper->offsetGet('name')->getFilterValue();
    }    
    if ($stripper->offsetExists('exam_base')) {
        $vExamBase= $stripper->offsetGet('exam_base')->getFilterValue();
    }           
    if ($stripper->offsetExists('description')) {
        $vDescription = $stripper->offsetGet('description')->getFilterValue();
    }  
    if ($stripper->offsetExists('corporation_id')) {
        $vCorporationId = $stripper->offsetGet('corporation_id')->getFilterValue();
    } 
    if ($stripper->offsetExists('exam_date')) {
        $vExamDate = $stripper->offsetGet('exam_date')->getFilterValue();
    }  
   
      
    $resData = $BLL->insert(array( 
            "parent_id" => $vParentId,
            "name" => ($vName),
            "exam_base" => ($vExamBase),  
            "description" => ($vDescription),   
            "corporation_id" => $vCorporationId, 
            "exam_date" => $vExamDate,   
            'pk' => $Pk,        
            ));

    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resData));
}
); 
/**
 *  * Okan CIRAN
 * @since 14.06.2017
 */ 
$app->get("/fillExamsTree_InfoDuyuru/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('InfoDuyuruBLL');    
     
    $vsearch = null;
    if(isset($_GET['search'])) {
        $stripper->offsetSet('search', 
                $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                        $app,
                        $_GET['search']));
    }
    
   $vId = 0;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    }   
    
    $stripper->strip();        
    if($stripper->offsetExists('search')) $vsearch = $stripper->offsetGet('search')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();

 
    $resCombobox = $BLL->fillExamsTree(array(
                                        'search' => $vsearch,'parent_id' =>  $vId ,   ));
 
    $flows = array();
    foreach ($resCombobox as $flow) {
        $flows[] = array(
            "id" => $flow["id"],
            "text" => html_entity_decode($flow["name"]),
            "state" => $flow["state_type"], //   'closed',
            "checked" => false,
           // "icon_class"=>$flow["icon_class"], 
           "attributes" => array("root" => $flow["root_type"], "active" => $flow["active"],
                                "last_node" => $flow["last_node"],
                                
                                "exam_date" => $flow["exam_date"],
                                "exam_base" => html_entity_decode($flow["exam_base"]),
                                "description" => html_entity_decode($flow["description"]), 
                                
               
                    
                    ), 
        );
    }

    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($flows));
});
 
 /**x
 *  * Okan CIRAN
 * @since 26-07-2016
 */
$app->get("/pkUpdateMakeActiveOrPassive_InfoDuyuru/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('InfoDuyuruBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkUpdateMakeActiveOrPassive_InfoDuyuru" end point, X-Public variable not found');
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
 * @since 14-06-2017
 */
$app->get("/FillNotice_InfoDuyuru/", function () use ($app ) {
 
    $BLL = $app->getBLLManager()->get('InfoDuyuruBLL'); 
    $headerParams = $app->request()->headers(); 
     
    $resDataGrid = $BLL->fillNotice(array(  
    ));
 
 
    $counts=0;
    $parentcounts=0;
  
    $menu = array();            
   if (isset($resDataGrid[0]['id'])) {      
        foreach ($resDataGrid as $menu) {
            $menus[] = array(
                "id" => $menu["id"], 
                "baslik" => html_entity_decode($menu["baslik"]),
                "ozet" => html_entity_decode($menu["ozet"]), 
                "detay" => html_entity_decode($menu["detay"]),
             
            );
        }
     //   $counts = $resTotalRowCount[0]['count'];
    //    $parentcounts = $resTotalRowCount[0]['parent_count'];
      } ELSE  $menus = array();       

    $app->response()->header("Content-Type", "application/json");
    $resultArray = array();
   // $resultArray['total'] = $counts;
   // $resultArray['total_parent'] = $parentcounts;
    $resultArray['rows'] = $menus;
    $app->response()->body(json_encode($resultArray));
});


 
 

$app->run();
