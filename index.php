<?php
/**
Plugin Name: Acunetix Secure WordPress
Plugin URI: http://www.acunetix.com/websitesecurity/wordpress-security-plugin/
Description: The Acunetix Secure WordPress plugin is the ultimate must-have tool when it comes to WordPress security. The plugin is free and monitors your website for security weaknesses that hackers might exploit and tells you how to easily fix them.
Version: 3.0.1
Author: Acunetix
Author URI: http://www.acunetix.com/
License: GPLv2 or later
Text Domain: WSDWP_SECURITY
Domain Path: /languages
*/
define('SWPA_PLUGIN_PREFIX', 'swpa_');
define('SWPA_PLUGIN_NAME', 'Acunetix Secure WordPress');
define('SWPA_PLUGIN_URL', trailingslashit(plugins_url('', __FILE__)));
define('SWPA_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('SWPA_PLUGIN_BASE_NAME', basename(__DIR__));

require('swpa-settings.php');
require('res/inc/alerts.php');
require('res/inc/SwpaUtil.php');
require('res/inc/SwpaPlugin.php');
require('res/inc/SwpaInfo.php');
require('res/inc/SwpaSecurity.php');
require('res/inc/SwpaCheck.php');
require('res/inc/SwpaScheduler.php');
require('res/inc/SwpaWatch.php');
require('res/inc/SwpaLiveTraffic.php');
require('res/inc/swpa-functions.php');

//#!--
add_action('admin_init', array('SwpaUtil','loadPluggable'));
register_activation_hook( __FILE__, array('SwpaPlugin', 'activate') );
register_deactivation_hook( __FILE__, array('SwpaPlugin', 'deactivate') );
register_uninstall_hook( __FILE__, array('SwpaPlugin', 'uninstall') );
//#++



#! register tasks
if(false !== get_option('WSD-PLUGIN-CAN-RUN-TASKS',false))
{
    SwpaScheduler::registerTask(array('SwpaPlugin','loadResources'), 'init');
    SwpaScheduler::registerTask(array('SwpaPlugin','createWpMenu'), 'admin_menu');
    SwpaScheduler::registerTask(array('SwpaLiveTraffic','registerHit'), 'init');
    SwpaScheduler::registerTask(array('SwpaLiveTraffic','ajaxGetTrafficData'), 'wp_ajax_ajaxGetTrafficData');
    SwpaScheduler::registerTask(array('SwpaLiveTraffic','ajaxGetTrafficData'), 'wp_ajax_nopriv_ajaxGetTrafficData');
    SwpaScheduler::registerTask(array('SwpaUtil','addDashboardWidget'), 'wp_dashboard_setup');

// override - scheduled task
    SwpaScheduler::registerCronTask('swpa_check_user_admin', array('SwpaCheck','adminUsername'), '8h');

// scheduled task - hourly cleanup of events in live traffic
    SwpaScheduler::registerCronTask('swpa_cleanup_live_traffic', array('SwpaLiveTraffic','clearEvents'), 'hourly');

// stacked
    SwpaScheduler::registerTask(array('SwpaWatch','userPasswordUpdate'));

// #! run fixes. Only those checked by the user will run (@see: settings page)
    SwpaScheduler::registerClassTasks('SwpaSecurity','fix_');

//#! run checks.
    SwpaScheduler::registerClassTasks('SwpaCheck','check_');
}
