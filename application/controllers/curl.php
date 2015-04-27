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
 * @description 
 */

class Curl extends Base_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('store', '', 'store');
    }
    
    public function addPostItem() {
        $this->load->model('item_m');
        $this->item_m->addFromPost();
        //return "complete";
    }
    
    public function addTags() {
        $this->load->model('item_m');
        $this->load->model('tag_m');
        $this->load->model('price_m');
        //$this->store->tag('title', 'hey', '565', '1');
        
        $items = $this->item_m->get(array('dbGenerated' => '0'));
        if($items) {
            $update_tag_ids = array();
            foreach($items as $item) {
                $tag_ids = array();
                $tag_ids = $this->store->tag('title', $item['title'], $item['id'], $item['category_id']);
                $tag_ids = array_merge($tag_ids, $this->store->tag('subtitle', $item['subtitle'], $item['id'], $item['category_id']));
                foreach($tag_ids as $t_id) {
                    if(!isset($update_tag_ids[$t_id])) $update_tag_ids[$t_id] = array();
                    $update_tag_ids[$t_id][] = $item['currentPrice'];
                }
                $this->item_m->update(array('dbGenerated'=>1), array('id'=>$item['id']));
            }
            foreach($update_tag_ids as $key => $arr) {
                $tag = $this->tag_m->get(array('select'=>array('tag.id', 'value', 'price_id', 'numItems', 'avg', 'max', 'min'), 'tag.id'=>$key, 'single'=>TRUE, 'join'=>array('price'=>'price.id=tag.price_id')));
                if($tag) {
                    $num = $tag['numItems'];
                    $total = $tag['avg'] * $num;
                    $max = $tag['max'];
                    $min = $tag['min'];
                    foreach($arr as $price) {
                        echo "PRICE : ".$price."<br>";
                        $total += $price;
                        if($price>$max) $max = $price;
                        if($price<$min || $min==0.00) $min = $price;
                        $num++;
                    }
echo "TSG : ".$min." ".$max." ".($total)."<br>";
                    $this->price_m->update(array('min'=>$min, 'max'=>$max, 'avg'=>($total/$num)), array('id'=>$tag['price_id']));
                    $this->tag_m->update(array('numItems'=>$num), array('id'=>$tag['id']));
                    
                }
            }
        }
    }
    
}