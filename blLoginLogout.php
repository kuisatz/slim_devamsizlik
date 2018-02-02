<?php
// test commit for branch slim2
require 'vendor/autoload.php';


use \Services\Filter\Helper\FilterFactoryNames as stripChainers;

/*$app = new \Slim\Slim(array(
    'mode' => 'development',
    'debug' => true,
    'log.enabled' => true,
    ));*/

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
 * @author Okan CIRAN Ğ
 * @since 05.01.2016
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
 * @since 02-09-2016
 */
$app->get("/getPK_blLoginLogout/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('blLoginLogoutBLL'); 
    $headerParams = $app->request()->headers();
  //  print_r($headerParams) ; 
   $vUsername = NULL;
    if (isset($_GET['username'])) {
        $stripper->offsetSet('username', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                $app, $_GET['username']));
    }
    $vSessionID= NULL;
    if (isset($_GET['sessionID'])) {
        $stripper->offsetSet('sessionID', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL1, 
                $app, $_GET['sessionID']));
    }
    $vPassword = NULL;
    if (isset($_GET['password'])) {
        $stripper->offsetSet('password', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL1, 
                $app, $_GET['password']));
    }
    
    $stripper->strip();
     if ($stripper->offsetExists('username')) {
        $vUsername = $stripper->offsetGet('username')->getFilterValue();
    }
    if ($stripper->offsetExists('password')) {
        $vPassword = $stripper->offsetGet('password')->getFilterValue();
    }
    if ($stripper->offsetExists('sessionID')) {
        $vSessionID = $stripper->offsetGet('sessionID')->getFilterValue();
    }
   
    $resDataInsert = $BLL->getPK(array( 
        'url' => $_GET['url'], 
        'username' => $vUsername,
        'password' => $vPassword,   
        'sessionID' => $vSessionID,  
        ));
   // $app->response()->header("Content-Type", "application/json");
  // $app->response()->body(json_encode($resDataInsert));
 // print_r($resDataInsert);
    
     $flows = array();
    foreach ($resDataInsert as $flow) {
        $flows[] = array(
           
            "success" =>  $flow["success"] ,
            "public_key" => $flow["public_key"],  
        //    "okunmamis_mesaj" => $flow["okunmamis_mesaj"],  
       //     "pdr_mesaj" => $flow["pdr_mesaj"],  
            "sessionID" => $flow["sessionid"],  
            "adsoyad" => html_entity_decode($flow["adsoyad"]),   
            
            
        );
    }
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($flows));
    
    
}
);

/**
 *  * Okan CIRAN
 * @since 02-09-2016
 */
$app->get("/pkLogOut_blLoginLogout/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('blLoginLogoutBLL'); 
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkLogOut_blLoginLogout" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];
   
   
    $resDataInsert = $BLL->logOutPK(array( 
        'url' => $_GET['url'], 
        'PublicKey' => $pk,  
        )); 
   $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($resDataInsert));
    
    
}
);



/**
 *  * OKAN CIRAN
 * @since 05-01-2016
 */
$app->get("/pkSessionControl_blLoginLogout/", function () use ($app ) {

    
    $BLL = $app->getBLLManager()->get('blLoginLogoutBLL'); 
 
    // Filters are called from service manager
    //$filterHtmlAdvanced = $app->getServiceManager()->get(\Services\Filter\FilterServiceNames::FILTER_HTML_TAGS_ADVANCED);
  //  $filterHexadecimalBase = $app->getServiceManager()->get(\Services\Filter\FilterServiceNames::FILTER_HEXADECIMAL_ADVANCED );
    //$filterHexadecimalAdvanced = $app->getServiceManager()->get(\Services\Filter\FilterServiceNames::FILTER_HEXADECIMAL_ADVANCED);

    
 // print_r( $app->request()->headers());
   
    $resDataMenu = $BLL->pkSessionControl(array('pk'=>$_GET['pk']));
   // print_r($resDataMenu);
   
 
    $menus = array();
    foreach ($resDataMenu as $menu){
        $menus[]  = array(
            "id" => $menu["id"],
            "name" => $menu["name"],
             "data" => $menu["data"],
             "lifetime" => $menu["lifetime"],
             "c_date" => $menu["c_date"],
             "modified" => $menu["modified"],
             "public_key" => $menu["public_key"],
             "u_name" => $menu["u_name"],
             "u_surname" => $menu["u_surname"],
             "username" => $menu["username"],
           
            
           
        );
    }
    
    $app->response()->header("Content-Type", "application/json");
    
  
    
    /*$app->contentType('application/json');
    $app->halt(302, '{"error":"Something went wrong"}');
    $app->stop();*/
    
  $app->response()->body(json_encode($menus));
  
});
 

$app->run();