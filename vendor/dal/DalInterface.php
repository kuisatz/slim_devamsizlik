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
interface DalInterface {
    public function getAll($params = array());
    public function update($params = array());
    public function delete ($params = array());
    public function insert($params = array());
    public function haveRecords($params = array());
}

