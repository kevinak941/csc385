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
 * @description Base controller that provides general controller functionality 
 *              Extends CI core controller
 */
 
class Base_Controller extends CI_Controller {
    // Stores error message for unified output
    // Set up for lang support
    protected $errors = array   ('invalid_search' => array(     'title'         =>  'Invalid Search Term',
                                                                'message'       =>  'The search terms you have provided are invalid, please try again',
                                                                'link'          =>  'search/byKeyword',
                                                                'linkMessage'   =>  'Back To Search')
                                );
    
    /**
     * Constructor
     * Starts parent __constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Displays error given a specific type
     * @param $type The type linked to class errors variable
     */
    public function error($type) {
        $this->load->view('header');
        $this->load->view('error', $this->errors[$type]);
        $this->load->view('footer');
        return;
    }
    
    /**
     * Custom model loader
     * Uses dynamic arguments to load models
     * Model names have _m postfix added automatically
     */
    public function load_models() {
        $models = func_get_args();
        if(count($models) > 0) {
            foreach($models as $model) {
                $this->load->model($model.'_m');
            }
        }
        return;
    }

}

/* End of file Base_Controller.php */
/* Location: ./application/controllers/Base_Controller.php */