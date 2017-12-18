<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Services\Validator;

/**
 * service manager layer for validation chainer which is using \Zend\Validator\ValidatorChainer
 * @author Okan CIRAN
 * @version 13/01/2016
 */
class ValidationChainerServiceForZendChainer implements \Zend\ServiceManager\FactoryInterface {
    
    /**
     * service ceration via factory on zend service manager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return Utill\Strip\Strip
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        // Create a message broker for usage
        $zendChainerValidator = new \Utill\Validation\ZendChainerValidator();
        return $zendChainerValidator;

    }

}