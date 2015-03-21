<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * EPTA
 * Admin Controller
 *
 * PHP version 5.3
 *
 * @category    Controllers
 * @package     EPTA
 * @author      Kevin Kern <kevinak941@gmail.com>
 * @copyright   2014-2015 Kevin Kern all rights reserved
 * @version     1.0
 * @description Provides admin methods for updating EPTA data
 *              TODO: Port to cron jobs
 */
class Admin extends CI_Controller {

	/**
	 * Constructor
     * Loads key configuration
     */
	public function __construct() {
        parent::__construct();
        $this->load->config('keys');
	}
    
    /**
     * Pull categories from ebay and add to our local database for rapid lookup
     * TODO: Add addition categories for other sources
     */
    public function loadCategories() {
        $call_string = $this->config->item('ebay_trading');
        $call_string = "https://api.ebay.com/wsapi";
        $call_string .= "?callname=getCategories";
        $call_string .= "&siteid=".$this->config->item('ebay_globalid');
        $call_string .= "&appid=".$this->config->item('ebay_appid');
        
        $call_string .= "&version=".$this->config->item('ebay_version');
        $call_string .= "&Routing=new";
        $resp = simplexml_load_file($call_string);
        print_r($resp);
    }
    
    /**
     * Removes all data from relevant tables
     * WARNING: THIS IS FOR REAL
     * TODO: Enable only in developer mode
     */
    public function wipeAllData() {
        $this->db->truncate('item');
        $this->db->truncate('price');
        $this->db->truncate('tag');
        $this->db->truncate('item_has_tag');
        $this->db->truncate('category');
        
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */
