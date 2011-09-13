<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
	exit();

delete_option('WSD-COOKIE');
delete_option('WSD-TOKEN');
delete_option('WSD-TARGETID');
delete_option('WSD-USER');
delete_option('secure-wp');
delete_option('wsd_feed_data');