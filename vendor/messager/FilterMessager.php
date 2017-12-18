<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Messager;

/**
 * concrete error messager class for filter messages
 * @author Okan CIRAN
 * @since 20/01/2016
 */
class FilterMessager extends AbstractMessager implements
                                              \Messager\Filter\FilterMessagerInterface,
                                              \Messager\FilterMessagerBaseKeyInterface{
    
    
    /**
     * filter operations message holder
     * @var string
     */
    protected $filterMessage = array();
    
    /**
     * basekey for filtered values to be arranged with a key name
     * @var string
     */
    protected $baseKey;
    
    /**
     * returns filter operations message
     * @return string
     */
    public function getFilterMessage() {
        return $this->filterMessage;
    }
    
    /**
     * set filter operations message
     * @param string $filterMessage
     */
    public function setFilterMessage($filterMessage = null) {
        $this->filterMessage = $filterMessage;
    }

    /**
     * add new message part to filter messager
     * @param string $filterMessage
     */
    public function addFilterMessage($filterMessageArray = null) {
        //print_r('okan');
        //$this->filterMessage.=$filterMessage;
        if(!is_array($filterMessageArray)) throw new \Exception ('array expected in \Messager\FilterMesseger!!');
         //array_push($this->filterMessage, $filterMessageArray);
        //array_merge($this->filterMessage[$this->baseKey], $filterMessageArray);
        $this->filterMessage[$this->baseKey] = $filterMessageArray;
    }
    
    /**
     * compare filtered values and add filter message if necessary
     * @param mixed $valuenew
     * @param mixed $valueold
     * @param mixed $filterName
     */
    public function compareValue($valuenew = null, $valueold = null, $filterName = null, $baseKey = null) {
        if(isset($baseKey)) $this->baseKey = $baseKey;
        if(strcmp($valuenew, $valueold)!=0) $this->addFilterMessage (array($filterName => array('old' => $valueold, 'new' => $valuenew)));
        //$this->addFilterMessage ('[:::]old->'.$valueold.'[:]new->'.$valuenew.'[:]filter->'.$filterName);
    }

    /**
     * gets base key, implemented from \Messager\Filter\FilterMessagerBaseKeyInterface
     * @return string
     */
    public function getBaseKey() {
        return $this->baseKey;
    }

    /**
     * set base key, implemented from \Messager\Filter\FilterMessagerBaseKeyInterface
     * @param string $baseKey
     */
    public function setBaseKey($baseKey = null) {
        $this->baseKey = $baseKey;
    }

}

