<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Utill\Mail;


interface InfoMailInterface  {

    public function sendInfoMail(array $params = null);
    public function sendInfoMailSMTP(array $params = null);
    public function sendInfoMailSMTPDebug(array $params = null);

}
