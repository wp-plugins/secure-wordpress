<?php
/*
Plugin Name: Secure WordPress
Plugin URI: http://bueltge.de/wordpress-login-sicherheit-plugin/652/
Description: Little basics for secure your WordPress-installation: Remove Error-Information on Login-Page; add index.html to plugin-directory; remove the wp-version, withour in admin-area.
Author: Frank Bueltge
Version: 0.2
License: GPL
Author URI: http://bueltge.de/
*/

if (!class_exists("SecureWP")) {
	class SecureWP {
		
		function SecureWP() {

			/**
			 * remove Error-information
			 */
			if ( function_exists('add_filter') ) {
				add_filter( 'login_errors', create_function( '$a', "return null;" ) );
			}
			
			
			/**
			 * add index.html to plugin-derectory
			 */
			function add_indexhtml($path, $enable) {
			
				$file = trailingslashit($path) . 'index.html';
			
				if ($enable) {
					if (!file_exists($file)) {
						$fh = @fopen($file, 'w');
						if ($fh) fclose($fh);
					}
				} else {
					if (file_exists($file) && filesize($file) === 0) {
						unlink($file);
					}
				}
			}
			
			if ( defined('WP_PLUGIN_DIR') ) {
				add_indexhtml( WP_PLUGIN_DIR, true);
			} else {
				add_indexhtml( ABSPATH . PLUGINDIR, true);
			}

			if (!function_exists('fb_replace_wp_version')) {
				/**
				 * Replace the WP-version with a random string &lt; WP 2.4
				 * and eliminate WP-version &gt; WP 2.4
				 * http://bueltge.de/wordpress-version-verschleiern-plugin/602/
				 */
				function fb_replace_wp_version() {
				
					if ( !is_admin() ) {
						global $wp_version;
						
						// random value
						$v = intval( rand(0, 9999) );
						
						if ( function_exists('the_generator') ) {
							// eliminate version for wordpress >= 2.4
							add_filter( 'the_generator', create_function('$a', "return null;") );
							// add_filter( 'wp_generator_type', create_function( '$a', "return null;" ) );
							
							// for $wp_version and db_version
							$wp_version = $v;
						} else {
							// for wordpress < 2.4
							add_filter( "bloginfo_rss('version')", create_function('$a', "return $v;") );
							
							// for rdf and rss v0.92
							$wp_version = $v;
						}
					}
				
				}
				
				if ( function_exists('add_action') ) {
					add_action('init', fb_replace_wp_version, 1);
				}
			}
		}

	}
}

if (class_exists("SecureWP")) {
	$swp_injector = new SecureWP();
}

if (isset($swp_injector)) {
	add_action('SecureWP',  array(&$swp_injector, 'init'));
}
?>