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
 * @since 23-05-2016
 */
$app->get("/getUsersCompanyNotifications_ActUsersActionStatistics1/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();   
    $BLL = $app->getBLLManager()->get('actUsersActionStatisticsBLL');
    $headerParams = $app->request()->headers();  
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }  
    print_r($headerParams['X-Public']);
    $vPk = 'pk';
    if (isset($headerParams['X-Public'])) {
         $stripper->offsetSet('pk',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                               $headerParams['X-Public']));
    }  
    $stripper->strip();
    if($stripper->offsetExists('language_code'))  {
        $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
    }
    if($stripper->offsetExists('pk'))  {
        $vPk = $stripper->offsetGet('pk')->getFilterValue();
    }
    
    
    $result = $BLL->getUsersCompanyNotifications(array(
        'language_code' => $vLanguageCode,
        'pk' => $vPk,
        ));
    
        print_r($result);
    $flows = array();
    foreach ($result['resultSet'] as $flow) {
        $flows[] = array(
            "name" => html_entity_decode($flow["name"]),
            "surname" => html_entity_decode($flow["surname"]),  
            "dayx" => $flow["dayx"],   
            "monthx" => $flow["monthx"],
            "yearx" => $flow["yearx"],
            "role" => html_entity_decode($flow["role"]),
            "picture" => $flow["picture"],
            "notification" => html_entity_decode($flow["notification"]),
            "passingtime" => $flow["passingtime"],
            "processingtime" => $flow["processingtime"], 
            "attributes" => array(),
        );
    }
 
    $app->response()->header("Content-Type", "application/json");    
    $app->response()->body(json_encode($flows));
});
  
 

 /**x
 *  * Okan CIRAN
 * @since 20-128-2016
 */
$app->get("/pkGetUsersCompanyNotifications1_ActUsersActionStatistics/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('actUsersActionStatisticsBLL');
    $headerParams = $app->request()->headers();     
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkGetUsersCompanyNotifications1_ActUsersActionStatistics" end point, X-Public variable not found');
    }
    $pk = $headerParams['X-Public'];
    
    $vNpk = NULL;    
    if (isset($_GET['npk'])) {
        $stripper->offsetSet('npk', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['npk']));
    } 
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }  
    
    $stripper->strip(); 
    if ($stripper->offsetExists('npk')) {$vNpk = $stripper->offsetGet('npk')->getFilterValue(); }
      $result = $BLL->getUsersCompanyNotifications(array(
        'language_code' => $vLanguageCode,
        'network_key' => $vNpk, 
        'pk' => $pk, 
        ));                            
                  
    $flows = array();
    foreach ($result['resultSet'] as $flow) {
        $flows[] = array(
            "name" => html_entity_decode($flow["name"]),
            "surname" => html_entity_decode($flow["surname"]),  
            "dayx" => $flow["dayx"],   
            "monthx" => $flow["monthx"],
            "yearx" => $flow["yearx"],
            "role" => html_entity_decode($flow["role"]),
            "notification_type_id" => $flow["notification_type_id"],
            "notification" => html_entity_decode($flow["notification"]),
            "passingtime" => $flow["passingtime"],
            "processingtime" => $flow["processingtime"], 
            "icon_path" => $flow["icon_path"], 
            "icon_class" => $flow["icon_class"], 
            "attributes" => array(),
        );
    }
 
    $app->response()->header("Content-Type", "application/json");    
    $app->response()->body(json_encode($flows));
}
); 


/**
 *  * Okan CIRAN
 * @since 20-12-2016
 */
$app->get("/getUsersCompanyNotifications_ActUsersActionStatistics/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();   
    $BLL = $app->getBLLManager()->get('actUsersActionStatisticsBLL');
    
    $headerParams = $app->request()->headers();  
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }  
  
    $vPk = 'zk';
   
    if (isset($_GET['zk'])) {
         $stripper->offsetSet('zk',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                               $_GET['zk']));
    }  
    $stripper->strip();
    if($stripper->offsetExists('language_code'))  {
        $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
    }
    if($stripper->offsetExists('zk'))  {
        $vPk = $stripper->offsetGet('zk')->getFilterValue();
    }
    
    
    
    $result = $BLL->getUsersCompanyNotifications(array(
        'language_code' => $vLanguageCode,
        'pk' => $vPk,
    
        ));
  
    $flows = array();
    foreach ($result['resultSet'] as $flow) {
        $flows[] = array(
            "name" => html_entity_decode($flow["name"]),
            "surname" => html_entity_decode($flow["surname"]),  
            "dayx" => $flow["dayx"],   
            "monthx" => $flow["monthx"],
            "yearx" => $flow["yearx"],
            "role" => html_entity_decode($flow["role"]),
            "notification_type_id" => $flow["notification_type_id"],
            "notification" => html_entity_decode($flow["notification"]),
            "passingtime" => $flow["passingtime"],
            "processingtime" => $flow["processingtime"], 
            "icon_path" => $flow["icon_path"], 
            "icon_class" => $flow["icon_class"], 
            "attributes" => array(),
        );
    }
 
    $app->response()->header("Content-Type", "application/json");    
    $app->response()->body(json_encode($flows));
});
  
 /**x
 *  * Okan CIRAN
 * @since 21-12-2016
 */
