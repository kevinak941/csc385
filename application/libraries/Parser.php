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
class Parser {
    // Stats
    public $totalItems = 0;
    public $totalLocal = 0;
    public $totalRemote = 0;
    public $firstStartDate = NULL;
    public $lastStartDate = NULL;
    
    // Collections
    public $items = array();
    public $byCategory = array();
    public $byPrice = array();
    public $mostCommon = array();
    public $itemIds = array();
    
    
    public $matchedProductId = NULL;
    
    public $trackCommon = array();
    
    // Internal Cache
    private $_currentPrice = 0;
    private $_currentId = 0;
    private $_currentItemId = 0;
    
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
                if(is_array($item)){
                    // Parsing database content
                    // TODO: Auto assign item variables to commons
                    $product_id = $item['site_product_id'];
                    $condition = $item['condition'];
                    $title = $item['title'];
                    $subtitle = $item['subtitle'];
                    $listing_type = $item['type'];
                    $buy_it_now_price = $item['buyItNowPrice'];
                    $selling_state = $item['sellingState'];
                    $images = $item['image'];
                    $shippingServiceCost = $item['shippingServiceCost'];
                    $shippingType = $item['shippingType'];
                    $shipToLocations = $item['shipToLocations'];
                    $topRatedListing = $item['topRatedListing'];
                    $this->_currentPrice = $item['currentPrice'];
                    $this->_currentItemId   =   $item['site_item_id'];
                    $this->totalLocal++;
                    
                    if($this->firstStartDate==NULL||$item['startTime']<$this->firstStartDate) $this->firstStartDate = $item['startTime'];
                    if($this->lastStartDate==NULL||$item['startTime']>$this->lastStartDate)  $this->lastStartDate = $item['startTime'];
                } else {
                    // Parsing data from external source
                    // By Category
                    if($item->primaryCategory) {
                        $cat_string = (string)$item->primaryCategory->categoryId;
                        if(!isset($this->byCategory[$cat_string])) $this->byCategory[$cat_string] = array();
                        $this->byCategory[$cat_string][] = $item;
                    }
                    // Common Tracking
                    $product_id             = $item->productId;
                    $condition              = (string)$item->condition->conditionDisplayName;
                    $title                  = (string)$item->title;
                    $subtitle               = (string)$item->subtitle;
                    $listing_type           = (string)$item->listingInfo->listingType;
                    $buy_it_now_price       = (string)$item->listingInfo->buyItNowPrice;
                    $selling_state          = (string)$item->sellingStatus->sellingState;
                    $shippingServiceCost    = $item->shippingInfo->shippingServiceCost;
                    $shippingType           = (string)$item->shippingInfo->shippingType;
                    $shipToLocations        = (string)$item->shippingInfo->shipToLocations;
                    $topRatedListing        = $item->topRatedListing;
                    $images                 = (string)$item->galleryURL;
                    $this->_currentPrice    = (int)$item->sellingStatus->currentPrice;
                    $this->_currentItemId   = (string)$item->itemId;
                    
                    $this->items[]      = $item;
                    $this->itemIds[]    = (int)$item->itemId;
                    
                    $this->totalRemote++;
                    
                    if($this->firstStartDate==NULL||$item->listingInfo->startDate<$this->firstStartDate) $this->firstStartDate = $item->listingInfo->startDate;
                    if($this->lastStartDate==NULL||$item->listingInfo->startDate>$this->lastStartDate)  $this->lastStartDate = $item->listingInfo->startDate;
                }
                // Store common values
                $this->_addCommon('product_id', $product_id);
                $this->_addCommon('condition', $condition);
                $this->_addCommon('title', $title);
                $this->_addCommon('subtitle', $subtitle);
                $this->_addCommon('listing_type', $listing_type);
                $this->_addCommon('buy_it_now_price', $buy_it_now_price);
                $this->_addCommon('selling_state', $selling_state);
                $this->_addCommon('shipping_service_cost', $shippingServiceCost);
                $this->_addCommon('ship_to_location', $shipToLocations);
                $this->_addCommon('top_rated_listing', $topRatedListing);
                $this->_addCommon('selling_state', $selling_state);
                $this->_addCommon('images', $images);
                $this->totalItems++;
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
}

/* End of file Parser.php */
/* Location: ./application/libraries/Parser.php */