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
class Group_has_tag_m extends KAK_Model {
	public function __construct() {
		parent::__construct('group_has_tag', 
							array(	'group_id',
                                    'tag_id'
							),
							NULL, 
							NULL);
	}
}

/* End of file group_has_tag_m.php */
/* Location: ./application/models/group_has_tag_m.php */