<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Messager\Filter;

interface FilterMessagerInterface{
    public function setFilterMessage($filterMessage = null);
    public function getFilterMessage();
    public function addFilterMessage($filterMessage = null);
}

