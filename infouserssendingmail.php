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
 * @since 26-04-2016
 */
$app->get("/sendMailTempUserRegistration_infoUsersSendingMail/", function () use ($app ) {  
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoUsersSendingMailBLL');    
  //  $BLLProfile = $app->getBLLManager()->get('infoUsersVerbalBLL');    
    $headerParams = $app->request()->headers();  
    
    $vauthemail = NULL;
    if (isset($_GET['auth_email'])) {
         $stripper->offsetSet('auth_email',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['auth_email']));
    }   
    $vname = NULL;
    if (isset($_GET['name'])) {
         $stripper->offsetSet('name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name']));
    }   
    $vsurname = NULL;
    if (isset($_GET['surname'])) {
         $stripper->offsetSet('surname',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['surname']));
    }   
    $vroleValue = 5;
    if (isset($_GET['rol'])) {
         $stripper->offsetSet('rol',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['rol']));
    }        
    $vkey = NULL;
    if (isset($_GET['key'])) {
         $stripper->offsetSet('key',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['key']));
    }    
     
    $stripper->strip();
    if ($stripper->offsetExists('auth_email')) {
        $vauthemail = $stripper->offsetGet('auth_email')->getFilterValue();
    }  
    if ($stripper->offsetExists('name')) {
        $vname = $stripper->offsetGet('name')->getFilterValue();
    }
    if ($stripper->offsetExists('surname')) {
        $vsurname = $stripper->offsetGet('surname')->getFilterValue();
    }
    if ($stripper->offsetExists('rol')) {
        $vroleValue = $stripper->offsetGet('rol')->getFilterValue();
    }
    if ($stripper->offsetExists('key')) {
        $vkey = $stripper->offsetGet('key')->getFilterValue();
    } 
   
    $resDataInsert = $BLL->sendMailTempUserRegistration(array(   
            'auth_email' => $vauthemail,  
            'name'=> $vname, 
            'surname'=> $vsurname,
            'rol'=> $vroleValue,
            'key'=> $vkey,
            'herkimse'  => 'Okannnnn',
            'kume' => 'ddeeenneemmeee',
                  
            ));

    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
}
); 


$app->run();