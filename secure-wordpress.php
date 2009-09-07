<?php
/**
 * @package Secure WordPress
 * @author Frank B&uuml;ltge
 * @version 0.3.9
 */
 
/*
Plugin Name: Secure WordPress
Plugin URI: http://bueltge.de/wordpress-login-sicherheit-plugin/652/
Description: Little basics for secure your WordPress-installation.
Author: Frank B&uuml;ltge
Version: 0.3.9
Author URI: http://bueltge.de/
Last Change: 07.09.2009 10:11:55
License: GPL
*/

global $wp_version;
if ( !function_exists ('add_action') || version_compare($wp_version, "2.6alpha", "<") ) {
	if (function_exists ('add_action'))
		$exit_msg = 'The plugin <em><a href="http://bueltge.de/wordpress-login-sicherheit-plugin/652/">Secure WordPress</a></em> requires WordPress 2.6 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update WordPress</a> or delete the plugin.';
	else
		$exit_msg = '';
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit($exit_msg);
}

if ( function_exists ('add_action') ) {
	// Pre-2.6 compatibility
	if ( !defined( 'WP_CONTENT_URL' ) )
		define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( !defined( 'WP_PLUGIN_URL' ) )
		define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( !defined( 'WP_PLUGIN_DIR' ) )
		define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
	
	// plugin definitions
	define( 'FB_SWP_BASENAME', plugin_basename(__FILE__) );
	define( 'FB_SWP_BASEFOLDER', plugin_basename( dirname( __FILE__ ) ) );
	define( 'FB_SWP_FILENAME', str_replace( FB_SWP_BASEFOLDER.'/', '', plugin_basename(__FILE__) ) );
	define( 'FB_SWP_TEXTDOMAIN', 'secure_wp' );
}

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
			
			$this->activate();
			
			add_action( 'init', array(&$this, 'textdomain') );
			
			/**
			 * remove WP version
			 */
			if ( $GLOBALS['WPlize']->get_option('secure_wp_version') == '1' )
				add_action( 'init', array(&$this, 'replace_wp_version'), 1 );
			
			/**
			 * remove core update for non admins
			 * @link: rights: http://codex.wordpress.org/Roles_and_Capabilities
			 */
			if ( is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_rcu') == '1') ) {
				add_action( 'init', array(&$this, 'remove_core_update'), 1 );
			}
			
			/**
			 * remove plugin update for non admins
			 * @link: rights: http://codex.wordpress.org/Roles_and_Capabilities
			 */
			if ( is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_rpu') == '1') )
				add_action( 'init', array(&$this, 'remove_plugin_update'), 1 );
			
			/**
			 * remove theme update for non admins
			 * @link: rights: http://codex.wordpress.org/Roles_and_Capabilities
			 */
			if ( is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_rtu') == '1') && ( version_compare($wp_version, "2.8alpha", ">") ) )
				add_action( 'init', array(&$this, 'remove_theme_update'), 1 );
			
			add_action( 'init', array(&$this, 'on_init'), 1 );
			
		}
		
		
		/**
		 * active for multilanguage
		 *
		 * @package Secure WordPress
		 */
		function textdomain() {
			
			if ( function_exists('load_plugin_textdomain') ) {
				if ( !defined('WP_PLUGIN_DIR') ) {
					load_plugin_textdomain(FB_SWP_TEXTDOMAIN, str_replace( ABSPATH, '', dirname(__FILE__) ) . '/languages');
				} else {
					load_plugin_textdomain(FB_SWP_TEXTDOMAIN, false, dirname( plugin_basename(__FILE__) ) . '/languages');
				}
			}
		}
		
		
		/**
		 * init fucntions; check rights and options
		 *
		 * @package Secure WordPress
		 */
		function on_init() {
			global $wp_version;
			
			if ( is_admin() ) {
				// update options
				add_action('admin_post_swp_update', array(&$this, 'swp_update') );
				// deinstall options
				add_action('admin_post_swp_uninstall', array(&$this, 'swp_uninstall') );
				
				// init default options on activate
				if ( function_exists('register_activation_hook') )
					register_activation_hook(__FILE__, array($this, 'activate') );
				// deinstall options on deactive
				if ( function_exists('register_uninstall_hook') )
					register_uninstall_hook(__FILE__, array($this, 'deactivate') );
				// deinstall options in deactivate
				if ( function_exists('register_deactivation_hook') )
					register_deactivation_hook(__FILE__, array($this, 'deactivate') );
				
				// add options page
				add_action( 'admin_menu', array(&$this, 'admin_menu') );
				// hint in footer of the options page
				add_action( 'in_admin_footer', array(&$this, 'admin_footer') );
				
				// add javascript for metaboxes
				if ( version_compare( $wp_version, '2.7alpha', '>' ) && file_exists(ABSPATH . '/wp-admin/admin-ajax.php') && (basename($_SERVER['QUERY_STRING']) == 'page=secure-wordpress.php') ) {
					wp_enqueue_script( 'secure_wp_plugin_win_page',  plugins_url( $path = 'secure-wordpress/js/page.php' ), array('jquery') );
				} elseif ( version_compare( $wp_version, '2.7alpha', '<' ) && file_exists(ABSPATH . '/wp-admin/admin-ajax.php') && (basename($_SERVER['QUERY_STRING']) == 'page=secure-wordpress.php') ) {
					wp_enqueue_script( 'secure_wp_plugin_win_page', plugins_url( $path = 'secure-wordpress/js/page_s27.php' ), array('jquery') );
				}
				add_action( 'wp_ajax_set_toggle_status', array($this, 'set_toggle_status') );
			}
			
			
			/**
			 * remove Error-information
			 */
			if ( !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_error') == '1') ) {
				add_action( 'login_head', array(&$this, 'remove_error_div') );
				add_filter( 'login_errors', create_function( '$a', "return null;" ) );
			}
			
			
			/**
			 * add index.html in plugin-folder
			 */
			if ( $GLOBALS['WPlize']->get_option('secure_wp_index') == '1' ) {
				$this->add_index( WP_PLUGIN_DIR, true );
				$this->add_index( WP_CONTENT_URL . '/themes', true );
			}
			
			
			/**
			 * remove rdf
			 */
			if ( function_exists('rsd_link') && !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_rsd') == '1') )
				remove_action('wp_head', 'rsd_link');
			
			
			/**
			 * remove wlf
			 */
			if ( function_exists('wlwmanifest_link') && !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_wlw') == '1') )
				remove_action('wp_head', 'wlwmanifest_link');
			
			/**
			 * add wp-scanner
			 * @link http://blogsecurity.net/wordpress/tools/wp-scanner
			 */
			if ( !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_wps') == '1') )
				add_action( 'wp_head', array(&$this, 'wp_scanner') );
			
		}
		
		
		/**
		 * install options
		 *
		 * @package Secure WordPress
		 */
		function activate() {
			// set default options
			$this->options_array = array('secure_wp_error' => '',
																	 'secure_wp_version' => '1',
																	 'secure_wp_index' => '1',
																	 'secure_wp_rsd' => '',
																	 'secure_wp_wlw' => '',
																	 'secure_wp_rcu' => '1',
																	 'secure_wp_rpu' => '1',
																	 'secure_wp_rtu' => '',
																	 'secure_wp_wps' => '1'
																	);
			
			// add class WPlize for options in WP
			$GLOBALS['WPlize'] = new WPlize(
																		 'secure-wp',
																		 $this->options_array
																		 );
		}
		
		
		/**
		 * unpdate options
		 *
		 * @package Secure WordPress
		 */
		function update() {
			// init value
			$update_options = array();
			
			// set value
			foreach ($this->options_array as $key => $value) {
				$update_options[$key] = stripslashes_deep( trim($_POST[$key]) );
			}
			
			// save value
			if ($update_options) {
				$GLOBALS['WPlize']->update_option($update_options);
			}
		}
		
		
		/**
		 * uninstall options
		 *
		 * @package Secure WordPress
		 */
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
		 * @version WP 2.8
		 * Add action link(s) to plugins page
		 *
		 * @package Secure WordPress
		 *
		 * @param $links, $file
		 * @return $links
		 */
		function filter_plugin_meta($links, $file) {
			
			/* create link */
			if ( $file == FB_SWP_BASENAME ) {
				array_unshift(
					$links,
					sprintf( '<a href="options-general.php?page=%s">%s</a>', FB_SWP_FILENAME, __('Settings') )
				);
			}
			
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
			
			$content = __('<a href="http://wordpress.org/extend/plugins/secure-wordpress/">Documentation</a>', FB_SWP_TEXTDOMAIN);
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
			
				// update, uninstall message
				if ( strpos($_SERVER['REQUEST_URI'], 'secure-wordpress.php') && $_GET['update'] == 'true' ) {
					$return_message = __('Options update.', FB_SWP_TEXTDOMAIN);
				} elseif ( $_GET['uninstall'] == 'true' ) {
					$return_message = __('All entries in the database was cleared. Now deactivate this plugin.', FB_SWP_TEXTDOMAIN);
				} else {
					$return_message = '';
				}
				$message = '<div class="updated fade"><p>' . $return_message . '</p></div>';
			
				$menutitle = '';
				if ( version_compare( $wp_version, '2.7alpha', '>' ) ) {
					
					if ( $return_message !== '' )
						add_action('admin_notices', create_function( '', "echo '$message';" ) );
					
					$menutitle = '<img src="' . $this->get_resource_url('secure_wp.gif') . '" alt="" />' . ' ';
				}
				$menutitle .= __('Secure WP', FB_SWP_TEXTDOMAIN);
				
				if ( version_compare( $wp_version, '2.7alpha', '>' ) && function_exists('add_contextual_help') ) {
					$hook = add_submenu_page( 'options-general.php', __('Secure WordPress', FB_SWP_TEXTDOMAIN), $menutitle, 9, basename(__FILE__), array(&$this, 'display_page') );
					add_contextual_help( $hook, __('<a href="http://wordpress.org/extend/plugins/secure-wordpress/">Documentation</a>', FB_SWP_TEXTDOMAIN) );
					//add_filter( 'contextual_help', array(&$this, 'contextual_help') );
				} else {
					add_submenu_page( 'options-general.php', __('Secure WP', FB_SWP_TEXTDOMAIN), $menutitle, 9, basename(__FILE__), array(&$this, 'display_page') );
				}
				
				$plugin = plugin_basename(__FILE__);
				add_filter( 'plugin_action_links_' . $plugin, array(&$this, 'filter_plugin_meta'), 10, 2 );
				if ( version_compare( $wp_version, '2.8alpha', '>' ) )
					add_filter( 'plugin_row_meta', array(&$this, 'filter_plugin_meta'), 10, 2 );
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
				printf('%1$s plugin | ' . __('Version') . ' <a href="http://bueltge.de/wordpress-login-sicherheit-plugin/652/#historie" title="' . __('History', FB_SWP_TEXTDOMAIN) . '">%2$s</a> | ' . __('Author') . ' %3$s<br />', $plugin_data['Title'], $plugin_data['Version'], $plugin_data['Author']);
			}
		}
		
		
		/**
		 * add index.php to plugin-derectory
		 */
		function add_index($path, $enable) {
		
			$file = trailingslashit($path) . 'index.php';
		
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
		 * remove core-Update-Information
		 *
		 * @package Secure WordPress
		 */
		function remove_core_update() {
			if ( !current_user_can('edit_plugins') ) {
				add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ) );
				add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
			}
		}
		
		
		/**
		 * remove plugin-Update-Information
		 *
		 * @package Secure WordPress
		 */
		function remove_plugin_update() {
			if ( !current_user_can('edit_plugins') ) {
				add_action( 'admin_menu', create_function( '$a', "remove_action( 'load-plugins.php', 'wp_update_plugins' );" ) );
				add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', 'wp_update_plugins' );" ), 2 );
				add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_update_plugins' );" ), 2 );
				add_filter( 'pre_option_update_plugins', create_function( '$a', "return null;" ) );
			}
		}
		
		
		/**
		 * remove theme-Update-Information
		 *
		 * @package Secure WordPress
		 */
		function remove_theme_update() {
			if ( !current_user_can('edit_themes') ) {
				remove_action( 'load-themes.php', 'wp_update_themes' );
				remove_action( 'load-update.php', 'wp_update_themes' );
				remove_action( 'admin_init', '_maybe_update_themes' );
				remove_action( 'wp_update_themes', 'wp_update_themes' );
				add_filter( 'pre_transient_update_themes', create_function( '$a', "return null;" ) );
			}
		}
		
		
		/**
		 * remove error-div
		 *
		 * @package Secure WordPress
		 */
		function remove_error_div() {
			echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( $path = 'secure-wordpress/css/remove_login.css' ) . '" />';
		}
		
		
		/**
		 * add string in blog for WP scanner
		 *
		 * @package Secure WordPress
		 */
		function wp_scanner() {
			echo '<!-- wpscanner -->';
		}
		
		/**
		 * update options
		 *
		 * @package Secure WordPress
		 */
		function swp_update() {
		
			if ( !current_user_can('manage_options') )
				wp_die( __('Options not update - you don&lsquo;t have the privilidges to do this!', FB_SWP_TEXTDOMAIN) );
		
			//cross check the given referer
			check_admin_referer('secure_wp_settings_form');
			
			$this->update();
			
			$referer = str_replace('&update=true&update=true', '', $_POST['_wp_http_referer'] );
			wp_redirect($referer . '&update=true' );
		}
		
		
		/**
		 * uninstall options
		 *
		 * @package Secure WordPress
		 */
		function swp_uninstall() {
		
			if ( !current_user_can('manage_options') )
				wp_die( __('Entries was not delleted - you don&lsquo;t have the privilidges to do this!', FB_SWP_TEXTDOMAIN) );
		
			//cross check the given referer
			check_admin_referer('secure_wp_uninstall_form');
		
			if ( isset($_POST['deinstall_yes']) ) {
				$this->deactivate();
			} else {
				wp_die( __('Entries was not delleted - check the checkbox!', FB_SWP_TEXTDOMAIN) ); 
			}
			
			wp_redirect( 'plugins.php' );
		}
		
		
		/**
		 * display options page in backende
		 *
		 * @package Secure WordPress
		 */
		function display_page() {
			global $wp_version;
			
			if ( isset($_POST['action']) && 'deinstall' == $_POST['action'] ) {
				check_admin_referer('secure_wp_deinstall_form');
				if ( current_user_can('manage_options') && isset($_POST['deinstall_yes']) ) {
					$this->deactivate();
					?>
					<div id="message" class="updated fade"><p><?php _e('All entries in the database was cleared.', FB_SWP_TEXTDOMAIN); ?></p></div>
					<?php
				} else {
					?>
					<div id="message" class="error"><p><?php _e('Entries was not delleted - check the checkbox or you don&lsquo;t have the privilidges to do this!', FB_SWP_TEXTDOMAIN); ?></p></div>
					<?php
				}
			}
			
			$secure_wp_error   = $GLOBALS['WPlize']->get_option('secure_wp_error');
			$secure_wp_version = $GLOBALS['WPlize']->get_option('secure_wp_version');
			$secure_wp_index   = $GLOBALS['WPlize']->get_option('secure_wp_index');
			$secure_wp_rsd     = $GLOBALS['WPlize']->get_option('secure_wp_rsd');
			$secure_wp_wlw     = $GLOBALS['WPlize']->get_option('secure_wp_wlw');
			$secure_wp_rcu     = $GLOBALS['WPlize']->get_option('secure_wp_rcu');
			$secure_wp_rpu     = $GLOBALS['WPlize']->get_option('secure_wp_rpu');
			$secure_wp_rtu     = $GLOBALS['WPlize']->get_option('secure_wp_rtu');
			$secure_wp_wps     = $GLOBALS['WPlize']->get_option('secure_wp_wps');
			
			$secure_wp_win_settings = $GLOBALS['WPlize']->get_option('secure_wp_win_settings');
			$secure_wp_win_about    = $GLOBALS['WPlize']->get_option('secure_wp_win_about');
			$secure_wp_win_opt      = $GLOBALS['WPlize']->get_option('secure_wp_win_opt');
		?>
		<div class="wrap">
			<h2><?php _e('Secure WordPress', FB_SWP_TEXTDOMAIN); ?></h2>
			<br class="clear" />
			
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div id="secure_wp_win_settings" class="postbox <?php echo $secure_wp_win_settings ?>" >
					<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br/></div>
					<h3><?php _e('Configuration', FB_SWP_TEXTDOMAIN); ?></h3>
					<div class="inside">
			
						<form name="secure_wp_config-update" method="post" action="admin-post.php">
							<?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('secure_wp_settings_form'); ?>
							
							<table class="form-table">
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_error"><?php _e('Error-Messages', FB_SWP_TEXTDOMAIN); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_error" id="secure_wp_error" value="1" <?php if ( $secure_wp_error == '1') { echo "checked='checked'"; } ?> />
										<?php _e('deactivates tooltip and error message at login of WordPress', FB_SWP_TEXTDOMAIN); ?>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_version"><?php _e('WordPress Version', FB_SWP_TEXTDOMAIN); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_version" id="secure_wp_version" value="1" <?php if ( $secure_wp_version == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Removes version of WordPress in all areas, including feed, not in admin', FB_SWP_TEXTDOMAIN); ?>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_index"><?php _e('index.html', FB_SWP_TEXTDOMAIN); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_index" id="secure_wp_index" value="1" <?php if ( $secure_wp_index == '1') { echo "checked='checked'"; } ?> />
										<?php _e('creates an <code>index.php</code> file in <code>/plugins/</code> and <code>/themes/</code> to keep it from showing your directory listing', FB_SWP_TEXTDOMAIN); ?>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_rsd"><?php _e('Really Simple Discovery', FB_SWP_TEXTDOMAIN); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_rsd" id="secure_wp_rsd" value="1" <?php if ( $secure_wp_rsd == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Remove Really Simple Discovery link in <code>wp_head</code> of the frontend', FB_SWP_TEXTDOMAIN); ?>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_wlw"><?php _e('Windows Live Writer', FB_SWP_TEXTDOMAIN); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_wlw" id="secure_wp_wlw" value="1" <?php if ( $secure_wp_wlw == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Remove Windows Live Writer link in <code>wp_head</code> of the frontend', FB_SWP_TEXTDOMAIN); ?>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_rcu"><?php _e('Core Update', FB_SWP_TEXTDOMAIN); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_rcu" id="secure_wp_rcu" value="1" <?php if ( $secure_wp_rcu == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Remove WordPress Core update for non-admins. Show message of a new WordPress version only to users with the right to update.', FB_SWP_TEXTDOMAIN); ?>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_rpu"><?php _e('Plugin Update', FB_SWP_TEXTDOMAIN); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_rpu" id="secure_wp_rpu" value="1" <?php if ( $secure_wp_rpu == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Remove the plugin update for non-admins. Show message for a new version of a plugin in the install of your blog only to users with the rights to edit plugins.', FB_SWP_TEXTDOMAIN); ?>
									</td>
								</tr>
								
								<?php if ( version_compare($wp_version, "2.8alpha", ">=") ) { ?>
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_rtu"><?php _e('Theme Update', FB_SWP_TEXTDOMAIN); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_rtu" id="secure_wp_rtu" value="1" <?php if ( $secure_wp_rtu == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Remove the theme update for non-admins. Show message for a new version of a theme in the install of your blog only to users with the rights to edit themes.', FB_SWP_TEXTDOMAIN); ?>
									</td>
								</tr>
							<?php } ?>
								
								<tr valign="top">
									<th scope="row">
										<label for="secure_wp_wps"><?php _e('WP Scanner', FB_SWP_TEXTDOMAIN); ?></label>
									</th>
									<td>
										<input type="checkbox" name="secure_wp_wps" id="secure_wp_wps" value="1" <?php if ( $secure_wp_wps == '1') { echo "checked='checked'"; } ?> />
										<?php _e('WordPress scanner is a free online resource that blog administrators can use to provide a measure of their wordpress security level. To run wp-scanner check this option and is add <code>&lt;!-- wpscanner --&gt;</code> to your current WordPress template. After this go to <a href="http://blogsecurity.net/wpscan">http://blogsecurity.net/wpscan</a> and scan your site.', FB_SWP_TEXTDOMAIN); ?>
									</td>
								</tr>
								
							</table>
							
							<p class="submit">
								<input type="hidden" name="action" value="swp_update" />
								<input type="submit" name="Submit" value="<?php _e('Save Changes', FB_SWP_TEXTDOMAIN); ?> &raquo;" />
							</p>
						</form>

					</div>
				</div>
			</div>
			
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div id="secure_wp_win_opt" class="postbox <?php echo $secure_wp_win_opt ?>" >
					<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br/></div>
					<h3 id="uninstall"><?php _e('Clear Options', FB_SWP_TEXTDOMAIN) ?></h3>
					<div class="inside">
						
						<p><?php _e('Click this button to delete settings of this plugin. Deactivating Secure WordPress plugin remove any data that may have been created.', FB_SWP_TEXTDOMAIN); ?></p>
						<form name="deinstall_options" method="post" action="admin-post.php">
							<?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('secure_wp_uninstall_form'); ?>
							<p id="submitbutton">
								<input type="hidden" name="action" value="swp_uninstall" />
								<input type="submit" value="<?php _e('Delete Options', FB_SWP_TEXTDOMAIN); ?> &raquo;" class="button-secondary" /> 
								<input type="checkbox" name="deinstall_yes" />
							</p>
						</form>
	
					</div>
				</div>
			</div>
			
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div id="secure_wp_win_about" class="postbox <?php echo $secure_wp_win_about ?>" >
					<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br/></div>
					<h3><?php _e('About the plugin', FB_SWP_TEXTDOMAIN) ?></h3>
					<div class="inside">
					
						<p>
							<span style="float: left;">
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
									<input type="hidden" name="cmd" value="_s-xclick">
									<input type="hidden" name="hosted_button_id" value="5295435">
									<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
									<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
								</form>
							</span>
						</p>
						<p><?php _e('Further information: Visit the <a href="http://bueltge.de/wordpress-login-sicherheit-plugin/652/">plugin homepage</a> for further information or to grab the latest version of this plugin.', FB_SWP_TEXTDOMAIN); ?><br />&copy; Copyright 2007 - <?php echo date("Y"); ?> <a href="http://bueltge.de">Frank B&uuml;ltge</a> | <?php _e('You want to thank me? Visit my <a href="http://bueltge.de/wunschliste/">wishlist</a>.', FB_SWP_TEXTDOMAIN); ?></p>
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
	$SecureWP = new SecureWP();
}
?>