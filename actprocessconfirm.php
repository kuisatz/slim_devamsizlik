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

 
 
 
/**
 *  * Okan CIRAN
* @since 21.07.2016
* rest servislere eklendi
 */
$app->get("/pkGetConsultantJobs_actProcessConfirm/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('actProcessConfirmBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkGetConsultantJobs_actProcessConfirm" end point, X-Public variable not found');
    }
    $pk = $headerParams['X-Public'];  
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
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
    
    ///////////////////////////////////////////////////////////// 
    $roperationName = NULL;
    $roperationNameEng = NULL;
    $rcategory = NULL;
    $rcategoryEng = NULL;
    $rtableName = NULL;
    $rmembershipTypesName = NULL;
    $rmembershipTypesNameEng = NULL;    
    $rperiodName = NULL;
    $rperiodNameEng = NULL;
    $rpreferredLanguage = NULL;
    $ropUserName = NULL;
    $rconsName = NULL;
    $ropConsName = NULL;
    $rconsOperationName = NULL;
    $rconsOperationNameEng = NULL;
    
     if (isset($_GET['filterRules'])) {
            $filterRules = trim($_GET['filterRules']);
            $jsonFilter = json_decode($filterRules, true);             
            foreach ($jsonFilter as $std) {
                if ($std['value'] != null) {
                    switch (trim($std['field'])) {
                        case 'operation_name':                            
                            $stripper->offsetSet('operation_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;
                        case 'operation_name_eng':
                            $stripper->offsetSet('operation_name_eng', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;
                        case 'category':
                            $stripper->offsetSet('category', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));                            
                            break;
                        case 'category_eng':
                            $stripper->offsetSet('category_eng', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;
                        case 'table_name':
                            $stripper->offsetSet('table_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;
                        case 'membership_types_name':
                            $stripper->offsetSet('membership_types_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;
                        case 'membership_types_name_eng':
                            $stripper->offsetSet('membership_types_name_eng', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;     
                        case 'period_name':
                            $stripper->offsetSet('period_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;   
                        case 'period_name_eng':
                            $stripper->offsetSet('period_name_eng', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;   
                        case 'preferred_language':
                            $stripper->offsetSet('preferred_language', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break; 
                        case 'cons_name':
                            $stripper->offsetSet('cons_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;  
                        case 'op_user_name':
                            $stripper->offsetSet('op_user_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;  
                        case 'op_cons_name':
                            $stripper->offsetSet('op_cons_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;  
                        case 'cons_operation_name':
                            $stripper->offsetSet('cons_operation_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;  
                         case 'cons_operation_name_eng':
                            $stripper->offsetSet('cons_operation_name_eng', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break; 
                        default:
                            break;
                    }
                }
            }
        }            
    ////////////////////////////////////////////////////////////
    
    $stripper->strip();  
    
       if (isset($_GET['filterRules'])) {
            $filterRules = trim($_GET['filterRules']);
            $jsonFilter = json_decode($filterRules, true);             
            $addfilterRules = NULL;
            $filterRules = NULL;
            foreach ($jsonFilter as $std) {
                if ($std['value'] != NULL) {                    
                    switch (trim($std['field'])) {
                        case 'operation_name':                            
                                $roperationName = $stripper->offsetGet('operation_name')->getFilterValue();                    
                            break;
                        case 'operation_name_eng':
                                $roperationNameEng = $stripper->offsetGet('operation_name_eng')->getFilterValue();
                            break;
                        case 'category':
                                $rcategory = $stripper->offsetGet('category')->getFilterValue();                            
                            break;
                        case 'category_eng':
                                $rcategoryEng = $stripper->offsetGet('category_eng')->getFilterValue();
                            break;
                        case 'table_name':
                                $rtableName = $stripper->offsetGet('table_name')->getFilterValue();
                            break; 
                        case 'membership_types_name':
                                $rmembershipTypesName = $stripper->offsetGet('membership_types_name')->getFilterValue();                            
                            break;
                        case 'membership_types_name_eng':
                                $rmembershipTypesNameEng = $stripper->offsetGet('membership_types_name_eng')->getFilterValue();                            
                            break; 
                        case 'period_name':
                                $rperiodName = $stripper->offsetGet('period_name')->getFilterValue();                            
                            break;  
                        case 'period_name_eng':
                                $rperiodNameEng = $stripper->offsetGet('period_name_eng')->getFilterValue();                            
                            break;   
                        case 'preferred_language':
                                $rpreferredLanguage = $stripper->offsetGet('preferred_language')->getFilterValue();                            
                            break;   
                        case 'cons_name':
                                $rconsName = $stripper->offsetGet('cons_name')->getFilterValue();                            
                            break;   
                        case 'op_user_name':
                                $ropUserName = $stripper->offsetGet('op_user_name')->getFilterValue();                            
                            break;  
                        case 'op_cons_name':
                                $ropConsName = $stripper->offsetGet('op_cons_name')->getFilterValue();                            
                            break;  
                         case 'cons_operation_name':
                                $rconsOperationName = $stripper->offsetGet('cons_operation_name')->getFilterValue();                            
                            break; 
                         case 'cons_operation_name_eng':
                                $rconsOperationNameEng = $stripper->offsetGet('cons_operation_name_eng')->getFilterValue();                            
                            break; 
                        default:
                            break;
                    }
                   
                   
                }
            }
        }  
    
    if ($stripper->offsetExists('language_code')) {
        $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
    }  
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
    
    $resDataGrid = $BLL->getConsultantJobs(array(    
        'pk' => $pk,
        'language_code' => $vLanguageCode, 
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,    
        
        'fr_operation_name' =>$roperationName,
        'fr_operation_name_eng' =>$roperationNameEng,
        'fr_category' =>$rcategory,
        'fr_category_eng' =>$rcategoryEng,
        'fr_table_name' =>$rtableName,
        'fr_membership_types_name' =>$rmembershipTypesName,        
        'fr_membership_types_name_eng' =>$rmembershipTypesNameEng,
        'fr_period_name' =>$rperiodName,
        'fr_period_name_eng' =>$rperiodNameEng,
        'fr_preferred_language' =>$rpreferredLanguage,
        'fr_op_user_name' =>$ropUserName,
        'fr_cons_name' =>$rconsName,
        'fr_op_cons_name' =>$ropConsName,
        'fr_cons_operation_name' =>$rconsOperationName,
        'fr_cons_operation_name_eng' =>$rconsOperationNameEng,        
    ));
    $resTotalRowCount = $BLL->getConsultantJobsRtc(array(    
        'pk' => $pk,
        'language_code' => $vLanguageCode,
        'fr_operation_name' =>$roperationName,
        'fr_operation_name_eng' =>$roperationNameEng,
        'fr_category' =>$rcategory,
        'fr_category_eng' =>$rcategoryEng,
        'fr_table_name' =>$rtableName,
        'fr_membership_types_name' =>$rmembershipTypesName,        
        'fr_membership_types_name_eng' =>$rmembershipTypesNameEng,
        'fr_period_name' =>$rperiodName,
        'fr_period_name_eng' =>$rperiodNameEng,
        'fr_preferred_language' =>$rpreferredLanguage,
        'fr_op_user_name' =>$ropUserName,
        'fr_cons_name' =>$rconsName,
        'fr_op_cons_name' =>$ropConsName,
        'fr_cons_operation_name' =>$rconsOperationName,
        'fr_cons_operation_name_eng' =>$rconsOperationNameEng,    
    ));
    $counts = 0;
    $flows = array();
    if (isset($resDataGrid[0]['id'])) {
        foreach ($resDataGrid as $flow) {
            $flows[] = array(
            "id" => $flow["id"],
            "operation_type_id" => $flow["operation_type_id"],
            "operation_name_eng" => html_entity_decode($flow["operation_name_eng"]),
            "category_id" => $flow["category_id"],
            "category" => html_entity_decode($flow["category"]),
            "category_eng" => html_entity_decode($flow["category_eng"]),
            "table_name" => html_entity_decode($flow["table_name"]),   
            "table_column_id" => $flow["table_column_id"],   
            "membership_types_id" => $flow["membership_types_id"],   
            "membership_types_name" => html_entity_decode($flow["membership_types_name"]),   
            "membership_types_name_eng" => html_entity_decode($flow["membership_types_name_eng"]),   
            "sys_membership_periods_id" => $flow["sys_membership_periods_id"],   
            "period_name" => html_entity_decode($flow["period_name"]),   
            "period_name_eng" => html_entity_decode($flow["period_name_eng"]),   
            "preferred_language_id" => $flow["preferred_language_id"],   
            "preferred_language" => html_entity_decode($flow["preferred_language"]),   
            "language_id" => $flow["language_id"],   
            "language_name" => html_entity_decode($flow["language_name"]),   
            "cons_id" => $flow["cons_id"],   
            "cons_name" => html_entity_decode($flow["cons_name"]),  
            "op_cons_id" => $flow["op_cons_id"],   
            "op_cons_name" => html_entity_decode($flow["op_cons_name"]), 
            "cons_operation_type_id" => $flow["cons_operation_type_id"],   
            "cons_operation_name" => html_entity_decode($flow["cons_operation_name"]), 
            "cons_operation_name_eng" => html_entity_decode($flow["cons_operation_name_eng"]),   
            "s_date" => $flow["s_date"], 
            "c_date" => $flow["c_date"],   
            "priority" => $flow["priority"], 
            "state_active" => html_entity_decode($flow["state_active"]),  
            "op_user_id" => $flow["op_user_id"],  
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




$app->run();
