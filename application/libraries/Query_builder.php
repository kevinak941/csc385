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
    
    private $_itemFilters = array('Condition');
    
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
        $this->_operation_name = "findCompletedItems";
        $this->_keyword = (isset($options['keyword']) ? $options['keyword'] : $this->ci->input->post('keyword'));
        if(isset($options['page'])) $this->_pageNumber = $options['page'];
        return $this->get_call_string();
    }
    
    public function set_operation($name) {
        $this->_operation_name = $name;
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
        $this->_call_string .= "?OPERATION-NAME=".$this->_operation_name;
        $this->_call_string .= "&SERVICE-VERSION=".$this->ci->config->item('ebay_version');
        $this->_call_string .= "&SECURITY-APPNAME=".$this->ci->config->item('ebay_appid');
        $this->_call_string .= "&GLOBAL-ID=".$this->ci->config->item('ebay_globalid');
        $this->_call_string .= "&keywords=".urlencode($this->_keyword);
        // Load any posted filters
        $this->_call_string .= $this->_build_filter();
        $this->_call_string .= "&paginationInput.entriesPerPage=".$this->ci->config->item('ebay_entriesPerPage');
        if($this->_pageNumber) 
            $this->_call_string .= "&paginationInput.pageNumber=".$this->_pageNumber;
        //print($this->_call_string);exit;
        return $this->_call_string;
    }
    
    private function _build_filter() {
        $string = "";
        $count = 0;
        foreach($this->_itemFilters as $filter) {
            $formData = $this->ci->input->post($filter);
            if(is_array($formData)) {
                foreach($formData as $v) {
                    $string .= $this->_call_string .="&itemFilter[".$count."].name=".$filter."&itemFilter[".$count."].value=".$v;
                    $count++;
                }
            }
        }
        return $string;
    }
    
}

/* End of file Query_builder.php */
/* Location: ./application/libraries/Query_builder.php */