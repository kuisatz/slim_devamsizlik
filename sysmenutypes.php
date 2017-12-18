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
 * @since 28.06.2016
 *  rest servislere eklendi
 */
$app->get("/pkFillMenuTypeList_sysMenuTypes/", function () use ($app ) {   
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysMenuTypesBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkFillMenuTypeList_sysMenuTypes" end point, X-Public variable not found');
   // $pk = $headerParams['X-Public'];
    
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }
    $stripper->strip();
    if($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
   
    $resCombobox = $BLL->fillMenuTypeList(array(                                   
                                    'language_code' => $vLanguageCode,
                        ));        
 
    $flows = array();
    $flows[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    foreach ($resCombobox as $flow) {
        $flows[] = array(            
            "text" => $flow["menu_type_name"],
            "value" =>  intval($flow["id"]),
            "selected" => false,
            "description" => $flow["menu_type_name_eng"],
            "imageSrc"=>"",
            "attributes" => array(   
                                    "active" => $flow["active"],    
                ),
        );
    }
    
     $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($flows)); 
});
 
 
/**
 *  * Okan CIRAN
 * @since 28.06.2016
 *  rest servislere eklendi
 */
$app->get("/pkInsert_sysMenuTypes/", function () use ($app ) {    
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();   
    $BLL = $app->getBLLManager()->get('sysMenuTypesBLL');   
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsert_sysMenuTypes" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];  
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }           
    $vName = NULL;
    if (isset($_GET['name'])) {
         $stripper->offsetSet('name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name']));
    } 
    $vNameEng = NULL;
    if (isset($_GET['name_eng'])) {
         $stripper->offsetSet('name_eng',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name_eng']));
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
    if ($stripper->offsetExists('language_code')) {
        $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
    }  
    if ($stripper->offsetExists('name')) {
        $vName = $stripper->offsetGet('name')->getFilterValue();
    }
    if ($stripper->offsetExists('name_eng')) {
        $vNameEng = $stripper->offsetGet('name_eng')->getFilterValue();
    }
    if ($stripper->offsetExists('description')) {
        $vDescription = $stripper->offsetGet('description')->getFilterValue();
    }
    if ($stripper->offsetExists('description_eng')) {
        $vDescriptionEng = $stripper->offsetGet('description_eng')->getFilterValue();
    }  
    
    $resData = $BLL->insert(array(  
            'language_code' => $vLanguageCode, 
            'name' => $vName,
            'name_eng'=> $vNameEng, 
            'description' => $vDescription,
            'description_eng'=> $vDescriptionEng,
            'pk' => $pk,           
            ));

    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resData));
}
); 
 
/**
 *  * Okan CIRAN
 * @since 28.06.2016
 *  rest servislere eklendi
 */
$app->get("/pkUpdate_sysMenuTypes/", function () use ($app ) {    
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();   
    $BLL = $app->getBLLManager()->get('sysMenuTypesBLL');   
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdate_sysMenuTypes" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];  
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }    
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
    $vNameEng = NULL;
    if (isset($_GET['name_eng'])) {
         $stripper->offsetSet('name_eng',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name_eng']));
    } 
    $vDescription = '';
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    } 
    $vDescriptionEng = '' ;
    if (isset($_GET['description_eng'])) {
         $stripper->offsetSet('description_eng',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description_eng']));
    } 
    
    $stripper->strip();
    if ($stripper->offsetExists('language_code')) {
        $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
    }  
    if ($stripper->offsetExists('id')) {
        $vId = $stripper->offsetGet('id')->getFilterValue();
    } 
    if ($stripper->offsetExists('name')) {
        $vName = $stripper->offsetGet('name')->getFilterValue();
    }
    if ($stripper->offsetExists('name_eng')) {
        $vNameEng = $stripper->offsetGet('name_eng')->getFilterValue();
    }
    if ($stripper->offsetExists('description')) {
        $vDescription = $stripper->offsetGet('description')->getFilterValue();
    }
    if ($stripper->offsetExists('description_eng')) {
        $vDescriptionEng = $stripper->offsetGet('description_eng')->getFilterValue();
    }     
    
    $resData = $BLL->update(array(  
            'language_code' => $vLanguageCode, 
            'id' => $vId, 
            'name' => $vName,
            'name_eng'=> $vNameEng, 
            'description' => $vDescription,
            'description_eng'=> $vDescriptionEng, 
            'pk' => $pk,
           
            ));

    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resData));
}
); 
 
 /**x
 *  * Okan CIRAN
 * @since 28.06.2016
 *  rest servislere eklendi
 */
$app->get("/pkUpdateMakeActiveOrPassive_sysMenuTypes/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysMenuTypesBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkUpdateMakeActiveOrPassive_sysMenuTypes" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];     
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
            'pk' => $pk,        
            ));
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resData));
}
); 

