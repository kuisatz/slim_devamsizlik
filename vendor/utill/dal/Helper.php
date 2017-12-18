<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Utill\Dal;

final class Helper {
    
    
    public static function haveRecord($result = null) {
        if(isset($result['resultSet'][0]['control'])) return true;
        return false;
    }
}

