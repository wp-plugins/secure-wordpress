<?php
/**
 * @package Secure WordPress
 * @author Frank B&uuml;ltge
 * @version 0.3.1
 */
 
/*
Plugin Name: Secure WordPress
Plugin URI: http://bueltge.de/wordpress-login-sicherheit-plugin/652/
Description: Little basics for secure your WordPress-installation: Remove Error-Information on Login-Page; add index.html to plugin-directory; remove the wp-version, without in admin-area.
Author: Frank B&uuml;ltge
Version: 0.3.1
License: GPL
Author URI: http://bueltge.de/
Last Change: 19.11.2008 20:02:48
*/


// Pre-2.6 compatibility
if ( !defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( !defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( !defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );


/**
 * Images/ Icons in base64-encoding
 * @use function wpag_get_resource_url() for display
 */
if ( isset($_GET['resource']) && !empty($_GET['resource']) ) {
	# base64 encoding performed by base64img.php from http://php.holtsmark.no
	$resources = array(
		'secure_wp.gif' =>
		'R0lGODlhCwALAKIEAPb29tTU1P///5SUlP///wAAAAAAAAAAAC'.
		'H5BAEAAAQALAAAAAALAAsAAAMhSLrT+0MAMB6JIujKgN6Qp1HW'.
		'MHKK06BXwDKulcby9T4JADs='.
		'',
		'wp.png' =>
		'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAFfKj/FAAAAB3RJTUUH1wYQEiwG0'.
		'0adjQAAAAlwSFlzAAALEgAACxIB0t1+/AAAAARnQU1BAACxjwv8YQUAAABOUExURZ'.
		'wMDN7n93ut1kKExjFjnHul1tbn75S93jFrnP///1qUxnOl1sbe71KMxjFrpWOUzjl'.
		'7tYy13q3G5+fv95y93muczu/39zl7vff3//f//9Se9dEAAAABdFJOUwBA5thmAAAA'.
		's0lEQVR42iWPUZLDIAxDRZFNTMCllJD0/hddktWPRp6x5QcQmyIA1qG1GuBUIArwj'.
		'SRITkiylXNxHjtweqfRFHJ86MIBrBuW0nIIo96+H/SSAb5Zm14KnZTm7cQVc1XSMT'.
		'jr7IdAVPm+G5GS6YZHaUv6M132RBF1PopTXiuPYplcmxzWk2C72CfZTNaU09GCM3T'.
		'Ww9porieUwZt9yP6tHm5K5L2Uun6xsuf/WoTXwo7yQPwBXo8H/8TEoKYAAAAASUVO'.
		'RK5CYII='.
		'');
	
	if ( array_key_exists($_GET['resource'], $resources) ) {

		$content = base64_decode($resources[ $_GET['resource'] ]);

		$lastMod = filemtime(__FILE__);
		$client = ( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false );
		// Checking if the client is validating his cache and if it is current.
		if ( isset($client) && (strtotime($client) == $lastMod) ) {
			// Client's cache IS current, so we just respond '304 Not Modified'.
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastMod).' GMT', true, 304);
			exit;
		} else {
			// Image not cached or cache outdated, we respond '200 OK' and output the image.
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastMod).' GMT', true, 200);
			header('Content-Length: '.strlen($content));
			header('Content-Type: image/' . substr(strrchr($_GET['resource'], '.'), 1) );
			echo $content;
			exit;
		}
	}
}


