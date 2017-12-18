<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Services\OperationTypes\Helper;

/**
 * base factory class for filter chainer classes
 * @author Okan CIRAN
 * @since 22/02/2016
 */
class OperationTypesChainerFactory extends \Utill\Factories\AbstractFactory {
    
    /**
     * constructor function 
     */
    public function __construct() {
        
    }

    public function get($helperName, $app, $value) {
        if(method_exists($this,$helperName)) {
          return  $this->$helperName($app, $value);
        }
    }

    protected function getUtility($identifier = null,
            $params = null) {
        
    }

}
