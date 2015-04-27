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
 * @description Parses data from either external or internal source
 */
class Parser extends Connector{
    // Stats
    public $totalItems = 0;
    public $totalLocal = 0;
    public $totalRemote = 0;
    public $totalRemoved = 0;
    public $firstStartDate = NULL;
    public $lastStartDate = NULL;
    
    // Collections
    public $items           = array();
    public $byCategory      = array();
    public $pricesByCategory= array();
    public $byPrice         = array();
    public $mostCommon      = array();
    public $itemIds         = array();
    public $removedItems    = array();
    
    
    public $matchedProductId= NULL;
    public $storedTags      = array();
    public $trackCommon     = array();
    
    // Internal Cache
    private $_currentItem   = false;
    private $_currentDate   = null;
    private $_currentPrice  = 0;
    private $_currentId     = 0;
    private $_currentItemId = 0;
    private $_tag_outliers  = array();
    
    private $_tag_types         = array();
    private $_weightedKeywords  = array();
    
    public function __construct() {
        parent::__construct();
        $this->ci->load->model('tag_type_m');
        // Load in tag types
        if(empty($this->_tag_types)) 
            $this->_tag_types = $this->ci->tag_type_m->get(array('array_key'=>'name'));
    }
    
    public function loadKeyword($keyword = NULL) {
        if($keyword) {
            $ex = explode(' ', $keyword);
            foreach($ex as $tag) {
                if($tag != '' && strpos($tag, '*') === 0)
                    $this->_weightedKeywords[] = substr(strtolower($tag), 1, strlen($tag));
            }
        }
    }
    
    /**
     * Scans a given set of items pulling out what data it needs
     * Stores values into commons for tracking
     * @param $items Array of items that needs to be scaned (can be internal or external source)
     */
    public function scan($items = array()) {
        // Make sure there are results to consider
        if(!empty($items)) {
            // Loop through the items performing parse
            foreach($items as $key => $item) {
                // Get the title match rating for this item
                $rating = $this->getMatchRating((isset($item['title']) ? $item['title'] : (string)$item->title));
                // Check if title match is enabled and the item has failed the test
                if($this->ci->config->item('enable_titleMatching') && $rating < ((array_sum($this->storedTags)*$this->ci->config->item('titleMatching_tolerance'))-count($this->_tag_outliers))) {
                    // Item did not pass tag calculation
                    // Only add live data to removed items 
                    // Note: It would be silly to pull database content then filter it out
                    if(is_object($items[$key])) {
                        // Add the item to removed list
                        $this->removedItems[] = $items[$key];
                        // Increment Counters
                        $this->totalItems++;
                        $this->totalRemoved++;
                    }
                    unset($items[$key]);
                } else {
                    $this->totalItems++;
                    if(is_array($item)){
                        // Parsing database content
                        // TODO: Auto assign item variables to commons
                        $product_id             = $item['site_product_id'];
                        $condition              = $item['condition'];
                        $title                  = $item['title'];
                        $subtitle               = $item['subtitle'];
                        $type                   = $item['type'];
                        $buyItNowPrice          = $item['buyItNowPrice'];
                        $sellingState           = $item['sellingState'];
                        $image                  = $item['image'];
                        $shippingServiceCost    = $item['shippingServiceCost'];
                        $shippingType           = $item['shippingType'];
                        $shipToLocations        = $item['shipToLocations'];
                        $topRatedListing        = $item['topRatedListing'];
                        $this->_currentPrice    = $item['currentPrice'];
                        $this->_currentItemId   =   $item['site_item_id'];
                        $this->_currentDate     = $item['endTime'];
                        $this->totalLocal++;
                        
                        if($this->firstStartDate==NULL||$item['startTime']<$this->firstStartDate) $this->firstStartDate = $item['startTime'];
                        if($this->lastStartDate==NULL||$item['startTime']>$this->lastStartDate)  $this->lastStartDate = $item['startTime'];
                    } else {
                        // Parsing data from external source
                        // By Category
                        if($item->primaryCategory) {
                            $cat_string = (string)$item->primaryCategory->categoryId;
                            if(!isset($this->pricesByCategory[$cat_string])) $this->pricesByCategory[$cat_string] = array();
                            $this->pricesByCategory[$cat_string][] = (int)$item->sellingStatus->currentPrice;
                        }
                        // Common Tracking
                        $product_id             = $item->productId;
                        $condition              = (string)$item->condition->conditionDisplayName;
                        $title                  = (string)$item->title;
                        $subtitle               = (string)$item->subtitle;
                        $type                   = (string)$item->listingInfo->listingType;
                        $buyItNowPrice          = (string)$item->listingInfo->buyItNowPrice;
                        $sellingState           = (string)$item->sellingStatus->sellingState;
                        $shippingServiceCost    = $item->shippingInfo->shippingServiceCost;
                        $shippingType           = (string)$item->shippingInfo->shippingType;
                        $shipToLocations        = (string)$item->shippingInfo->shipToLocations;
                        $topRatedListing        = $item->topRatedListing;
                        $image                  = (string)$item->galleryURL;
                        $this->_currentPrice    = (int)$item->sellingStatus->currentPrice;
                        $this->_currentItemId   = (string)$item->itemId;
                        $this->_currentDate     = (string)$item->listingInfo->endTime;
                        $this->items[]      = $item;
                        $this->itemIds[]    = (string)$item->itemId;
                        
                        $this->totalRemote++;
                        
                        if($this->firstStartDate==NULL||$item->listingInfo->startDate<$this->firstStartDate) $this->firstStartDate = $item->listingInfo->startDate;
                        if($this->lastStartDate==NULL||$item->listingInfo->startDate>$this->lastStartDate)  $this->lastStartDate = $item->listingInfo->startDate;
                    }
                    
                    // Store common values
                    $this->_addCommon('product_id', $product_id);
                    $this->_addCommon('condition', $condition);
                    $this->_addCommon('title', $title);
                    $this->_addCommon('subtitle', $subtitle);
                    $this->_addCommon('listing_type', $type);
                    $this->_addCommon('buy_it_now_price', $buyItNowPrice);
                    $this->_addCommon('selling_state', $sellingState);
                    $this->_addCommon('shipping_service_cost', $shippingServiceCost);
                    $this->_addCommon('ship_to_location', $shipToLocations);
                    $this->_addCommon('top_rated_listing', $topRatedListing);
                    $this->_addCommon('selling_state', $sellingState);
                    $this->_addCommon('images', $image);
                }
            }
            $this->_buildCommon();
            
        } else {
        
        }
    }
    
