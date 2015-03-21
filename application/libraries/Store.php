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
 * @description Handles the storing of various data
                Specifically handles tags and tag groups
 */
 
/**
 * CONTROLLER NOT DEVELOPED YET
 */
class Store extends Linker {
    private $tag_types = array();
    
    private $_tag_cache = array();
    
    /** 
     * Constructor
     */
    public function __construct() {
        // Construct the parent
        parent::__construct();
        // Load required models
        $this->ci->load->model('tag_m');
        $this->ci->load->model('price_m');
    }
    
    public function tag($type = FALSE, $tags = '', $item_id = NULL, $category_id = NULL) {
        if(!$type) return FALSE;
        if(!isset($this->tag_types[$type])) 
            $this->tag_types = array_merge($this->tag_types, $this->ci->tag_type_m->get(array('name'=>$type, 'array_key'=>'name')));
        $tag_type = $this->tag_types[$type];
        
        $return_tag_ids = array();
        if($tags !== '') {
            // Check if tag type requires any chars to be removed
            if(isset($tag_type['remove_chars'])) {
                // Format remove chars to support replacement
                $removes = explode('\',',substr($tag_type['remove_chars'], 1, -1));
                $tags = str_replace($removes, '', (string)$tags);
            }
            // Explode tag string by predefined delimiter
            $ex = explode($tag_type['delimiter'], $tags);
            foreach($ex as $k=>$tag) {
                // Check store's cache before asking DB
                if(array_key_exists($tag, $this->_tag_cache)) {
                    $otag = $this->_tag_cache[$tag];
                } else
                    $otag = $this->ci->tag_m->get(array('value'=>$tag, 'single'=>TRUE));
                
                if($otag!==FALSE) {
                    //$price = $this->ci->price_m->get(array('id'=>$otag['price_id']));
                    $this->ci->tag_m->update(   array('numItems'=>  $otag['numItems']+1),
                                                array('id'      =>  $otag['id']));
                    $tag_id = $otag['id'];
                    
                   // $this->_tag_cache[$otag['value']] = array('id'=>$tag_id,'numItems'=>$otag['numItems']+1);
                } else {
                    
                    // Set up a base price grouping for the tag
                    $price_id = $this->ci->price_m->insert(array('min'=>'0.00'));
                    // Add the tag and link it to its new price grouping
                    $tag_id = $this->ci->tag_m->insert(array(   'value'         =>  $tag,
                                                                'tag_type_id'   =>  $tag_type['id'],
                                                                'price_id'      =>  $price_id));
                    $this->_tag_cache[$tag] = array('id'=>$tag_id,'numItems'=>1);
                    
                }
                $return_tag_ids[] = $tag_id;
                parent::linkItemToTag($item_id, $tag_id);
            }
            return $return_tag_ids;
            //$this->tag_group($ex);
        }
        return array();
    }
    
    public function tag_group($tags = array()){
        $tag_length = count($tags);
        
        function array_cartesian($arr = array()) {
            $_ = $arr;//func_get_args();
            if(count($_) == 0) return array(array());
            $a = array_shift($_);
            $c = call_user_func_array(__FUNCTION__, array($_));
            $r = array();
            if(is_array($a)) {
            foreach($a as $v) {
                foreach($c as $p)
                    $r[] = array_merge(array($v), $p);
            }
            }
            return $r;
        } echo '<pre>';
        //array_cartesian($tags, $tags);
        $cross = array_cartesian(array($tags, $tags));
        /*$prep = array($tags);
        for($x = 0; $x < $tag_length; $x++) {
            $prep[] = $tags;
            //print_r($prep);
            $cross[] = array_cartesian($prep);
        }*/
       
        foreach($cross as $k => $v) {
            $len = count($cross[$k]);
            $cross[$k] = array_unique($cross[$k]);
            if(count($cross[$k]) < $len) unset($cross[$k]);
        }
        print_r($cross);
        return $cross;
    }
}

/* End of file Store.php */
/* Location: ./application/libraries/Store.php */