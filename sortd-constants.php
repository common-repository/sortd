<?php

/**
 * The plugin constants file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.sortd.mobi
 * @since             1.0.0
 * @package           Sortd
 */


define( 'SORTD_VERSION', '3.0.5' ); 

define( 'SORTD_VERSION_CODE', 24052911 );

define( 'SORTD_ENVIRONMENT', 'PRODUCTION' );

define( 'SORTD_API_BASE', 'publishapi.sortd.mobi' );

define( 'SORTD_CONSOLE_BASE', 'console.sortd.mobi' );

define('SORTD_INCLUDES_PATH', plugin_dir_path( __FILE__ ) . 'includes');

define('SORTD_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin');

define('SORTD_PUBLIC_PATH', plugin_dir_path( __FILE__ ) . 'public');

define('SORTD_CSS_URL', plugin_dir_url( __FILE__ ) . 'admin/css');

define('SORTD_JS_URL', plugin_dir_url( __FILE__ ) . 'admin/js');

define('SORTD_PARTIALS_PATH', plugin_dir_path( __FILE__ ) . 'admin/partials');

define( 'SORTD_NONCE', 'my_nonce');
