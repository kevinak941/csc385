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
 * @description Serves up search pages: byKeyword and advanced
                Using key libraries to process and provide results
 */

class Search extends Base_Controller {
    
    /**
     * Constructor 
     * Loads libraries needed in controller
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('query_builder', '', 'query');
        $this->load->library('parser', '', 'parser');
        $this->load->library('store', '', 'store');
    }

	/**
	 * Index Page for this controller
     * Defaults to search by keyword
     */
	public function index() {
        //Default to keyword search
        $this->byKeyword();
	}
    
    /**
     * Output view for search by keyword form
     */
    public function byKeyword($local = FALSE) {
        $this->load->model('recent_search_m');
        // Get list of recent searches
        $results = $this->recent_search_m->get_list('keyword');
        
        $this->load->view('header');
        $this->load->view('search_byKeyword', array(    'isLocal'       =>  $local,
                                                        'pastSearches'  =>  $results));
        $this->load->view('footer');
    }
    
    /**
     * Calculate the results of the keyword search
     * Obtains user input from either URL param or post variable
     * @param $keyword (optional) The keyword word string to be searched 
     */
    public function byKeyword_results($keyword = false) {
        // Load required models
        $this->load_models('item', 'category', 'tag', 'tag_type', 'price', 'recent_search');
        
        if($keyword) {
            $keyword = urldecode($keyword);
        } else {
            $keyword = $this->input->post('keyword');
        }
        
        if(!$keyword || $keyword == '') { $this->error('invalid_search'); return; }
        
        
        if($this->config->item('enable_recentSearch'))
            $this->recent_search_m->add_keyword($this->input->post('keyword'));
        
        $tt = $this->tag_type_m->get(array('array_key'=>'name'));
        
        // Perform multiple calls to ebay api
        for($x = 1; $x <= $this->config->item('ebay_pagesPerSearch'); $x++) {
            $url = $this->query->build(array('keyword'=>$keyword, 'page'=>$x));
            // Fetch xml from source as object
            $response = simplexml_load_file($url);
            // Only parse if successful
            if($response->ack == "Success") { 
                // Scan item 
                $this->parser->scan($response->searchResult->item);
            }
        }
        
        if(count($this->parser->items) > 0) {
            foreach($this->parser->items as $value) {
                $cat = $this->category_m->get(array('site_cat_id'   =>  (int)$value->primaryCategory->categoryId, 'single'=>TRUE));
                if(!$cat) {
                    $cat_id = $this->category_m->insert(array(  'site_cat_id'   =>  (int)$value->primaryCategory->categoryId,
                                                                'name'          =>  (string)$value->primaryCategory->categoryName));
                } else 
                    $cat_id = $cat['id'];

                // Check for UPC
                //$upc_response = simplexml_load_file('http://upcdatabase.org/code/'.$value->);
                
                // Cache reusable values
                $status = $value->sellingStatus->sellingStatus;
                $cost   = (int)$value->sellingStatus->currentPrice;
                $cat_id = (int)$value->primaryCategory->categoryId;
                
                // Check if item is already in db based on site item id
                if(!$this->item_m->exists(array('site_item_id'=>$value->itemId, 'site_type'=>'ebay'))) {
                    // Site specific item not found in our database
                    
                    $item_id = 
                    $this->item_m->insert(array(    'site_type'         =>  'ebay',
                                                    'site_item_id'      =>  (string)$value->itemId,
                                                    'site_product_id'   =>  (string)$value->productId,
                                                    'site_url'          =>  (string)$value->viewItemURL,
                                                    'category_id'       =>  $cat_id,
                                                    'title'             =>  (string)$value->title,
                                                    'subtitle'          =>  (string)$value->subtitle,
                                                    'type'              =>  (string)$value->listingInfo->listingType,
                                                    'image'             =>  (string)$value->galleryURL,
                                                    'currentPrice'      =>  $cost,
                                                    'bestOffer'         =>  (int)$value->listingInfo->bestOfferEnabled,
                                                    'buyItNow'          =>  (int)$value->listingInfo->buyItNowAvailable,
                                                    'buyItNowPrice'     =>  (isset($value->listingInfo->buyItNowPrice) ? $value->listingInfo->buyItNowPrice : null),
                                                    'startTime'         =>  (string)$value->listingInfo->startTime,
                                                    'endTime'           =>  (string)$value->listingInfo->endTime,
                                                    'condition'         =>  (string)$value->condition->conditionDisplayName,
                                                    'sold'              =>  (int)(($status=='EndedWithoutSales'||$status=='EndedWithSales')?1:0),
                                                    'raw'               =>  (string)print_r($value, true)
                                                ));
                    
                    //$this->store->tag('title', (string)$value->title, $item_id, $cat_id);
                    //$this->store->tag('subtitle', (string)$value->subtitle, $item_id, $cat_id);
                } else {
                    // Site specific item found in our database
                    // Update it's values
                    $this->item_m->update(array(    'category_id'       =>  (int)$value->primaryCategory->categoryId,
                                                    'image'             =>  (string)$value->galleryURL,
                                                    'currentPrice'      =>  $cost,
                                                    'bestOffer'         =>  (int)$value->listingInfo->bestOfferEnabled,
                                                    'buyItNow'          =>  (int)$value->listingInfo->buyItNowAvailable,
                                                    'buyItNowPrice'     =>  (isset($value->listingInfo->buyItNowPrice) ? $value->listingInfo->buyItNowPrice : null),
                                                    'startTime'         =>  (string)$value->listingInfo->startTime,
                                                    'endTime'           =>  (string)$value->listingInfo->endTime,
                                                    'sold'              =>  (int)(($status=='EndedWithoutSales'||$status=='EndedWithSales')?1:0),
                                                    'raw'               =>  (string)print_r($value, true)
                                                ),
                                            array('site_item_id'=>(string)$value->itemId));
                }
                
            }
            
            $this->pull_local();
            //echo "<pre>";print_r($this->parser->trackCommon);exit;
            $this->load->view('header');
            $this->load->view('search_results', $this->prep_output());
            $this->load->view('footer');
        } else {
            // Application error
        }
    }
    
