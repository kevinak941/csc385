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
 * @description Responsible for serving the home page
                Future: User session management
 */

class Main extends CI_Controller {

	/**
	 * Index Page for this controller
     */
	public function index()
	{
        $this->load->view('header');
		$this->load->view('main');
        $this->load->view('footer');
	}
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
