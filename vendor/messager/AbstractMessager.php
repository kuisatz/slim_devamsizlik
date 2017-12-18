<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Messager;

/**
 * abstract error messager class
 * @author Okan CIRAN
 * @since 14/01/2016  
 */
abstract class AbstractMessager implements
                    \Messager\MessageBrokerInterface{
    
    /**
     * compare  values as before and after, will be overridden in sub classes
     * @param mixed $valuenew
     * @param mixed $valueold
     * @param mixed $filterName
     */
    public function compareValue($valuenew = null, $valueold = null, $filterName = null, $baseKey = null) {
        
    }

}

