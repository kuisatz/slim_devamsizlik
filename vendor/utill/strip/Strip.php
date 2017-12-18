<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Utill\Strip;

 class Strip extends AbstractStrip implements \Services\Filter\FilterChainInterface
                                              {
    
    public function __construct($params = null) {
        
        //if(empty($params))throw new Exception('strip class constructor parametre hatası');
        
        
    }
    
    public function strip($key = null) {
        $this->rewind();
        foreach ($this->stripStrategies as $key => $value) {
            if(method_exists($value, 'strip')) { 
                $value->strip($key);
            } else {
                throw new \Exception('invalid strip method for strip');
            }
        }
    }

    public function getFilterChain($name = null) {
        
    }

    public function setFilterChain(\Utill\Strip\Chain\AbstractStripChainer $filterChainer) {
        
    }

}