    /**
     * Increments the value of a specific common counter by 1
     * @param $type The type of common variable to add (listing_type, subtitle, etc)
     * @param $value The value to add
     */
    private function _addCommon($type, $value) {
        // Invalid type given
        if($value=='') return false;
        if(!isset($this->trackCommon[$type])) $this->trackCommon[$type] = array();
        $value = strval($value);
        
        // Check initialization of common arrays 
        if(!isset($this->trackCommon[$type][$value]))
            $this->trackCommon[$type][$value] = array();
        
        if(!isset($this->trackCommon[$type][$value]['num']))
            $this->trackCommon[$type][$value]['num'] = 0;
        
        if(!isset($this->trackCommon[$type][$value]['total']))
            $this->trackCommon[$type][$value]['total'] = 0;
        
        if(!isset($this->trackCommon[$type][$value]['max']))
            $this->trackCommon[$type][$value]['max'] = $this->_currentPrice;
        
        if(!isset($this->trackCommon[$type][$value]['min']))
            $this->trackCommon[$type][$value]['min'] = $this->_currentPrice;
        
        if(!isset($this->trackCommon[$type][$value]['ids']))
            $this->trackCommon[$type][$value]['ids'] = array();
        
        if(!isset($this->trackCommon[$type][$value]['itemIds']))
            $this->trackCommon[$type][$value]['itemIds'] = array();
        // End Array Check
        
        // Increment/Add required values
        $this->trackCommon[$type][$value]['num'] += 1;
        $this->trackCommon[$type][$value]['total'] += $this->_currentPrice;
        if($this->trackCommon[$type][$value]['min']>$this->_currentPrice) $this->trackCommon[$type][$value]['min'] = $this->_currentPrice;
        if($this->trackCommon[$type][$value]['max']<$this->_currentPrice) $this->trackCommon[$type][$value]['max'] = $this->_currentPrice;
        $this->trackCommon[$type][$value]['itemIds'][] = $this->_currentItemId;
        $this->trackCommon[$type][$value]['ids'][] = $this->_currentId;
        $this->trackCommon[$type][$value]['date'][] = array('date'=>$this->_currentDate, 'price'=> (int)$this->_currentPrice);
    }
    
    /**
     * Calculates the most common of each common tracker
     * Stores as class variable mostCommon 
     */
    private function _buildCommon() {
        foreach($this->trackCommon as $k => $type) {
            if(!empty($type)) {
                $max = array_keys($type, max($type));
                $this->mostCommon[$k] = $max[0];
            } else
                $this->mostCommon[$k] = NULL;
        }
    }
    
    public function explodeTitle($title, $store = true) {
        $tag_type = $this->_tag_types['title'];
        // Check if tag type requires any chars to be removed
        if(isset($tag_type['remove_chars'])) {
            // Format remove chars to support replacement
            $removes = explode('\',',substr($tag_type['remove_chars'], 1, -1));
            $tags = str_replace($removes, '', (string)$title);
        }
        // Explode tag string by predefined delimiter
        $ex = explode($tag_type['delimiter'], $tags);
        if($store) {
            foreach($ex as $tag) {
                $tag = strtolower($tag);
                if(!isset($this->storedTags[$tag])) 
                    $this->storedTags[$tag] = 0;

                $this->storedTags[$tag]++;
            }
        }
        return $ex;
    }
    
    /**
     * Must be called after stored tags have been calculated by explodeTitle
     */
    public function getMatchRating($title) {
        $ex = $this->explodeTitle($title, false);
        $rating = 0;
        foreach($ex as $tag) {
            $tag = strtolower($tag);
            if($tag != '' && isset($this->_weightedKeywords[$tag])) {
                if(isset($this->storedTags[$tag])) $rating+=($this->storedTags[$tag]*2);
            } else {
                if(isset($this->storedTags[$tag])) $rating+=($this->storedTags[$tag] > 1) ? $this->storedTags[$tag] : 0;
            }
            if(isset($this->storedTag[$tag]) && $this->storedTags[$tag] < 2) {
                $this->_tag_outliers[] = $tag;
            }
        }
        return $rating;
    }
}

/* End of file Parser.php */
/* Location: ./application/libraries/Parser.php */