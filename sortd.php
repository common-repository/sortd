<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.sortd.mobi
 * @since             1.0.0
 * @package           Sortd
 *
 * @wordpress-plugin
 * Plugin Name:       SORTD
 * Plugin URI:        https://www.sortd.mobi/
 * Description:       SORTD helps to create a feature-rich native experience of your brand on PWA, Android & iOS.
 * Version:           3.0.5
 * Author:            Mediology Software Pvt. Ltd.
 * Author URI:        https://www.mediologysoftware.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sortd
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Constants file for the SORTD Plugin
 */

include plugin_dir_path(__FILE__).'sortd-constants.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sortd-activator.php
 */
function activate_sortd() {
    require_once SORTD_INCLUDES_PATH . '/class-sortd-activator.php';
    Sortd_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sortd-deactivator.php
 */
function deactivate_sortd() {
    require_once SORTD_INCLUDES_PATH . '/class-sortd-deactivator.php';
    Sortd_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sortd' );
register_deactivation_hook( __FILE__, 'deactivate_sortd' );

add_action('admin_init', 'sortd_redirect');

/**
 * Redirect to plugin page after activating plugin
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function sortd_redirect() {
    if (get_option('sortd_do_activation_redirect', false)) {
        delete_option('sortd_do_activation_redirect');
        if(function_exists('wp_verify_nonce')){
            if(!wp_verify_nonce('sortd_nonce')){
                return false;
            }
        }	
        if(!isset($_GET['activate-multi']))
        {
            add_option('sortd_notification_bubble', 1);
           wp_redirect(site_url()."/wp-admin/admin.php?page=sortd-settings");
           exit;
        }
    }
}


add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'menu_add_sortd_settings_link');

/**
 * For adding  links to sortd settings page
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.1
 */
function menu_add_sortd_settings_link( $links ) {
    $links[] = '<a href="' .
        admin_url( 'options-general.php?page=sortd-settings' ) .
        '">' . __('Configure') . '</a> | <a href="https://www.sortd.mobi/" target="_blank">' . __('Get Help') . '</a>';
    return $links;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require SORTD_INCLUDES_PATH . '/class-sortd.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sortd() {

    $plugin = new Sortd();
    $plugin->run();

}
run_sortd();
