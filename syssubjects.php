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
 * @since 12.06.2017
 */
$app->get("/pkDelete_sysSubjects/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysSubjectsBLL'); 
   
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

/**x
 *  * Okan CIRAN
 * @since 12.06.2017
 */ 
$app->get("/pkUpdate_sysSubjects/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();   
    $BLL = $app->getBLLManager()->get('sysSubjectsBLL'); 
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkUpdate_sysSubjects" end point, X-Public variable not found');
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
    $vAbbreviation = NULL;
    if (isset($_GET['abbreviation'])) {
        $stripper->offsetSet('abbreviation', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['abbreviation']));
    }  
    $vDescription = NULL;
    if (isset($_GET['description'])) {
        $stripper->offsetSet('description', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }    
    $vSubjectTypeId = 0;
    if (isset($_GET['subject_type_id'])) {
         $stripper->offsetSet('subject_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['subject_type_id']));
    }  
     $vKanunno = NULL;
    if (isset($_GET['kanunno'])) {
         $stripper->offsetSet('kanunno',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['kanunno']));
    }  
    $vKonutip = 1;
    if (isset($_GET['konutip'])) {
         $stripper->offsetSet('konutip',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['konutip']));
    }  
    
     $stripper->strip(); 
    
    if ($stripper->offsetExists('id')) {
        $vId = $stripper->offsetGet('id')->getFilterValue();
    }  
    if ($stripper->offsetExists('name')) {
        $vName = $stripper->offsetGet('name')->getFilterValue();
    }    
    if ($stripper->offsetExists('abbreviation')) {
        $vAbbreviation= $stripper->offsetGet('abbreviation')->getFilterValue();
    }           
    if ($stripper->offsetExists('description')) {
        $vDescription = $stripper->offsetGet('description')->getFilterValue();
    } 
    if ($stripper->offsetExists('subject_type_id')) {
        $vSubjectTypeId = $stripper->offsetGet('subject_type_id')->getFilterValue();
    } 
    if ($stripper->offsetExists('kanunno')) {
        $vKanunno = $stripper->offsetGet('kanunno')->getFilterValue();
    }  
    if ($stripper->offsetExists('konutip')) {
        $vKonutip = $stripper->offsetGet('konutip')->getFilterValue();
    }  
    

    $resData = $BLL->update(array( 
            "name" => htmlentities($vName, ENT_QUOTES),
            "abbreviation" => htmlentities($vAbbreviation, ENT_QUOTES),  
            "description" => htmlentities($vDescription, ENT_QUOTES),   
            "subject_type_id" => $vSubjectTypeId, 
            "kanunno" => $vKanunno, 
            "konutip" => $vKonutip, 
            "id" => $vId,   
            'pk' => $Pk,        
            ));

    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resData));
}
); 

/**x
 *  * Okan CIRAN
 * @since 12.06.2017
 */
$app->get("/pkInsert_sysSubjects/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();   
    $BLL = $app->getBLLManager()->get('sysSubjectsBLL'); 
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkInsert_sysSubjects" end point, X-Public variable not found');
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
    $vAbbreviation = NULL;
    if (isset($_GET['abbreviation'])) {
        $stripper->offsetSet('abbreviation', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['abbreviation']));
    }  
    $vDescription = NULL;
    if (isset($_GET['description'])) {
        $stripper->offsetSet('description', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }    
    $vSubjectTypeId = 0;
    if (isset($_GET['subject_type_id'])) {
         $stripper->offsetSet('subject_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['subject_type_id']));
    }   
    $vKanunno = NULL;
    if (isset($_GET['kanunno'])) {
         $stripper->offsetSet('kanunno',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['kanunno']));
    }  
    $vKonutip = NULL;
    if (isset($_GET['konutip'])) {
         $stripper->offsetSet('konutip',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['konutip']));
    }  
    
     $stripper->strip(); 
  
    if ($stripper->offsetExists('parent_id')) {
        $vParentId = $stripper->offsetGet('parent_id')->getFilterValue();
    }  
    if ($stripper->offsetExists('name')) {
        $vName = $stripper->offsetGet('name')->getFilterValue();
    }    
    if ($stripper->offsetExists('abbreviation')) {
        $vAbbreviation= $stripper->offsetGet('abbreviation')->getFilterValue();
    }           
    if ($stripper->offsetExists('description')) {
        $vDescription = $stripper->offsetGet('description')->getFilterValue();
    } 
    if ($stripper->offsetExists('subject_type_id')) {
        $vSubjectTypeId = $stripper->offsetGet('subject_type_id')->getFilterValue();
    }  
    if ($stripper->offsetExists('kanunno')) {
        $vKanunno = $stripper->offsetGet('kanunno')->getFilterValue();
    }  
    if ($stripper->offsetExists('konutip')) {
        $vKonutip = $stripper->offsetGet('konutip')->getFilterValue();
    }  
   
    $resData = $BLL->insert(array( 
            "name" =>htmlentities( $vName, ENT_QUOTES), 
            "abbreviation" => htmlentities($vAbbreviation, ENT_QUOTES),  
            "description" => htmlentities($vDescription, ENT_QUOTES), 
            "subject_type_id" => $vSubjectTypeId,  
            "parent_id" => $vParentId,   
            "kanunno" => $vKanunno, 
            "konutip" => $vKonutip, 
            'pk' => $Pk,        
            ));

    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resData));
}
); 
/**
 *  * Okan CIRAN
 * @since 12.06.2017
 */ 
$app->get("/fillSubjectsTree_sysSubjects/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysSubjectsBLL');    
     
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
    $NotIn = '5';
    if (isset($_GET['not_in'])) {
         $stripper->offsetSet('not_in',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL1,
                                                $app,
                                                $_GET['not_in']));
    }   
    
    $stripper->strip();        
    if($stripper->offsetExists('search')) $vsearch = $stripper->offsetGet('search')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
    if($stripper->offsetExists('not_in')) $NotIn = $stripper->offsetGet('not_in')->getFilterValue();

 
    $resCombobox = $BLL->fillSubjectsTree(array(
                                        'search' => $vsearch, 
                                        'parent_id' => $vId,
                                        'not_in' => $NotIn,
                                        )   );
 
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
                                "subject_type_id" => $flow["subject_type_id"],
                                "kanunno" => $flow["kanunno"],
                                "konutip" => $flow["konutip"],
                                "abbreviation" => html_entity_decode($flow["abbreviation"]),
                                "description" => html_entity_decode($flow["description"]),                 
                    
                    ), 
        );
    }

    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($flows));
});
 

/**
 *  * Okan CIRAN
 * @since 12.06.2017
 */ 
$app->get("/fillBaseTree_sysSubjects/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysSubjectsBLL');    
     
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

 
    $resCombobox = $BLL->fillBaseTree(array(
                                        'search' => $vsearch, 'parent_id' => $vId,));
 
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
                                "subject_type_id" => $flow["subject_type_id"],
                                "kanunno" => $flow["kanunno"],
                                "konutip" => $flow["konutip"],
                                "abbreviation" => html_entity_decode($flow["abbreviation"]),
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
$app->get("/pkUpdateMakeActiveOrPassive_sysSubjects/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysSubjectsBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkUpdateMakeActiveOrPassive_sysSubjects" end point, X-Public variable not found');
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


$app->run();
