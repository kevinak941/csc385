<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * EPTA
 *
 * PHP version 5.3
 *
 * @category    Libraries
 * @package     EPTA
 * @author      Kevin Kern <kevinak941@gmail.com>
 * @copyright   2014-2015 Kevin Kern all rights reserved
 * @version     1.0
 * @description Simple connector that extends CI instance to children
 */
class Connector {
    protected $ci;
    
    /**
     * Constructor
     * Gets instance of CI
     */
    public function __construct() {
        // Get instance of framework
        $this->ci = &get_instance();
    }
}

/* End of file Connector.php */
/* Location: ./application/core/Connector.php */