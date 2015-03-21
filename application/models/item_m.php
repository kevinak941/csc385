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
                                    'raw',
                                    'dbCreatedOn',
                                    'dbUpdatedOn',
                                    'dbGenerated'
							),
							NULL, 
							NULL);
	}
}

/* End of file item_m.php */
/* Location: ./application/models/item_m.php */