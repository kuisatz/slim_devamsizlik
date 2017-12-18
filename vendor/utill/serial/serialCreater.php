<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Utill\Serial;

/**
 * create serial or key for several uses
 * @author Okan CIRAN
 * @since 0.0.1 15.06.2015
 */
class serialCreater {
    
    public function __construct() {
       
    }
    
    /**
     * create serial for every request
     * @return \Serial\strıng
     * @author Okan CIRAN
     */
    public static function cretaSerial() {
    $template   = 'XX99-XX99-99XX-99XX-XXXX-99XX';
    $k = strlen($template);
    $sernum = '';
    for ($i=0; $i<$k; $i++)
    {
        switch($template[$i])
        {
            case 'X': $sernum .= chr(rand(65,90)); break;
            case '9': $sernum .= rand(0,9); break;
            case '-': $sernum .= '-';  break; 
        }
    }
    return $sernum;

    }
}

