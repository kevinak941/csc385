<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * EPTA
 * Application Programming Interface (API)
 *
 * PHP version 5.3
 *
 * @category    Controllers
 * @package     EPTA
 * @author      Kevin Kern <kevinak941@gmail.com>
 * @copyright   2014-2015 Kevin Kern all rights reserved
 * @version     1.0
 * @description Provides API methods and documentation
 */
 
class API extends CI_Controller {
    
    /**
     * Serves core documentation page
     */
	public function index()
	{
		// Documentation
        $this->load->view('header');
        $this->load->view('api/main');
        $this->load->view('footer');
	}
    
    /**
     * Fetches a list of all the tags in the system
     * @param $keyword (optional) Filters results to match a specific keyword
     */
    public function getTags($keyword = '') {
        $this->load->model('tag_m');
        if($keyword == NULL)
            $items = $this->tag_m->get(array('select'=>array('id', 'value', 'numItems')));
        else
            $items = $this->tag_m->get(array('select'=>array('id', 'value', 'numItems'), 'like'=>array('value'=>$keyword)));
        echo json_encode($items);
    }
    
    /**
     * Fetches item info with a list of tags linked to it based on item id
     * @param $item_id The native id of the item 
     */
    public function getTagsByItemId($item_id) {
        $this->load->model('item_m');
        $this->load->model('tag_m');
        $this->load->model('item_has_tag_m');
        
        if($item_id) {
            $results['item'] = $this->item_m->get(array('id'=>$item_id));
            $results['tags'] = $this->item_has_tag_m->get(array('select'                 =>  array('tag.id', 'tag.value', 'tag.numItems AS connections'),
                                                                'item_has_tag_m.item_id' =>  $item_id,
                                                                'join'                   =>  array('tag'=>'tag.id=item_has_tag.tag_id')));
        }
        echo json_encode($results);
    }
    
    /**
     * Fetches item info with a list of tags linked to it based on an ebay id
     * @param $item_id The native id of the item 
     */
    public function getTagsByEbayId($ebay_id) {
        $this->load->model('item_m');
        $this->load->model('tag_m');
        $this->load->model('item_has_tag_m');
        
        if($ebay_id) {
            $results['item'] = $this->item_m->get(array('site_item_id'=>$ebay_id));
            $results['tags'] = $this->item_has_tag_m->get(array('select'                 =>  array('tag.id', 'tag.value', 'tag.numItems AS connections'),
                                                                'item_has_tag_m.item_id' =>  $ebay_id,
                                                                'join'                   =>  array('tag'=>'tag.id=item_has_tag.tag_id')));
        }
        echo json_encode($results);
    }
    
    
    public function getItems() {
        $this->load->model('item_m');
        $items = $this->item_m->get();
        echo json_encode($items);
    }
    
    public function getCategories() {
        $this->load->model('category_m');
        $items = $this->category_m->get();
        echo json_encode($items);
    }
}

/* End of file api.php */
/* Location: ./application/controllers/api.php */