    public function advanced() {
        $this->load->view('header');
        $this->load->view('search_advanced');
        $this->load->view('footer');
    }
    
    /**
     * Prep the parser's data for output
     */
    private function prep_output() {
        
        $trackCommon = array();
        
        // Sort the most common found resources
        foreach($this->parser->trackCommon as $k => $arr) {
           // $trackCommon[$k] = array_splice($arr,0,5);
           $trackCommon[$k] = $arr;
            arsort($trackCommon[$k]);
        }
        
        // Get the top images from the mostCommon
        $topImages = array_splice($trackCommon['images'],0,5);
        
        // Return the view data
        return array(   'common'        =>  $trackCommon,
                        'topImages'     =>  $topImages,
                        'mostCommon'    =>  $this->parser->mostCommon,
                        'stats'         =>  array(  'total'     =>  $this->parser->totalItems,
                                                    'remote'    =>  $this->parser->totalRemote,
                                                    'local'     =>  $this->parser->totalLocal,
                                                    'firstStartDate'    =>  $this->parser->firstStartDate,
                                                    'lastStartDate'     =>  $this->parser->lastStartDate));
    }
    
    /** 
     * Pulls specific item data from the database based on matches discoved by the parser
     */
    private function pull_local() {
        if($this->config->item('enable_localResults')) {
            // Check if parser found a most common product id
            if(isset($this->parser->mostCommon['product_id'])) {
                // Pull any matching items from local datasource
                $local_items = $this->item_m->get(array('site_product_id' => $this->parser->mostCommon['product_id'], 'not_in' => array('site_item_id'=>$this->parser->itemIds)));
                // Load items into the parser
                $this->parser->scan($local_items);
            }
        }
    }

}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