if ( !class_exists('SecureWP') ) {
	class SecureWP {
		
		// constructor
		function SecureWP() {
			global $wp_version;

			// set default options
			$this->options_array = array('secure_wp_error' => '1',
																	 'secure_wp_version' => '1',
																	 'secure_wp_index' => '1'
																	);
			
			// add class WPlize for options in WP
			$GLOBALS['WPlize'] = new WPlize(
																		 'secure-wp',
																		 $this->options_array
																		 );
			
			if ( is_admin() ) {
				
				if ( function_exists('register_uninstall_hook') )
					register_uninstall_hook(__FILE__, array(&$this,'deactivate') );
				if ( function_exists('register_deactivation_hook') )
					register_deactivation_hook(__FILE__, array(&$this,'deactivate') );
					
				add_action( 'admin_menu', array(&$this,'admin_menu') );
				add_action( 'in_admin_footer', array(&$this, 'admin_footer') );
				
				if ( version_compare( $wp_version, '2.6.999', '>' ) && file_exists(ABSPATH . '/wp-admin/admin-ajax.php') && (basename($_SERVER['QUERY_STRING']) == 'page=secure-wordpress.php') ) {
					wp_enqueue_script( 'secure_wp_plugin_win_page',  plugins_url( $path = 'secure-wordpress/js/page.php' ), array('jquery') );
				} elseif ( version_compare( $wp_version, '2.6.999', '<' ) && file_exists(ABSPATH . '/wp-admin/admin-ajax.php') && (basename($_SERVER['QUERY_STRING']) == 'page=secure-wordpress.php') ) {
					wp_enqueue_script( 'secure_wp_plugin_win_page',  plugins_url( $path = 'secure-wordpress/js/page_s27.php' ), array('jquery') );
				}
				add_action( 'wp_ajax_set_toggle_status', array($this, 'set_toggle_status') );
			}
			
			/**
			 * remove Error-information
			 */
			if ( function_exists('add_filter') && !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_error') == '1') ) {
				add_filter( 'login_errors', create_function( '$a', "return null;" ) );
			}
			
			if ( function_exists('add_action') && !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_version') == '1') ) {
				add_action( 'init', array(&$this,'replace_wp_version'), 1 );
			}
			
			if ( $GLOBALS['WPlize']->get_option('secure_wp_index') == '1' )
				$this->add_indexhtml( WP_PLUGIN_DIR, true );
			
			/**
			 * Retrieve the url to the plugins directory.
			 *
			 * @package WordPress
			 * @since 2.6.0
			 *
			 * @param string $path Optional. Path relative to the plugins url.
			 * @return string Plugins url link with optional path appended.
			 */
			if ( !function_exists('plugins_url') ) {
				function plugins_url($path = '') {
					if ( function_exists( 'is_ssl' ) ) {
						$scheme = ( is_ssl() ? 'https' : 'http' );
					} else {
						$scheme = ( 'http' );
					}
					$url = WP_PLUGIN_URL;
					if ( 0 === strpos($url, 'http') ) {
						if ( is_ssl() )
							$url = str_replace( 'http://', "{$scheme}://", $url );
					}
				
					if ( !empty($path) && is_string($path) && strpos($path, '..') === false )
						$url .= '/' . ltrim($path, '/');
				
					return $url;
				}
			}
			
		}
		
		
		/**
		 * active for multilanguage
		 *
		 * @package Secure WordPress
		 */
		function textdomain() {
		
			if ( function_exists('load_plugin_textdomain') ) {
				if ( !defined('WP_PLUGIN_DIR') ) {
					load_plugin_textdomain('secure_wp', str_replace( ABSPATH, '', dirname(__FILE__) ) . '/languages');
				} else {
					load_plugin_textdomain('secure_wp', false, dirname( plugin_basename(__FILE__) ) . '/languages');
				}
			}
		}
		
		
		function deactivate() {
			
			$GLOBALS['WPlize']->delete_option();
		}
		
		
		/**
		 * Add option for tabboxes via ajax
		 *
		 * @package Secure WordPress
		 */
		function set_toggle_status() {
			if ( current_user_can('manage_options') && $_POST['set_toggle_id'] ) {
				
				$id     = $_POST['set_toggle_id'];
				$status = $_POST['set_toggle_status'];
					
				$GLOBALS['WPlize']->update_option($id, $status);
			}
		}
		
		
		/**
		 * @version WP 2.7
		 * Add action link(s) to plugins page
		 *
		 * @package Secure WordPress
		 *
		 * @param $links
		 * @return $links
		 */
		function filter_plugin_actions_new($links) {
		
			$settings_link = '<a href="options-general.php?page=secure-wordpress.php">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link );
			
			return $links;
		}
		
		
		/**
		 * Display Images/ Icons in base64-encoding
		 *
		 * @package Secure WordPress
		 *
		 * @param $resourceID
		 * @return $resourceID
		 */
		function get_resource_url($resourceID) {
		
			return trailingslashit( get_bloginfo('url') ) . '?resource=' . $resourceID;
		}
		
		
		/**
		 * content of help
		 *
		 * @package Secure WordPress
		 */
		function contextual_help() {
			
			$content = __('<a href="http://wordpress.org/extend/plugins/secure-wordpress/">Documentation</a>', 'secure_wp');
			return $content;
		}
		
		
		/**
		 * settings in plugin-admin-page
		 *
		 * @package Secure WordPress
		 */
		function admin_menu() {
			global $wp_version;
			
			if ( function_exists('add_management_page') && current_user_can('manage_options') ) {
			
				$menutitle = '';
				if ( version_compare( $wp_version, '2.6.999', '>' ) ) {
					$menutitle = '<img src="' . $this->get_resource_url('secure_wp.gif') . '" alt="" />' . ' ';
				}
				$menutitle .= __('Secure WP', 'secure_wp');
				
				if ( version_compare( $wp_version, '2.6.999', '>' ) && function_exists('add_contextual_help') ) {
					$hook = add_submenu_page( 'options-general.php', __('Secure WordPress', 'secure_wp'), $menutitle, 9, basename(__FILE__), array(&$this, 'display_page') );
					add_contextual_help( $hook, __('<a href="http://wordpress.org/extend/plugins/secure-wordpress/">Documentation</a>', 'secure_wp') );
					//add_filter( 'contextual_help', array(&$this, 'contextual_help') );
				} else {
					add_submenu_page( 'options-general.php', __('Secure WP', 'secure_wp'), $menutitle, 9, basename(__FILE__), array(&$this, 'display_page') );
				}
				
				$plugin = plugin_basename(__FILE__); 
				add_filter( 'plugin_action_links_' . $plugin, array(&$this, 'filter_plugin_actions_new') );
			}
		}
		
		
		/**
		 * credit in wp-footer
		 *
		 * @package Secure WordPress
		 */
		function admin_footer() {
			
			if( basename($_SERVER['QUERY_STRING']) == 'page=secure-wordpress.php') {
				$plugin_data = get_plugin_data( __FILE__ );
				printf('%1$s plugin | ' . __('Version') . ' <a href="http://bueltge.de/wordpress-login-sicherheit-plugin/652/#historie" title="' . __('History', 'secure_wp') . '">%2$s</a> | ' . __('Author') . ' %3$s<br />', $plugin_data['Title'], $plugin_data['Version'], $plugin_data['Author']);
			}
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
		
		
		/**
		 * Replace the WP-version with a random string &lt; WP 2.4
		 * and eliminate WP-version &gt; WP 2.4
		 * http://bueltge.de/wordpress-version-verschleiern-plugin/602/
		 *
		 * @package Secure WordPress
		 */
		function replace_wp_version() {
		
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
		
		
		/**
		 * display options page in backende
		 *
		 * @package Secure WordPress
		 */
		function display_page() {
			global $wp_version;
			
			if ( isset($_POST['action']) && 'update' == $_POST['action'] ) {
				check_admin_referer('secure_wp_settings_form');
				if ( current_user_can('manage_options') ) {
				
					// init value
					$update_options = array();
					
					// set value
					foreach ($this->options_array as $key => $value) {
						$update_options[$key] = stripslashes_deep(trim($_POST[$key]));
					}
					
					// save value
					if ($update_options) {
						$GLOBALS['WPlize']->update_option($update_options);
					}
					
					?>
					<div id="message" class="updated fade"><p><?php _e('Options update.', 'secure_wp'); ?></p></div>
					<?php
				} else {
					?>
					<div id="message" class="error"><p><?php _e('Options not update - you don&lsquo;t have the privilidges to do this!', 'secure_wp'); ?></p></div>
					<?php
				}
			}
			
			if ( isset($_POST['action']) && 'deinstall' == $_POST['action'] ) {
				check_admin_referer('secure_wp_deinstall_form');
				if ( current_user_can('manage_options') && isset($_POST['deinstall_yes']) ) {
					$this->deactivate();
					?>
					<div id="message" class="updated fade"><p><?php _e('All entries in the database was cleared.', 'secure_wp'); ?></p></div>
					<?php
				} else {
					?>
					<div id="message" class="error"><p><?php _e('Entries was not delleted - check the checkbox or you don&lsquo;t have the privilidges to do this!', 'secure_wp'); ?></p></div>
					<?php
				}
			}
			
			$secure_wp_error   = $GLOBALS['WPlize']->get_option('secure_wp_error');
			$secure_wp_version = $GLOBALS['WPlize']->get_option('secure_wp_version');
			$secure_wp_index   = $GLOBALS['WPlize']->get_option('secure_wp_index');
			
			$secure_wp_win_settings = $GLOBALS['WPlize']->get_option('secure_wp_win_settings');
			$secure_wp_win_about    = $GLOBALS['WPlize']->get_option('secure_wp_win_about');
			$secure_wp_win_opt      = $GLOBALS['WPlize']->get_option('secure_wp_win_opt');
			
		?>
		<div class="wrap">
			<h2><?php _e('Secure WordPress', 'secure_wp'); ?></h2>
			<br class="clear" />
			
			<div id="poststuff" class="ui-sortable">
				<div id="secure_wp_win_settings" class="postbox <?php echo $secure_wp_win_settings ?>" >
					<h3><?php _e('Configuration', 'secure_wp'); ?></h3>
					<div class="inside">
			
						<form name="secure_wp_config-update" method="post" action="">
							<?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('secure_wp_settings_form'); ?>
							
							<table class="form-table">
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_error"><?php _e('Error-Messages', 'secure_wp'); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_error" id="secure_wp_error" value="1" <?php if ( $secure_wp_error == '1') { echo "checked='checked'"; } ?> />
										<?php _e('deactivates tooltip and error message at login of WordPress', 'secure_wp'); ?>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_version"><?php _e('WP Version', 'secure_wp'); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_version" id="secure_wp_version" value="1" <?php if ( $secure_wp_version == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Removes version of WordPress in all areas, including feed; not in admin', 'secure_wp'); ?>
									</td>
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_index"><?php _e('index.html', 'secure_wp'); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_index" id="secure_wp_index" value="1" <?php if ( $secure_wp_index == '1') { echo "checked='checked'"; } ?> />
										<?php _e('creates an index.html file in /plugins/ to keep it from showing your directory listing', 'secure_wp'); ?>
									</td>
								
							</table>
							
							<p class="submit">
								<input type="hidden" name="action" value="update" />
								<input type="submit" name="Submit" value="<?php _e('Save Changes', 'secure_wp'); ?> &raquo;" />
							</p>
						</form>

					</div>
				</div>
			</div>
			
			<div id="poststuff" class="ui-sortable">
				<div id="secure_wp_win_opt" class="postbox <?php echo $secure_wp_win_opt ?>" >
					<h3 id="uninstall"><?php _e('Clear Options', 'secure_wp') ?></h3>
					<div class="inside">
						
						<p><?php _e('Click this button to delete settings of this plugin. Deactivating WordPress Cache Management plugin remove any data that may have been created, such as the cache options.', 'secure_wp'); ?></p>
						<form name="deinstall_options" method="post" action="">
							<?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('secure_wp_deinstall_form'); ?>
							<p id="submitbutton">
								<input type="hidden" name="action" value="deinstall" />
								<input type="submit" value="<?php _e('Delete Options', 'secure_wp'); ?> &raquo;" class="button-secondary" /> 
								<input type="checkbox" name="deinstall_yes" />
							</p>
						</form>
	
					</div>
				</div>
			</div>
			
			<div id="poststuff" class="ui-sortable">
				<div id="secure_wp_win_about" class="postbox <?php echo $secure_wp_win_about ?>" >
					<h3><?php _e('About the plugin', 'secure_wp') ?></h3>
					<div class="inside">
					
						<p><?php _e('Further information: Visit the <a href="http://bueltge.de/wordpress-login-sicherheit-plugin/652/">plugin homepage</a> for further information or to grab the latest version of this plugin.', 'secure_wp'); ?><br />&copy; Copyright 2007 - <?php echo date("Y"); ?> <a href="http://bueltge.de">Frank B&uuml;ltge</a> | <?php _e('You want to thank me? Visit my <a href="http://bueltge.de/wunschliste/">wishlist</a>.', 'secure_wp'); ?></p>
						
					</div>
				</div>
			</div>
			
		</div>
		<?php
		}
		
	}
}


if ( !class_exists('WPlize') ) {
	require_once('inc/WPlize.php');
}


if ( class_exists('SecureWP') && class_exists('WPlize') && function_exists('is_admin') ) {
	$swp_injector = new SecureWP();
}


if ( isset($swp_injector) && function_exists( 'add_action' ) ) {
	add_action( 'SecureWP',  array(&$swp_injector, 'init') );
}
?>