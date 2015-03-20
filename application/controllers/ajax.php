<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * EPTA
 *
 * PHP version 5.3
 *
 * @category    Controllers
 * @package     EPTA
 * @author      Kevin Kern <kevinak941@gmail.com>
 * @copyright   2014-2015 Kevin Kern all rights reserved
 * @version     1.0
 * @description Supplies JSON to ajax requests
 */
 
class Ajax extends CI_Controller {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        // Prevent access to non-ajax requests
        if(!$this->input->is_ajax_request()) return false;
    }
    
    /**
     * Retrieve items given a list of comma delimited site_item_ids 
     */
    public function getItemsById() {
        $this->load->model('item_m');
        
        $items = $this->input->post('items');
        if(!is_array($items))
            $items = explode(', ', $items);
        
        $results = $this->item_m->get(array('in'=>array('site_item_id'=>$items)));
        echo json_encode($results);
    }

}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */