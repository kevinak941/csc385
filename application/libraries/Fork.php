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
 
/**
 * ----- Additional Help -------
 * Fork usage:
 *  - Create a fork
 *  - For background process (no return) use $fork->add('example.com/blah')->run();
 *  - For concurrent process (wait for return) use $fork->add('example.com/blah')->runConcurrent('xml')
 * Not required to chain
 *  - $fork->add('blah.com/rrehe');
 *  - $fork->add('blah.com/rthrth');
 *  - $fork->run();
 *    This will run all added urls in the background
 */
class Fork {
    private $_handles = array();
    private $_urls    = array();
    private $_posts   = array();
    private $_master;
    
    /**
     * Class constructor
     */
    function __construct() {
        // Established curl multi
        // However is unused for concurrent processes
        $this->_master = curl_multi_init();
    }
    
    /**
     * Resets the fork to be used again 
     * Becomes a clean fork
     */
    function reset() {
        $this->_handles = array();
        $this->_urls    = array();
        $this->_posts   = array();
        $this->_master  = curl_multi_init();
        return $this;
    }
 
    /**
     * Adds a given url to the list of requests to send
     * @param $url The url of the requests
     * @param $post_fields (Optional) Array of post fields to send with request
     * SUPPORTS METHOD CHAINING
     */
    function add($url, $post_fields = false) {
        // Optionally Compile any post fields
        $post_string = "";
        if($post_fields) {
            if(is_array($post_fields)) {
                foreach($post_fields as $key => $field) {
                    $post_string .= $key.'='.urlencode($field).'&';
                }
                rtrim($post_string, '&');
            }
        }
        // Create cURL resource for each added url
        $ch = curl_init();
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url.($post_string!='' ? '?'.$post_string : ''));
        curl_setopt($ch, CURLOPT_HEADER, "Content-Type:application/xml");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Add resource to multi cURL
        curl_multi_add_handle($this->_master, $ch);
        // Add resource to total handles
        $this->_handles[] = $ch;
        $this->_urls[] = $url;
        $this->_posts[] = $post_string;
        // Return fork for chaining
        return $this;
    }
    
    /**
     * Run urls as a background process
     * Creates packets and send them over socket 80
     * Packet contents are set via the add method as $this->_posts
     */
    function run() {
        // Configuration changes
        // Not required, but good practice
        ignore_user_abort(true);
        set_time_limit(0);
        // Loop through the urls
        foreach($this->_urls as $key => &$url) {
            // Break down the url
            $parts=parse_url($url);
            // Open the socket
            $fp = fsockopen($parts['host'],
                isset($parts['port'])?$parts['port']:80,
                $errno, $errstr, 30);
            // Write the packet
            $out = "POST ".$parts['path']." HTTP/1.1\r\n";
            $out.= "Host: ".$parts['host']."\r\n";
            $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out.= "Content-Length: ".strlen($this->_posts[$key])."\r\n";
            $out.= "Connection: Close\r\n\r\n";
            // Add any content if applicable
            if (isset($this->_posts[$key])) $out.= $this->_posts[$key];
            // Write packet to socket
            fwrite($fp, $out);
            fclose($fp);
            // Remove sent url (don't need it anymore)
            unset($this->_urls[$key]);
        }
    }

    /**
     * Concurrently run all urls that have been added
     * This can return as it must wait until calls are complete to continue processing
     * @param $result_type The type of the data that will be retrieved
     * @param $callback A function to send the data back to
     *
     * @return The data received via the given urls
     */
    function runConcurrent($result_type = false, $callback = false) {
        $running = null;
        $t = array();
        // Loop while running is true
        do {
            // Execute each curl request
            while(($exec = curl_multi_exec($this->_master, $running)) == CURLM_CALL_MULTI_PERFORM);
            if($exec != CURLM_OK)
                break;
            // Read the information from the request
            while($done = curl_multi_info_read($this->_master)) {
                $info = curl_getinfo($done['handle']);
                if($info['http_code'] == 200) {
                    $output = curl_multi_getcontent($done['handle']);
                    // This project only uses xml response.. should be the default
                    if($result_type == 'xml') {
                        try {
                            // Make sure its valid XML
                            $tempXML = new SimpleXMLElement($output);
                            // Store info for return
                            $t[] = $tempXML;
                            // Fire a callback with returned data
                            if($callback) $callback($tempXML);
                        } catch(Exception $e) {};
                    } else {
                        // Fire off callback with result
                        
                        if($callback) $callback($output);
                    }
                    // Remove the handle for the url (don't need it anymore)
                    curl_multi_remove_handle($this->_master, $done['handle']);
                } else {
                    // Request Failed, so ignore
                    // Shouldn't fail, but if it does, we'll just leave it alone
                }
            }
        } while($running);
        
        // ALWAYS CLOSE
        curl_multi_close($this->_master);
        // Return all collected data
        return $t;
    }
}

/* End of file Fork.php */
/* Location: ./application/libraries/Fork.php */
