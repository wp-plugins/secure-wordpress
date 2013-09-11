<?php if(! defined('SWPA_PLUGIN_PREFIX')) return;

/**
 * Common function to add custom time intervals to wp cron.
 * This function should not be called directly.
 *
 * Usage: add_filter( 'cron_schedules', 'wsdplugin_addCronIntervals' );
 *
 * @param $schedules
 * @return mixed
 */
function swpaplugin_addCronIntervals( $schedules )
{
    $schedules['8h'] = array( // The name to be used in code
        'interval' => 28800, // Intervals: in seconds
        'display' => __('Every 8 hours') // display name
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'swpaplugin_addCronIntervals' );

if(SwpaUtil::canLoad() && SwpaUtil::isAdministrator())
{
    //#!++
    add_action('admin_notices','swpaPlugin_AdminNotice');
    function swpaPlugin_AdminNotice()
    {
        $m = sprintf(
            'We strongly recommend you to use our other plugin <a href="%s" title="%s" target="%s">%s</a> instead, as it will be constantly maintained and updated with new features.',
            'http://wordpress.org/plugins/wp-security-scan/', 'Acunetix WP Security', '_blank', 'Acunetix WP Security'
        );
        echo '<div class="updated"><p><strong>'.SWPA_PLUGIN_NAME.':</strong> '.$m.'</p></div>';
    }
    //#--

    //#!++
    add_action('admin_notices', 'swpaPluginInstallErrorNotice');
    function swpaPluginInstallErrorNotice() {
        if ($notices = get_option('wsd_plugin_install_error')) {
            if(! empty($notices)){
                foreach ($notices as $notice) {
                    echo "<div class='updated'><p>$notice</p></div>";
                }
            }
        }
    }
    //#--
}
