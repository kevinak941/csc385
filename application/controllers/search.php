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
        $this->load->library('fork', '', 'fork');
        $this->load->library('fork', '', 'tempf');
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
    
    public function test() {
        $start = microtime(true);
            $url = $this->query->build(array('keyword'=>'iron man poster'));
            echo $url . "<br>";
            // Fetch xml from source as object
            $response = simplexml_load_file($url);
        $end = microtime(true);
        print("Time: ".($end-$start));
    }
    
    /**
     * Calculate the results of the keyword search
     * Obtains user input from either URL param or post variable
     * @param $keyword (optional) The keyword word string to be searched 
     */
    public function byKeyword_results($keyword = false) {
        // Load required models
        $this->load_models('item', 'category', 'tag', 'tag_type', 'price', 'recent_search');
        $start = microtime(true);

        $keyword = ($keyword) ? urldecode($keyword) : $this->input->post('keyword');
        if(!$keyword || $keyword == '') { $this->error('invalid_search'); return; }
        $this->parser->loadKeyword($keyword);
        $keyword = str_replace('*', '', $keyword);
        
        if($this->config->item('enable_recentSearch'))
            $this->recent_search_m->add_keyword($this->input->post('keyword'));
        
        $tt = $this->tag_type_m->get(array('array_key'=>'name'));
        
        // Perform multiple calls to ebay api
        $itemList = array();
        $this->query->set_operation("findItemsAdvanced");
        
        // Build query strings
        for($x = 1; $x <= $this->config->item('ebay_pagesPerSearch'); $x++) {
            $url = $this->query->build(array('keyword'=>$keyword, 'page'=>$x));
            $this->fork->add($url);
        }
        
        $result = $this->fork->runConcurrent('xml',function($response) {
            return $response;
        });
        $this->fork->reset();
        
        // Make sure we got a result
        if($result) { 
            // All calls are added to an response array
            // Loop through to compile each results' items
            foreach($result as $response) {
                if(!isset($itemList['item']))
                    $itemList = array_merge($itemList, (Array)$response->searchResult);
                else {
                    foreach($response->searchResult->item as $item)
                        $itemList['item'][] = $item;
                }
            }
        }

        $item_data;
        // Ensure data was added to item list
        if(!empty($itemList['item'])) {
            $this->item_handler($itemList);
            
            
            /*function cmp($a, $b) {
                if($a['num'] == $b['num'])
                    return 0;
                return ($a['num'] > $b['num']) ? -1:1;
            }
            $enhanceProdId = $this->parser->trackCommon['product_id'];
            uasort($enhanceProdId, 'cmp');

            if(count($enhanceProdId) > 0) {
            for($x = 1; $x <= $this->config->item('ebay_pagesPerSearch'); $x++) {
                $url = $this->query->build(array('keyword'=>array_keys($enhanceProdId)[0], 'page'=>$x));
                print($url."<br>");
                $this->fork->add($url);
            }
            
            $result = $this->fork->runConcurrent('xml',function($response) {
                return $response;
            });
            print(array_keys($enhanceProdId)[0]);
            // Make sure we got a result
        if($result) { 
        $itemList = array('item'=>array());
            // All calls are added to an response array
            // Loop through to compile each results' items
            foreach($result as $response) {
print_r($response);
                    foreach($response->searchResult->item as $item)
                        $itemList['item'][] = $item;

            }
        }
        print_r($itemList);exit;
$this->item_handler($itemList);
            }*/
            $this->pull_local();
            $end = microtime(true);
            //print "TIME : " . ($end-$start);
            

            //echo "<pre>";print_r($this->parser->trackCommon);exit;
            $this->load->view('header');
            $this->load->view('search_results', $this->prep_output($keyword, ($end-$start)));
            $this->load->view('footer');
            
        } else {
            // Application error
            $this->error('no_listings'); return;
        }
    }
    
    private function item_handler($itemList) {

        foreach($itemList['item'] as $key=>$value) {
                
            // Smart learning for category names
            $cat = $this->category_m->get(array('site_cat_id'   =>  (int)$value->primaryCategory->categoryId, 'single'=>TRUE));
            if(!$cat) {
                $cat_id = $this->category_m->insert(array(  'site_type'     =>  'ebay',
                                                            'site_cat_id'   =>  (int)$value->primaryCategory->categoryId,
                                                            'name'          =>  (string)$value->primaryCategory->categoryName));
            } else 
                $cat_id = $cat['id'];

            // TODO: Future Check for UPC
            //$upc_response = simplexml_load_file('http://upcdatabase.org/code/'.$value->);
            
            // Cache reusable values
            $status = $value->sellingStatus->sellingStatus;
            $cost   = (int)$value->sellingStatus->currentPrice;
            $cat_id = (int)$value->primaryCategory->categoryId;
            
            // Check if item is already in db based on site item id
            $item_data = (array(    'site_type'         =>  'ebay',
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
            $this->tempf->add(base_url('curl/addPostItem'), $item_data);
            $this->parser->explodeTitle((string)$value->title); 
        }
        if($this->config->item('enable_tagCollection')) {
            $this->tempf->add(base_url('curl/addTags'));
        }
        $this->tempf->run();
        $this->parser->scan((Array)$itemList['item']);
    }
    
    public function advanced() {
        $this->load->view('header');
        $this->load->view('search_advanced');
        $this->load->view('footer');
    }
    
    /**
     * Prep the parser's data for output
     */
    private function prep_output($keyword = false, $time) {
        
        $trackCommon = array();
        
        // Sort the most common found resources
        foreach($this->parser->trackCommon as $k => $arr) {
           // $trackCommon[$k] = array_splice($arr,0,5);
           $trackCommon[$k] = $arr;
            arsort($trackCommon[$k]);
        }
        
        $topTags = $this->parser->storedTags;
        arsort($topTags);
        
        // Get the top images from the mostCommon
        if(isset($trackCommon['images']))
            $topImages = array_splice($trackCommon['images'],0,5);
        else $topImages = false;

        if(isset($this->parser->removedItems))
            $removedItems = $this->parser->removedItems;
        else $removedItems = false;
        
        if(isset($this->parser->items)) 
            $allItems = $this->parser->items;
        else $allItems = false;
        
        // Return the view data
        return array(   'time'          =>  $time,
                        'keyword'       =>  $keyword,
                        'common'        =>  $trackCommon,
                        'topImages'     =>  $topImages,
                        'topTags'       =>  $topTags,
                        'removedItems'  =>  $removedItems,
                        'allItems'      =>  $allItems,
                        'mostCommon'    =>  $this->parser->mostCommon,
                        'stats'         =>  array(  'total'     =>  $this->parser->totalItems,
                                                    'remote'    =>  $this->parser->totalRemote,
                                                    'local'     =>  $this->parser->totalLocal,
                                                    'removed'   =>  $this->parser->totalRemoved,
                                                    'firstStartDate'    =>  $this->parser->firstStartDate,
                                                    'lastStartDate'     =>  $this->parser->lastStartDate));
    }
    
    /** 
     * Pulls specific item data from the database based on matches discoved by the parser
     */
    private function pull_local() {
        // Check if local results are enabled
        if($this->config->item('enable_localResults')) {
                // Pull any matching items from local datasource
                $local_items = $this->item_m->smartSearch($this->parser->mostCommon, $this->parser->itemIds, $this->parser->storedTags);
                // Load items into the parser
                $this->parser->scan($local_items);
            
        }
    }

}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
