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
$app->get("/FillKurumlarCmb_infokurumlar/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('infoKurumlarBLL'); 
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    } 
    $resCombobox = $BLL->fillKurumlarCmb();

    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" =>  html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => ""
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
$app->get("/pkFillKurumlar_infokurumlar/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoKurumlarBLL');

    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillKurumlar_infokurumlar" end point, X-Public variable not found');
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
    
    $resDataGrid = $BLL->fillKurumlar(array( 
        'pk' => $pk,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
        'filterRules' => $filterRules,
    ));
  /*  $resTotalRowCount = $BLL->fillNobetDevamsizligiRtc(array( 
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
                "KurumTurId" => intval($flow["kurumTurId"]), 
                "KurumTurName" => html_entity_decode($flow["kurumTurName"]),  
                "Name" => html_entity_decode($flow["name"]),
                "NameAbb" => html_entity_decode($flow["nameAbb"]),
                "Logo" => html_entity_decode($flow["logo"]),
                "IlAdi" => html_entity_decode($flow["IlAdi"]),
                "IlceAdi" => html_entity_decode($flow["IlceAdi"]),
                "Adres1" => html_entity_decode($flow["adres1"]),
                "Adres2" => html_entity_decode($flow["adres2"]),
                "Postcode" => html_entity_decode($flow["postcode"]),
                "IlId" => $flow["ilId"],
                "IlceId" => $flow["ilceId"],
                "Active" => intval($flow["active"]),
                "Deleted" => intval($flow["deleted"]),
                 "attributes" => ""
                 
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
$app->get("/pkUpdateMakeActiveOrPassive_infokurumlar/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoKurumlarBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public'])) {
        throw new Exception('rest api "pkUpdateMakeActiveOrPassive_infokurumlar" end point, X-Public variable not found');
    }
    $Pk = $headerParams['X-Public'];      
    $vId = NULL;
    if (isset($_GET['Id'])) {
        $stripper->offsetSet('Id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Id']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('Id')) {$vId = $stripper->offsetGet('Id')->getFilterValue(); }
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
$app->get("/pkInsert_infokurumlar/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoKurumlarBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsert_infokurumlar" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vKurumTurId = NULL;
    if (isset($_GET['KurumTurId'])) {
         $stripper->offsetSet('KurumTurId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['KurumTurId']));
    }
    $vlogo = NULL;
    if (isset($_GET['Logo'])) {
         $stripper->offsetSet('Logo',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Logo']));
    }
    $vname = NULL;
    if (isset($_GET['Name'])) {
         $stripper->offsetSet('Name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Name']));
    }
    $vNameAbb = NULL;
    if (isset($_GET['NameAbb'])) {
         $stripper->offsetSet('NameAbb',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['NameAbb']));
    }
    $vilId= NULL;
    if (isset($_GET['IlId'])) {
         $stripper->offsetSet('IlId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['IlId']));
    }
    $vilceId= NULL;
    if (isset($_GET['IlceId'])) {
         $stripper->offsetSet('IlceId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['IlceId']));
    }
    $vadres1= NULL;
    if (isset($_GET['Adres1'])) {
         $stripper->offsetSet('Adres1',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Adres1']));
    }
    $vadres2= NULL;
    if (isset($_GET['Adres2'])) {
         $stripper->offsetSet('Adres2',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Adres2']));
    }
    $vpostcode= NULL;
    if (isset($_GET['Postcode'])) {
         $stripper->offsetSet('Postcode',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Postcode']));
    }
    $vmebkodu= NULL;
    if (isset($_GET['Mebkodu'])) {
         $stripper->offsetSet('Mebkodu',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Mebkodu']));
    }
   
    $stripper->strip();
    if($stripper->offsetExists('KurumTurId')) $vKurumTurId = $stripper->offsetGet('KurumTurId')->getFilterValue();
    if($stripper->offsetExists('Logo')) $vlogo = $stripper->offsetGet('Logo')->getFilterValue();
    if($stripper->offsetExists('Name')) $vname = $stripper->offsetGet('Name')->getFilterValue();
    if($stripper->offsetExists('NameAbb')) $vNameAbb = $stripper->offsetGet('NameAbb')->getFilterValue();
    if($stripper->offsetExists('IlId')) $vilId = $stripper->offsetGet('IlId')->getFilterValue(); 
    if($stripper->offsetExists('IlceId')) $vilceId = $stripper->offsetGet('IlceId')->getFilterValue();
    if($stripper->offsetExists('Adres1')) $vadres1 = $stripper->offsetGet('Adres1')->getFilterValue();
    if($stripper->offsetExists('Adres2')) $vadres2 = $stripper->offsetGet('Adres2')->getFilterValue();
    if($stripper->offsetExists('Postcode')) $vpostcode = $stripper->offsetGet('Postcode')->getFilterValue();
    if($stripper->offsetExists('Mebkodu')) $vmebkodu = $stripper->offsetGet('Mebkodu')->getFilterValue();
      
    $resDataInsert = $BLL->insert(array(
            'kurumTurId' => $vKurumTurId,       
            'logo' => $vlogo,     
            'name' => $vname,      
            'nameAbb' => $vNameAbb,
            'ilId' => $vilId,
            'ilceId' => $vilceId,
            'adres1' => $vadres1,
            'adres2' => $vadres2,
            'postcode' => $vpostcode,
            'mebkodu' => $vmebkodu,
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
/**
 *  * Okan CIRAN
* @since 31-01-2018
 */
$app->get("/pkUpdate_infokurumlar/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoKurumlarBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdate_infokurumlar" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
     
    $vId = NULL;
    if (isset($_GET['Id'])) {
         $stripper->offsetSet('Id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Id']));
    } 
   $vKurumTurId = NULL;
    if (isset($_GET['KurumTurId'])) {
         $stripper->offsetSet('KurumTurId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['KurumTurId']));
    }
    $vlogo = NULL;
    if (isset($_GET['Logo'])) {
         $stripper->offsetSet('Logo',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['Logo']));
    }
    $vname = NULL;
    if (isset($_GET['Name'])) {
         $stripper->offsetSet('Name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Name']));
    }
    $vNameAbb = NULL;
    if (isset($_GET['NameAbb'])) {
         $stripper->offsetSet('NameAbb',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['NameAbb']));
    }
    $vilId= NULL;
    if (isset($_GET['IlId'])) {
         $stripper->offsetSet('IlId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['IlId']));
    }
    $vilceId= NULL;
    if (isset($_GET['IlceId'])) {
         $stripper->offsetSet('IlceId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['IlceId']));
    }
    $vadres1= NULL;
    if (isset($_GET['Adres1'])) {
         $stripper->offsetSet('Adres1',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Adres1']));
    }
    $vadres2= NULL;
    if (isset($_GET['Adres2'])) {
         $stripper->offsetSet('Adres2',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Adres2']));
    }
    $vpostcode= NULL;
    if (isset($_GET['Postcode'])) {
         $stripper->offsetSet('Postcode',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Postcode']));
    }
    $vmebkodu= NULL;
    if (isset($_GET['Mebkodu'])) {
         $stripper->offsetSet('Mebkodu',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['Mebkodu']));
    }
   
    $stripper->strip();
    if($stripper->offsetExists('KurumTurId')) $vKurumTurId = $stripper->offsetGet('KurumTurId')->getFilterValue();
    if($stripper->offsetExists('Logo')) $vlogo = $stripper->offsetGet('Logo')->getFilterValue();
    if($stripper->offsetExists('Name')) $vname = $stripper->offsetGet('Name')->getFilterValue();
    if($stripper->offsetExists('NameAbb')) $vNameAbb = $stripper->offsetGet('NameAbb')->getFilterValue();
    if($stripper->offsetExists('IlId')) $vilId = $stripper->offsetGet('IlId')->getFilterValue(); 
    if($stripper->offsetExists('IlceId')) $vilceId = $stripper->offsetGet('IlceId')->getFilterValue();
    if($stripper->offsetExists('Adres1')) $vadres1 = $stripper->offsetGet('Adres1')->getFilterValue();
    if($stripper->offsetExists('Adres2')) $vadres2 = $stripper->offsetGet('Adres2')->getFilterValue();
    if($stripper->offsetExists('Postcode')) $vpostcode = $stripper->offsetGet('Postcode')->getFilterValue();
    if($stripper->offsetExists('Mebkodu')) $vmebkodu = $stripper->offsetGet('Mebkodu')->getFilterValue();
    if($stripper->offsetExists('Id')) $vId = $stripper->offsetGet('Id')->getFilterValue();  
    $resDataInsert = $BLL->update(array(
            'id' => $vId,  
            'kurumTurId' => $vKurumTurId,       
            'logo' => $vlogo,     
            'name' => $vname,      
            'nameAbb' => $vNameAbb,
            'ilId' => $vilId,
            'ilceId' => $vilceId,
            'adres1' => $vadres1,
            'adres2' => $vadres2,
            'postcode' => $vpostcode,
            'mebkodu' => $vmebkodu,
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
/**
 *  * Okan CIRAN
* @since 31-01-2018
 */
 
$app->get("/pkDelete_infokurumlar/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoKurumlarBLL');   
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
