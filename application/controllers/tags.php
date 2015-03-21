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
 * @description Shows data about tags
 */

class Tags extends Base_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->load->model('tag_m');
        
        $query = $this->db->query('SELECT * FROM tag LEFT JOIN price ON price.id = tag.price_id ORDER BY price.avg DESC LIMIT 10');
        $top_avg = $query->result();
        
        $query = $this->db->query('SELECT * FROM tag LEFT JOIN price ON price.id = tag.price_id ORDER BY numItems DESC LIMIT 10');
        $top_num = $query->result();
        
        $query = $this->db->query('SELECT * FROM tag LEFT JOIN price ON price.id = tag.price_id ORDER BY price.max DESC LIMIT 10');
        $top_max = $query->result();
        
        $query = $this->db->query('SELECT * FROM tag LEFT JOIN price ON price.id = tag.price_id ORDER BY price.min DESC LIMIT 10');
        $top_min = $query->result();
        
        $this->load->view('header');
        $this->load->view('tags_main', array(   'top_avg'   =>  $top_avg,
                                                'top_num'   =>  $top_num,
                                                'top_max'   =>  $top_max,
                                                'top_min'   =>  $top_min));
        $this->load->view('footer');
    }
}