<?php

// test commit for branch slim2
require 'vendor/autoload.php';




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
 * @since 25-01-2016
 */
$app->get("/fillMainDefinitions_sysSpecificDefinitions/", function () use ($app ) {


    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillMainDefinitions(array('language_code' => $languageCode
    ));

    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" =>html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {        
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => html_entity_decode($menu["name"]),
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" =>html_entity_decode( $menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    }
 
    $app->response()->header("Content-Type", "application/json");
 

    $app->response()->body(json_encode($menus));
});
/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillFullDefinitions_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
   
    $resCombobox = $BLL->fillFullDefinitions(array('language_code' => $languageCode
    ));

    
    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {        
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => html_entity_decode($menu["name"]),
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => html_entity_decode($menu["name_eng"]),
              //  "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");
  
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillCommunicationsTypes_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
 
    $resCombobox = $BLL->fillCommunicationsTypes(array('language_code' => $languageCode
    ));
    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => html_entity_decode($menu["name"]),
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => html_entity_decode($menu["name_eng"]),
            //    "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");


    $app->response()->body(json_encode($menus));

    //$app->response()->body(json_encode($flows));
});

/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillBuildingType_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }

    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillBuildingType(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {     
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => html_entity_decode($menu["name"]),
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => html_entity_decode($menu["name_eng"]),
                //"imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");
 

    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillOwnershipType_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillOwnershipType(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => html_entity_decode($menu["name"]),
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => html_entity_decode($menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    }
    $app->response()->header("Content-Type", "application/json");
 
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillPersonnelTypes_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillPersonnelTypes(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {      
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => html_entity_decode($menu["name_eng"]),
                //"imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");
 
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillAddressTypes_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillAddressTypes(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => html_entity_decode($menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/fillSexTypes_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillSexTypes(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => html_entity_decode($menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 13-06-2017
 */
$app->get("/fillSubjectsTypes_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillSubjectsTypes();

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" =>  html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => html_entity_decode( $menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    } 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 14-06-2017
 */
$app->get("/fillDifficulty_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillDifficulty();

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" =>  html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" =>  html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" =>  html_entity_decode($menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    } 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 14-06-2017
 */
$app->get("/fillQuestionTime_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillQuestionTime();

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" =>  html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" =>  html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" =>  html_entity_decode($menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    } 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 04-07-2017
 */
$app->get("/fillSubjectTypes_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillSubjectTypes();

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" =>  html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" =>  html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" =>  html_entity_decode($menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    } 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});
 

/**
 *  * Okan CIRAN
 * @since 04-07-2017
 */
$app->get("/fillAnswerTypes_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillAnswerTypes();

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" =>  html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" =>  html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" =>  html_entity_decode($menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    } 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 04-07-2017
 */
$app->get("/fillQuestionSourceType_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillQuestionSourceType();

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" =>  html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" =>  html_entity_decode($menu["name"]),
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" =>  html_entity_decode($menu["name_eng"]),
               // "imageSrc" => ""
            );
        }
    } 
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 17-09-2017
 */
$app->get("/fillEducationType_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillEducationType();

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" =>  html_entity_decode($menu["name"]),
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
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


$app->run();
