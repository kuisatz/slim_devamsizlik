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
 * mail template concrete class
 * @author Okan CIRAN
 * @since 29/06/2016
 */
class MailTemplate extends AbstractMailTemplate {

    /**
     * abstract method overridden
     * @param type $variablesToBeReplaced
     * @return boolean
     */
    public function replaceTemplatePlaceHolders($variablesToBeReplaced=null) {
        if(!empty($variablesToBeReplaced)) {
            $content = $this->getTemplateContent();
            $content = str_replace(array_keys($variablesToBeReplaced), 
                        array_values($variablesToBeReplaced), 
                        $content);
            $this->templateContent = $content;
        }
        return false;  
    }
    
    /**
     * abstract method overriden
     * @param type $variablesToBeReplaced
     * @return type
     */
    public function replaceAndGetTemplateContent($variablesToBeReplaced=null) {
        $this->replaceTemplatePlaceHolders($variablesToBeReplaced);
        return $this->templateContent;
    }

    

}
