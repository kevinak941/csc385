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
class Product_upc_m extends KAK_Model {
	public function __construct() {
		parent::__construct('product_upc', 
							array(	'upc',
                                    'name',
                                    'alias',
                                    'description',
                                    'verified'
							),
							NULL, 
							NULL);
	}
}

/* End of file product_upc_m.php */
/* Location: ./application/models/product_upc_m.php */