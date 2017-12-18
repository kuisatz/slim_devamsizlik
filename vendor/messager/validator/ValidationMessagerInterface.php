<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Messager\Validator;

interface ValidationMessagerInterface{
    public function setValidationMessage($validationMessage = null);
    public function getValidationMessage();
    public function addValidationMessage($validationMessage = null);
}

