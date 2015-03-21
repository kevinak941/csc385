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
 * @description Provides ability to run concurrent scripting
 */
class Fork {
    private $_handles = array();
    private $_multi   = array();
 
    function __construct() {
        $this->_multi = curl_multi_init();
    }
 
    function add($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_multi_add_handle($this->_multi, $ch);
        $this->_handles[] = $ch;
        return $this;
    }
 
    function run() {
        $running = null;
        curl_multi_exec($this->_multi, $running);
    }
}

/* End of file Fork.php */
/* Location: ./application/libraries/Fork.php */