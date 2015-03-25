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
    public function loadCategories($id = '-1') {
        $this->_fetchCategories($id);
    }
    
    /**
     * Recursive function that spans over a tree of concurrent connections to 
     * fetch all children of a given category id
     * Stores any unknown categories into local database
     * @param $site_cat_id The category id to pull data about
     * To start at the root, use "-1"
     */
    private function _fetchCategories($site_cat_id) {
        $this->load->model('category_m');
        $this->load->model('price_m');
        $this->load->library('fork', '', 'fork');
        // Prep call string
        $call_string = $this->config->item('ebay_categories');
        $call_string .= "?callname=GetCategoryInfo";
        $call_string .= "&siteid=".$this->config->item('ebay_globalid');
        $call_string .= "&appid=".$this->config->item('ebay_appid');
        $call_string .= "&CategoryID=".$site_cat_id;
        $call_string .= "&IncludeSelector=ChildCategories";
        $call_string .= "&version=897";
        // Send request for xml
        $resp = simplexml_load_file($call_string);
        if($resp->Ack == "Success") {
            // Loop and process the results
            foreach($resp->CategoryArray->Category as $category) {
                if((string)$category->CategoryID != "-1") {
                    if(!$this->category_m->exists(array('site_type'=>'ebay',
                                                        'site_cat_id'   =>  (string)$category->CategoryID))){
                        // Category doesn't exist
                        $price_id = $this->price_m->insert(array('min'=>'0.00'));
                        $this->category_m->insert(array(    'site_type'     =>  'ebay',
                                                            'site_cat_id'   =>  (string)$category->CategoryID,
                                                            'name'          =>  (string)$category->CategoryName,
                                                            'price_id'      =>  $price_id));
                                                        
                    }
                    // Magic time: trigger concurrent process to start fetching children of this category
                    if((string)$category->LeafCategory=="false") 
                        $this->fork->add(base_url('admin/loadCategories/'.(string)$category->CategoryID))->run();
                }
            }
        }
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
