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
 * @since 15.12.2016
 
 */
$app->get("/pkInsert_sysNotificationRestservices/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysNotificationRestservicesBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsert_sysNotificationRestservices" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public']; 
    
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $stripper->offsetSet('language_code', $stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE, 
                                                                $app, 
                                                                $_GET['language_code']));
    }    
    $vServicesGroupId = NULL;
    if (isset($_GET['services_group_id'])) {
         $stripper->offsetSet('services_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['services_group_id']));
    } 
    $vName = NULL;
    if (isset($_GET['name'])) {
         $stripper->offsetSet('name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL1,
                                                $app,
                                                $_GET['name']));
    } 
    $vDescription = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }
    $vDescriptionEng = NULL;
    if (isset($_GET['description_eng'])) {
         $stripper->offsetSet('description_eng',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description_eng']));
    }
    
    $stripper->strip();
    if($stripper->offsetExists('language_code')) 
        $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
    if($stripper->offsetExists('services_group_id')) 
        $vServicesGroupId = $stripper->offsetGet('services_group_id')->getFilterValue();
    if($stripper->offsetExists('name')) 
        $vName = $stripper->offsetGet('name')->getFilterValue(); 
    if($stripper->offsetExists('description')) 
        $vDescription = $stripper->offsetGet('description')->getFilterValue();    
    if($stripper->offsetExists('description_eng')) 
        $vDescriptionEng = $stripper->offsetGet('description_eng')->getFilterValue();    
      
    $resDataInsert = $BLL->insert(array(
            'language_code' => $vLanguageCode,  
            'services_group_id' => $vServicesGroupId,  
            'name' => $vName, 
            'description' => $vDescription,
            'description_eng' => $vDescriptionEng,
            'url' => $_GET['url'],
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15.12.2016
 
 */
$app->get("/pkUpdate_sysNotificationRestservices/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysNotificationRestservicesBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsert_sysNotificationRestservices" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public']; 
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    }
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $stripper->offsetSet('language_code', $stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE, 
                                                                $app, 
                                                                $_GET['language_code']));
    }    
    $vServicesGroupId = NULL;
    if (isset($_GET['services_group_id'])) {
         $stripper->offsetSet('services_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['services_group_id']));
    } 
    $vName = NULL;
    if (isset($_GET['name'])) {
         $stripper->offsetSet('name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL1,
                                                $app,
                                                $_GET['name']));
    } 
    $vDescription = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }
    $vDescriptionEng = NULL;
    if (isset($_GET['description_eng'])) {
         $stripper->offsetSet('description_eng',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description_eng']));
    }
    
    $stripper->strip();
    if($stripper->offsetExists('id')) 
        $vId = $stripper->offsetGet('id')->getFilterValue();
    if($stripper->offsetExists('language_code')) 
        $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
    if($stripper->offsetExists('services_group_id')) 
        $vServicesGroupId = $stripper->offsetGet('services_group_id')->getFilterValue();
    if($stripper->offsetExists('name')) 
        $vName = $stripper->offsetGet('name')->getFilterValue(); 
    if($stripper->offsetExists('description')) 
        $vDescription = $stripper->offsetGet('description')->getFilterValue();    
    if($stripper->offsetExists('description_eng')) 
        $vDescriptionEng = $stripper->offsetGet('description_eng')->getFilterValue();    
      
    $resDataInsert = $BLL->update(array(
            'id' => $vId,  
            'language_code' => $vLanguageCode,  
            'services_group_id' => $vServicesGroupId,  
            'name' => $vName, 
            'description' => $vDescription,
            'description_eng' => $vDescriptionEng,
            'url' => $_GET['url'],
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
  
/**
 *  * Okan CIRAN
 * @since 15.12.2016
 
 */ 
$app->get("/pkDelete_sysNotificationRestservices/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysNotificationRestservicesBLL');   
    $headerParams = $app->request()->headers();
    $Pk = $headerParams['X-Public'];  
    $vId = NULL;
    if (isset($_GET['id'])) {
        $stripper->offsetSet('id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('id')) {$vId = $stripper->offsetGet('id')->getFilterValue(); }  
    $resDataDeleted = $BLL->Delete(array(                  
            'id' => $vId ,    
            'pk' => $Pk,        
            ));
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataDeleted));
}
); 
 
  
/**
 *  * Okan CIRAN
 * @since 08.08.2016
 
 */
$app->get("/pkFillNotificationRestservicesList_sysNotificationRestservices/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysNotificationRestservicesBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkFillNotificationRestservicesList_sysNotificationRestservices" end point, X-Public variable not found');
    } 
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
    
    $resDataGrid = $BLL->fillNotificationRestservicesList(array(        
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
        'filterRules' => $filterRules,
    ));
    $resTotalRowCount = $BLL->fillNotificationRestservicesListRtc(array(
        'filterRules' => $filterRules,
    ));
    $counts = 0;
    $flows = array();
    if (isset($resDataGrid[0]['id'])) {
        foreach ($resDataGrid as $flow) {
            $flows[] = array(
            "id" => $flow["id"],
            "name" => html_entity_decode($flow["name"]), 
            "services_group_id" => $flow["services_group_id"],
            "service_group_name" => html_entity_decode($flow["service_group_name"]),  
            "description" => html_entity_decode($flow["description"]),  
            "description_eng" => html_entity_decode($flow["description_eng"]),  
            "state_active" => html_entity_decode($flow["state_active"]),  
            "op_user_name" => html_entity_decode($flow["op_user_name"]), 
            "attributes" => array(                 
                "active" => $flow["active"], ) );
        };
        $counts = $resTotalRowCount[0]['count'];
    }   
    
    $app->response()->header("Content-Type", "application/json");
    $resultArray = array();
    $resultArray['total'] = $counts;
    $resultArray['rows'] = $flows;
    $app->response()->body(json_encode($resultArray));
});
   
 /**x
 *  * Okan CIRAN
 * @since 08.08.2016
 
 */
$app->get("/pkUpdateMakeActiveOrPassive_sysNotificationRestservices/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysNotificationRestservicesBLL');
    $headerParams = $app->request()->headers();
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


$app->run();
