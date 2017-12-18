<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Utill\MQ\MessageMQ;

class MQMessageServiceLog extends \Utill\MQ\MessageMQ\MQMessage {
    
    
    const SERVICE_INSERT_OPERATION                 = 45;
    const SERVICE_DELETE_OPERATION                 = 46;
    const SERVICE_UPDATE_OPERATION                 = 47;
    


    public function __construct() {

    }
}

