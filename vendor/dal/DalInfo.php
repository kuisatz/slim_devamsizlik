<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace DAL;

/**
 * interface to used for CRUD operations on DAl layer
 * @author Okan CIRAN
 */
interface DalInfo {
    public function deletedAct($id = null, $params = array());
}