$app->get("/getUsersRightNotifications_ActUsersActionStatistics/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('actUsersActionStatisticsBLL');
    
    $vNpk = NULL;    
    if (isset($_GET['npk'])) {
        $stripper->offsetSet('npk', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['npk']));
    } 
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }  
    
    $stripper->strip(); 
    if ($stripper->offsetExists('npk')) {$vNpk = $stripper->offsetGet('npk')->getFilterValue(); }
      $result = $BLL->getUsersRightNotifications(array(
        'language_code' => $vLanguageCode,
        'network_key' => $vNpk,         
        ));                            
                  
    $flows = array();
    foreach ($result['resultSet'] as $flow) {
        $flows[] = array(
            "allfirmmachinetotal" =>  $flow["allfirmmachinetotal"],
            "firmmachinetotal" => $flow["firmmachinetotal"],  
            "lasttwelve" => $flow["lasttwelve"],               
            "lastsix" => $flow["lastsix"],   
            "lasttwelvepercent" => $flow["lasttwelvepercent"],
         //   "lastsixpercent" => $flow["lastsixpercent"], 
            "attributes" => array(),
        );
    }
 
    $app->response()->header("Content-Type", "application/json");    
    $app->response()->body(json_encode($flows));
}
); 


 /**x
 *  * Okan CIRAN
 * @since 21-12-2016
 */
$app->get("/getUsersLeftNotifications_ActUsersActionStatistics/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('actUsersActionStatisticsBLL');    
    
    $vNpk = NULL;    
    if (isset($_GET['npk'])) {
        $stripper->offsetSet('npk', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['npk']));
    } 
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }  
    
    $stripper->strip(); 
    if ($stripper->offsetExists('npk')) {
        $vNpk = $stripper->offsetGet('npk')->getFilterValue();         
    }
    if ($stripper->offsetExists('language_code')) {
        $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
    }
      $result = $BLL->getUsersLeftNotifications(array(
        'language_code' => $vLanguageCode,
        'network_key' => $vNpk,       
        ));                            
                  
    $flows = array();
    foreach ($result['resultSet'] as $flow) {
        $flows[] = array(
            "lasttwelve" =>  $flow["lasttwelve"],
            "lastsix" => $flow["lastsix"],  
            "allnotificationscount" => $flow["allnotificationscount"],               
            "allfirmnotificationscount" => $flow["allfirmnotificationscount"],   
            "lasttwelvepercent" => $flow["lasttwelvepercent"],         
            "attributes" => array(),
        );
    }
 
    $app->response()->header("Content-Type", "application/json");    
    $app->response()->body(json_encode($flows));
}
); 

/**
 *  * Okan CIRAN
 * @since 27-12-2016
 */
$app->get("/getFirmHistoryV1_ActUsersActionStatistics/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();   
    $BLL = $app->getBLLManager()->get('actUsersActionStatisticsBLL');    
    
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }  
    $vNpk = NULL;    
    if (isset($_GET['npk'])) {
        $stripper->offsetSet('npk', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['npk']));
    } 
    
    $stripper->strip();
    if($stripper->offsetExists('language_code')) {
        $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
    }
    if ($stripper->offsetExists('npk')) {
        $vNpk = $stripper->offsetGet('npk')->getFilterValue();         
    }
    
    
     $result = $BLL->getFirmHistoryV1(array(
        'language_code' => $vLanguageCode,
        'network_key' => $vNpk,
    
        ));
  
    $flows = array();
    foreach ($result['resultSet'] as $flow) {
        $flows[] = array(
            "s_date" => $flow["s_date"],
          //  "op_user_id" => $flow["op_user_id"],  
       //     "operation_type_id" => $flow["operation_type_id"],            
            "category_id" => $flow["category_id"],
            "category" => html_entity_decode($flow["category"]),
            "operation_name" => html_entity_decode($flow["operation_name"]),
            "picture" => $flow["picture"],
            "op_user_picture" => $flow["op_user_picture"],
            "statu" => html_entity_decode($flow["statu"]),            
            "name" => html_entity_decode($flow["name"]),
            "surname" => html_entity_decode($flow["surname"]),            
            "description" => html_entity_decode($flow["description"]),
            "attributes" => array(),
        );
    }
 
    $app->response()->header("Content-Type", "application/json");    
    $app->response()->body(json_encode($flows));
});
  



$app->run();