/**x
 *  * Okan CIRAN
 * @since 28.06.2016
 * rest servislere eklendi
 */
$app->get("/pkDelete_sysMenuTypes/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysMenuTypesBLL');   
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkDelete_sysMenuTypes" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];   
    $vId = NULL;
    if (isset($_GET['id'])) {
        $stripper->offsetSet('id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('id')) 
        {$vId = $stripper->offsetGet('id')->getFilterValue(); }  
        
    $resDataDeleted = $BLL->Delete(array(                  
            'id' => $vId ,    
            'pk' => $pk,        
            ));
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataDeleted));
}
); 

/**
 *  * Okan CIRAN
* @since 21.07.2016
* rest servislere eklendi
 */
$app->get("/pkFillMenuTypeListGrid_sysMenuTypes/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysMenuTypesBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkFillMenuTypeListGrid_sysMenuTypes" end point, X-Public variable not found');
    }
   // $pk = $headerParams['X-Public'];
    
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
    $rname = NULL;
    $rnameEng = NULL;
    $rdescription = NULL;
    $rdescriptionEng = NULL;
   // $rstateActive = NULL;
    $ropUserName = NULL;
    $rlanguageName = NULL;
     if (isset($_GET['filterRules'])) {
            $filterRules = trim($_GET['filterRules']);
            $jsonFilter = json_decode($filterRules, true);             
            foreach ($jsonFilter as $std) {
                if ($std['value'] != null) {
                    switch (trim($std['field'])) {
                        case 'name':                            
                            $stripper->offsetSet('name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;
                        case 'name_eng':
                            $stripper->offsetSet('name_eng', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;
                        case 'description':
                            $stripper->offsetSet('description', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));                            
                            break;
                        case 'description_eng':
                            $stripper->offsetSet('description_eng', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;
                 //       case 'state_active':
                   //         $stripper->offsetSet('state_active', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                  //          break;
                        case 'op_user_name':
                            $stripper->offsetSet('op_user_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
                            break;
                        case 'language_name':
                            $stripper->offsetSet('language_name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app,$std['value']));
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
                        case 'name':                            
                                $rname = $stripper->offsetGet('name')->getFilterValue();                    
                            break;
                        case 'name_eng':
                                $rnameEng = $stripper->offsetGet('name_eng')->getFilterValue();
                            break;
                        case 'description':
                                $rdescription = $stripper->offsetGet('description')->getFilterValue();                            
                            break;
                        case 'description_eng':
                                $rdescriptionEng = $stripper->offsetGet('description_eng')->getFilterValue();
                            break;
                  //      case 'state_active':
                   //             $rstateActive = $stripper->offsetGet('state_active')->getFilterValue();                            
                  //          break;
                        case 'op_user_name':
                                $ropUserName = $stripper->offsetGet('op_user_name')->getFilterValue();                            
                            break; 
                        case 'language_name':
                                $rlanguageName = $stripper->offsetGet('language_name')->getFilterValue();                            
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
    
    $resDataGrid = $BLL->fillMenuTypeListGrid(array(
        'language_code' => $vLanguageCode, 
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
                        
        // 'filterRules' => $filterRules,
        'fr_name' => $rname,
        'fr_name_eng' => $rnameEng,
        'fr_op_user_name' => $ropUserName,        
        'fr_description' => $rdescription,
        'fr_description_eng' => $rdescriptionEng,
      //  'fr_state_active' => $rstateActive,
        'fr_language_name' => $rlanguageName,        
    ));
    $resTotalRowCount = $BLL->fillMenuTypeListGridRtc(array(
        'language_code' => $vLanguageCode,                 
        // 'filterRules' => $filterRules,
        'fr_name' => $rname,
        'fr_name_eng' => $rnameEng,
        'fr_op_user_name' => $ropUserName,        
        'fr_description' => $rdescription,
        'fr_description_eng' => $rdescriptionEng,
      //  'fr_state_active' => $rstateActive,
        'fr_language_name' => $rlanguageName,        
    ));
    $counts = 0;
    $flows = array();
    if (isset($resDataGrid[0]['id'])) {
        foreach ($resDataGrid as $flow) {
            $flows[] = array(
            "id" => $flow["id"],
            "name" => $flow["name"],
            "name_eng" => $flow["name_eng"],
            "description" => $flow["description"],
            "description_eng" => $flow["description_eng"],
            "language_id" => $flow["language_id"],   
            "language_name" => $flow["language_name"],   
            "state_active" => $flow["state_active"],  
            "op_user_id" => $flow["op_user_id"],  
            "op_user_name" => $flow["op_user_name"],
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
