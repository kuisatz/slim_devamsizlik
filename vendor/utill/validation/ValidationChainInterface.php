<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Utill\Validation;

Interface ValidationChainInterface {
    public function setValidationChain($validationChain = null);
    public function getValidationChain($name = null);
}

