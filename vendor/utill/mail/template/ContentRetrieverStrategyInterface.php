<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Utill\Mail\Template;

/**
 * mail template content retrieve strategy interface
 * @author Okan CIRAN
 * @since 29/06/2016
 */
interface ContentRetrieverStrategyInterface  {

    public function getContent(array $params = null);
    public function fillContent(array $params = null);

}
