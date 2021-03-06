<?php
/**
 * Uninstallation hook.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Inc\Base
 * @author     Sayan Datta <hello@sayandatta.in>
 */

namespace Inc\Base;

/**
 * Uninstall class.
 */
class Uninstall
{
	/**
	 * Run plugin uninstallation process.
	 */
	public static function uninstall() {
		$option_name = 'wpar_plugin_settings';
		if ( ! is_multisite() ) {
			$options = get_option( $option_name );
			if( isset( $options['wpar_remove_plugin_data'] ) && ( $options['wpar_remove_plugin_data'] == 1 ) ) {
				// delete plugin settings
			    delete_option( $option );
			    // uninstall action
			    do_action( 'wpar/plugin_uninstall_action' );
			}
		} else {
			global $wpdb;
			
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				$options = get_option( $option_name );
				if( isset( $options['wpar_remove_plugin_data'] ) && ( $options['wpar_remove_plugin_data'] == 1 ) ) {
					// delete plugin settings
					delete_option( $option );
					// uninstall action
					do_action( 'wpar/plugin_uninstall_action' );
				}
			}
			switch_to_blog( $original_blog_id );
		}
	}
}