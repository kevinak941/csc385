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
 * @description Provides methods for linking objects
 *              Example: Linking an item to a tag
 */
class Linker extends Connector {
    
    /**
     * Constructor
     * Loads required models
     */
    public function __construct() {
        parent::__construct();
        // Load linking models
        $this->ci->load->model('tag_type_m');
        $this->ci->load->model('item_has_tag_m');
    }
    
    /**
     * Links an item to a tag
     * @param $item_id The internal item id of the desired item
     * @param $tag_id The internal tag id of the desired tag
     */
    protected function linkItemToTag($item_id, $tag_id) {
        // Check if item id provided
        if($item_id&&$tag_id) {
            // Make sure tag and item are not already linked
            if(!$this->ci->item_has_tag_m->exists(array('item_id'   =>  $item_id,
                                                        'tag_id'    =>  $tag_id))) {
                // Add link from tag to item
                $this->ci->item_has_tag_m->insert(array('item_id'   =>  $item_id,
                                                        'tag_id'    =>  $tag_id));
            }
        }
    }
    
}