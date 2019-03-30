<?php
/*

  Plugin Name: PassGrinder
  Description: 
  Author:      Jeremy Caris
  Author URI:  https://passgrinder.com/
  Version:     0.3
  Category:    utility

*/

if (!defined('ABSPATH')) exit();


require 'checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/jeremycaris/passgrinder/',
	__FILE__,
	'passgrinder'
);


// Include main class
require("inc/class-passgrinder.php");


// Create pass page with shortcode
//if( get_page_by_title('pass') == false ) {
//   $post_details = array(
//      'post_title'    => 'Pass',
//      'post_content'  => '[password_grinder]',
//      'post_status'   => 'publish',
//      'post_author'   => 11,
//      'post_type' => 'page'
//   );
//   wp_insert_post( $post_details );
//}


// Wordpress settings page with option to make url the default salt

// Update WP registration form

// Update WP pass reset form

// set up name space and translation

