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
class Tag_type_m extends KAK_Model {
	public function __construct() {
		parent::__construct('tag_type', 
							array(	'id',
                                    'name',
                                    'weight'
							),
							NULL, 
							NULL);
	}
}

/* End of file tag_type_m.php */
/* Location: ./application/models/tag_type_m.php */