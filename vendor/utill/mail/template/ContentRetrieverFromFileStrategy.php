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
 * mail template content retriever concrete class
 * @author Okan CIRAN
 * @since 29/06/2016
 */
class ContentRetrieverFromFileStrategy implements ContentRetrieverStrategyInterface {

/**
 * 
 * @param string $content
 */
protected $content;


public function fillContent(array $params = null) {
    //print_r(dirname(__FILE__)); 
    $this->content = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'contents'.DIRECTORY_SEPARATOR.$params['fileName'].'.html');
}

public function getContent(array $params = null) {
    
    if(!isset($this->content)) {
        $this->fillContent($params);
    }
    return $this->content;
}

}
