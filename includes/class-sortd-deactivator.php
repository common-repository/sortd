<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.sortd.mobi
 * @since      1.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Sortd
 * @subpackage Sortd/includes
 * @author     Your Name <email@example.com>
 */
class Sortd_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		require_once( plugin_dir_path( __DIR__ ) . 'admin/class-sortd-utils.php' );
        $utils_obj = new Sortd_Utils('wp_sortd','1.0.35','');

        $utils_obj->get_data_on_plugin_deactivation();
        update_option( 'sortd_activated', 0 );
        update_option( 'sortd_activated', 0 );
	}

}
