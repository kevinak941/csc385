<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * EPTA
 *
 * PHP version 5.3
 *
 * @category    Libraries
 * @package     EPTA
 * @author      Kevin Kern <kevinak941@gmail.com>
 * @copyright   2014-2015 Kevin Kern all rights reserved
 * @version     1.0
 * @description Handles query string building and managing
 */
class Query_builder extends Connector {
    private $_operation_name = "";
    private $_call_string = "";
    
    // Item Filters
    private $_pageNumber = 1;
    private $_keyword = "";
    private $_condition = false;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Creates a call string url to be used to retrieve data
     * @param options Additional condition arguments (TODO: Document args)
     */
    public function build($options = array()) {
        // Default operation_name
        // TODO: Dynamic operation name
        $this->operation_name = "findCompletedItems";
        // Store any provided conditions
        // TODO: Layout conditions for advanced search
        $this->_keyword = (isset($options['keyword']) ? $options['keyword'] : $this->ci->input->post('keyword'));
        if(isset($options['condition'])) $this->_condition = $options['condition'];
        if(isset($options['page'])) $this->_pageNumber = $options['page'];
        return $this->get_call_string();
    }
    
    /**
     * Create call string using private class variables
     * Requires the use of config file: keys
     * @return Finished url ready to be queried
     */
    public function get_call_string() {
        // Reset call string
        $this->_call_string = "";
        $this->_call_string .= $this->ci->config->item('ebay_endpoint');
        $this->_call_string .= "?OPERATION-NAME=".$this->operation_name;
        $this->_call_string .= "&SERVICE-VERSION=".$this->ci->config->item('ebay_version');
        $this->_call_string .= "&SECURITY-APPNAME=".$this->ci->config->item('ebay_appid');
        $this->_call_string .= "&GLOBAL-ID=".$this->ci->config->item('ebay_globalid');
        $this->_call_string .= "&keywords=".$this->_keyword;
        $this->_call_string .="&itemFilter[0].name=Condition&itemFilter[0].value=New";
        $this->_call_string .= "&paginationInput.entriesPerPage=".$this->ci->config->item('ebay_entriesPerPage');
        if($this->_pageNumber) 
            $this->_call_string .= "&paginationInput.pageNumber=".$this->_pageNumber;
        return $this->_call_string;
    }
    
}

/* End of file Query_builder.php */
/* Location: ./application/libraries/Query_builder.php */