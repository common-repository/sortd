<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.sortd.mobi
 * @since      1.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sortd
 * @subpackage Sortd/includes
 * @author     Your Name <email@example.com>
 */
class Sortd_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
            update_option( 'sortd_activated', 1 );
            add_option('sortd_do_activation_redirect', true);
            update_option('activate_sortd', 1);
			$get_option = get_option('sortd_projectid');
			update_option('sortd_author_sync_success_'.$get_option,0);
			
	}

}
