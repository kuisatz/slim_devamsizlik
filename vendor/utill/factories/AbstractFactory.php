<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Utill\Factories;

/**
 * base class for factory type operations
 * @author Okan CIRAN
 * @since 11/02/2016
 */
abstract  class AbstractFactory{
    abstract protected function getUtility($identifier = null, $params = null);
}
