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
 * @description Exports data in various formats
 */

class Export extends CI_Controller {

	/**
	 * Index Page for this controller
     */
	public function index()
	{
        $this->load->view('header');
		$this->load->view('main');
        $this->load->view('footer');
	}
    
    /**
     * Exports items as a csv
     * @param $filename (optional) Name of the file to save
     *                   - Default: 'epta_items'
     */
    public function items($filename = "epta_items") {
        // Force a file name
        if(!$filename || $filename == NULL)
            $filename = "epta_items";
        
        $this->load->model('item_m');
        $items = $this->item_m->get(array('select'    =>  array('item.*', 'category.name as category_name'),
                                    'join'=>array(  'category'=>'category.site_cat_id = item.category_id')));
        $output = '';
        if($items) {
            // There are items to export
            
            // Let's steal headers from first row
            foreach($items[0] as $k =>$row) {
                if($k != 'raw')
                    $output .= '"'.$k.'",';
            }
            $output .= "\n";
            
            // Now export all items 
            foreach($items as $row) {
                foreach($row as $key => $col) {
                    // Exclude raw data, don't need it
                    if($key != 'raw')
                        $output .= '"'.str_replace('"', '', (string)$col).'",';
                }
                $output .= "\n";
            }
            // Load up filename and set headers
            $filename = $filename.".csv";
            header('Content-type: application/csv');
            header('Content-Disposition: attachment;filename='.$filename);
            // Fire out output
            echo $output;
        } else {
            // No items in database
            echo "<h3>Oh no, looks like there aren't any items yet to export!</h3>";
            echo "<p>epta only collects items through its searching</p>";
        }
        exit;
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
