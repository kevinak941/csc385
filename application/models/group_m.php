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
class Group_m extends KAK_Model {
	public function __construct() {
		parent::__construct('group', 
							array(	'id',
                                    'name',
                                    'price_id'
							),
							NULL, 
							NULL);
	}
}

/* End of file group_m.php */
/* Location: ./application/models/group_m.php */