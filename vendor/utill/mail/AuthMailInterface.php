<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Utill\Mail;


interface AuthMailInterface  {

    public function sendAuthMail(array $params = null);
    public function sendAuthMailDebug(array $params = null);

}
