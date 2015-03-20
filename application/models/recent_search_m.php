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
class Recent_search_m extends KAK_Model {
	public function __construct() {
		parent::__construct('recent_search', 
							array(	'id',
                                    'user_id',
                                    'type',
                                    'value',
                                    'ip',
                                    'dbCreatedOn'
							),
							NULL, 
							NULL);
	}
    
    public function add_keyword($value, $user_id = NULL) {
        $this->insert(array('type'  =>  'keyword',
                            'value' =>  $value,
                            'ip'    =>  $_SERVER['REMOTE_ADDR']));
    }
    
    public function get_list($type) {
        $results = $this->get(array(    'select'    =>  array('value','dbCreatedOn'),
                                        'type'      => $type,
                                        'limit'     => 10,
                                        'order_by'  =>  array('id'=> 'DESC')));
        return $results;
    }
}

/* End of file recent_search_m.php */
/* Location: ./application/models/recent_search_m.php */