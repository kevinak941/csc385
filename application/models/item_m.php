<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * EPTA
 *
 * PHP version 5.3
 *
 * @category    Models
 * @package     EPTA
 * @author      Kevin Kern <kevinak941@gmail.com>
 * @copyright   2014-2015 Kevin Kern all rights reserved
 * @version     1.0
 */
class Item_m extends KAK_Model {
	public function __construct() {
		parent::__construct('item', 
							array(	'id',
                                    'product_upc',
                                    'category_id',
                                    'site_item_id',
                                    'site_product_id',
                                    'site_type',
                                    'site_url',
                                    'title',
                                    'subtitle',
                                    'type',
                                    'image',
                                    'currentPrice',
                                    'bestOffer',
                                    'buyItNow',
                                    'buyItNowPrice',
                                    'startTime',
                                    'endTime',
                                    'condition',
                                    'sold',
                                    'sellingState',
                                    'shippingType',
                                    'shipToLocations',
                                    'shippingServiceCost',
                                    'topRatedListing',
                                    'raw',
                                    'dbCreatedOn',
                                    'dbUpdatedOn',
                                    'dbGenerated'
							),
							NULL, 
							NULL);
	}
    
    /**
     * Used to achieve background insertions
     */
    public function addFromPost() {
        $insert_array = array();
        foreach($this->_fields as $key) {
            if(isset($_POST[$key]))
                $insert_array[$key] = urldecode($_POST[$key]);
        }
        if($this->exists(array('site_item_id'=>$insert_array['site_item_id'], 'site_type'=>'ebay'))) {
            $update_id = $insert_array['site_item_id'];
            // Don't update when it was created or site item id
            unset($insert_array['site_item_id']);
            if(isset($insert_array['dbCreatedOn']))
                unset($insert_array['dbCreatedOn']);
            $this->update($insert_array, array('site_item_id'=>$update_id));
        } else {
            $this->insert($insert_array);
        }
    }
    
    /**
     * Internal Searching Query
     * Determines the most relevant local data to include within the search
     * @param $terms An array containing the most common features of an search
     * @param $not_ids External Item ids to avoid using
     * @param $stored_tags All the tags from the parser, used to widen search efforts
     */
    public function smartSearch($terms, $not_ids = array(), $stored_tags = array()) {
        $query_s = "SELECT * FROM item WHERE ((title LIKE ?";
        $where = array();
        $where[] = $terms['title'];
        if(isset($terms['product_id'])) {
            $query_s .= " OR site_product_id = ?)";
            $where[] = $terms['product_id'];
        } else  
            $query_s .= ")";

        if(!empty($stored_tags)) {
            $sub_string = " (";
            arsort($stored_tags);
            $stored_tags = array_slice(array_keys($stored_tags), 0, 3);

            foreach($stored_tags as $k => $v) {
                if(isset($v)) {
                    if($k !=0) $sub_string .= " AND ";
                    $sub_string .= " title LIKE '%".$this->db->escape_like_str($v)."%' ";
                }
            }
            $sub_string .=" ))";
            $query_s .= " OR ".$sub_string;
        } else 
            $query_s .= ")";
        
        
        if(!empty($not_ids)) {
            $query_s .= " AND site_item_id NOT IN (".implode(",",$not_ids).")";
        }

        $query = $this->db->query($query_s, $where);
        return $query->result_array();
    }
}

/* End of file item_m.php */
/* Location: ./application/models/item_m.php */