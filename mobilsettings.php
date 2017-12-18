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
$app->get("/mobilUrlData_mobilsettings/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mobilSettingsBLL'); 
    
   
    $resDataInsert = $BLL->mobilUrlData(array(  
        )); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array(
            "proxy" => $menu["proxy"],
            "logo" => $menu["logo"], 
            "abbrevation" => html_entity_decode($menu["abbrevation"]), 
            "schoolName" => html_entity_decode($menu["schoolName"]), 
            "combologo" => $menu["combologo"], 
            "id" => $menu["id"], 
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
}
);
  



/**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/MobilwsdlEncryptPassword_mobilsettings/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mobilSettingsBLL'); 
    
   
    $vpswrd = NULL; 
    if (isset($_GET['pswrd'])) {
        $stripper->offsetSet('pswrd', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL1, 
                $app, $_GET['pswrd']));
    }
    
    $stripper->strip();
    if ($stripper->offsetExists('pswrd')) {
        $vpswrd = $stripper->offsetGet('pswrd')->getFilterValue();
    }
    
   
    $resDataInsert = $BLL->mobilwsdlEncryptPassword(array( 
        'url' => $_GET['url'], 
        'PswrD' => $vpswrd,  
        ));
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
     
}
);
  
/**
 *  * Okan CIRAN
 * @since 25.10.2017
 */
$app->get("/MobilwsdlDecryptPassword_mobilsettings/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mobilSettingsBLL'); 
    
   
    $vpswrd = NULL; 
    if (isset($_GET['pswrd'])) {
        $stripper->offsetSet('pswrd', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL1, 
                $app, $_GET['pswrd']));
    }
    
    $stripper->strip();
    if ($stripper->offsetExists('pswrd')) {
        $vpswrd = $stripper->offsetGet('pswrd')->getFilterValue();
    }
    
   
    $resDataInsert = $BLL->mobilwsdlDecryptPassword(array( 
        'url' => $_GET['url'], 
        'PswrD' => $vpswrd,  
        ));
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
     
}
);
  
$app->run();
