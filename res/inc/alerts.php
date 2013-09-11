<?php /** Alerts & descriptions */
$wsdPluginAlertsArray = array
(
// SwpaCheck::adminUsername
    'check_username_admin' => array('name' => 'check_username_admin ', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
// SwpaCheck::tablePrefix
    'check_table_prefix' => array('name' => 'check_table_prefix', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
// SwpaCheck::currentVersion
    'check_wp_current_version' => array('name' => 'check_wp_current_version', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
// SwpaCheck::files
    'check_index_wp_content' => array('name' => 'check_index_wp_content', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaCheck::files
    'check_index_wp_content' => array('name' => 'check_index_wp_content', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaCheck::files
    'check_index_wp_plugins' => array('name' => 'check_index_wp_plugins', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaCheck::files
    'check_index_wp_themes' => array('name' => 'check_index_wp_themes', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaCheck::files - alert issued only if the wp-content/uploads directory exists
    'check_index_wp_uploads' => array('name' => 'check_index_wp_uploads', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaCheck::files
    'check_htaccess_wp_admin' => array('name' => 'check_htaccess_wp_admin', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaCheck::files
    'check_readme_wp_root' => array('name' => 'check_readme_wp_root', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),


//  SwpaSecurity::fix_hideWpVersion
    'fix_wp_version_hidden' => array('name' => 'fix_wp_version_hidden', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_removeWpMetaGeneratorsFrontend
    'fix_wp_generators_frontend' => array('name' => 'fix_wp_generators_frontend', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_removeReallySimpleDiscovery
    'fix_wp_rsd_frontend' => array('name' => 'fix_wp_rsd_frontend', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_removeWindowsLiveWriter
    'fix_wp_wlw_frontend' => array('name' => 'fix_wp_wlw_frontend', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_disableErrorReporting
    'fix_wp_error_reporting' => array('name' => 'fix_wp_error_reporting', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_removeCoreUpdateNotification
    'fix_wp_core_update_notif' => array('name' => 'fix_wp_core_update_notif', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_removePluginUpdateNotifications
    'fix_wp_plugins_update_notif' => array('name' => 'fix_wp_plugins_update_notif', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_removeThemeUpdateNotifications
    'fix_wp_themes_update_notif' => array('name' => 'fix_wp_themes_update_notif', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_removeLoginErrorNotificationsFrontEnd
    'fix_wp_login_errors' => array('name' => 'fix_wp_login_errors', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_hideAdminNotifications
    'fix_wp_admin_notices' => array('name' => 'fix_wp_admin_notices', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_preventDirectoryListing
    'fix_wp_dir_listing' => array('name' => 'fix_wp_dir_listing', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_preventDirectoryListing
    'fix_wp_index_content' => array('name' => 'fix_wp_index_content', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_preventDirectoryListing
    'fix_wp_index_plugins' => array('name' => 'fix_wp_index_plugins', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_preventDirectoryListing
    'fix_wp_index_themes' => array('name' => 'fix_wp_index_themes', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_preventDirectoryListing - alert issued only if the wp-content/uploads directory exists
    'fix_wp_index_uploads' => array('name' => 'fix_wp_index_uploads', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
//  SwpaSecurity::fix_removeWpVersionFromLinks
    'fix_remove_wp_version_links' => array('name' => 'fix_remove_wp_version_links', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),
// SwpaSecurity::fix_emptyReadmeFileFromRoot
    'fix_empty_root_readme_file' => array('name' => 'fix_empty_root_readme_file', 'type' => SWPA_PLUGIN_ALERT_TYPE_OVERWRITE ),


//  SwpaWatch::userPasswordUpdate
    'watch_admin_password_update' => array('name' => 'watch_admin_password_update', 'type' => SWPA_PLUGIN_ALERT_TYPE_STACK ),
);