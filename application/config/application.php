<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Application Configurations
 * Modify these values to change how the EPTA searching works
 */
 
/**
 * Number of entries to receive per api call
 * Min: 0 
 * Max: 100
 * Recommended: 100
 */
$config['ebay_entriesPerPage'] = 100;

/** 
 * Number of pages to get per search
 * Total Results = (ebay_entriesPerPage * ebay_pagesPerSearch)
 * Min: 1
 * Max: 1000 (Would kill performance)
 * Recommended: 1-5
 * Default: 2
 */
$config['ebay_pagesPerSearch'] = 3;


/**
 * Allow search to store simple information about the search and searcher
 * Enabled: TRUE
 * Disabled: FALSE
 * Recommended: TRUE
 */
$config['enable_recentSearch'] = TRUE;

/**
 * Allow EPTA to pull local data it has already saved about search items
 * Enabled: TRUE
 * Disabled: FALSE
 * Recommended: TRUE
 */ 
$config['enable_localResults'] = false;

/**
 * Allow EPTA to parse and store tags from items
 * Recommended: TRUE
 */
$config['enable_tagCollection'] = TRUE;

/**
 * Using title tag matching algorithm to filter out unlikely results
 * Recommended: TRUE
 */
$config['enable_titleMatching'] = TRUE;

/**
 * Dictates the minimum match rating allowed to pass
 * The total rating is: (Total Non Unique Tags * titleMatching_tolerance)
 * Any value greater than total rating passes
 * Min: 0.01
 * Max: 1.0
 * Recommended: 0.5 
 */
$config['titleMatching_tolerance'] = 0.3;
