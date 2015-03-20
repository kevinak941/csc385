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
 * @description Controller for performing tests on performance and object output
 */
 
class Test extends CI_Controller {
    private $_active = FALSE;
    
    /**
     * Constructor
     * Die if not in use 
     */
	public function __construct() {
        parent::__construct();
        if(!$this->_active)
            die('Tests are not enabled');
    }
}

/* End of file test.php */
/* Location: ./application/controllers/test.php */