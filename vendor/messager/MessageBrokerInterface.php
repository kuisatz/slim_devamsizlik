<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Messager;

interface MessageBrokerInterface{
    public function compareValue($valuenew = null, $valueold = null, $filterName = null, $baseKey = null);
}

