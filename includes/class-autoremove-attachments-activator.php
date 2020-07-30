<?php
/**
 * Fired during plugin activation
 *
 * @since   1.0.0
 * @package Autoremove_Attachments
 */





/**
 * Fired during plugin activation.
 *
 * This class defines all the code that runs during the plugin activation.
 *
 * @since 1.0.0
 */
class Autoremove_Attachments_Activator {

	/**
	 * Run the activation script.
	 *
	 * Run the activation script for the current site if we are on a standard
	 * WordPress install or for all sites if we are on WordPress Multisite
	 * and the plugin is network activated.
	 *
	 * @since 1.0.0
	 * @param bool $network_wide Boolean value with the network-wide activation status.
	 */
	public static function activate( $network_wide = false ) {
		if ( is_multisite() ) {
			if ( $network_wide ) {
				global $wpdb;

				// phpcs:ignore
				$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );

				if ( $blogs ) {
					foreach ( $blogs as $blog ) {
						switch_to_blog( $blog['blog_id'] );

						self::run_activation_script();
					}
					restore_current_blog();
				}
			} else {
				self::run_activation_script();
			}
		} else {
			self::run_activation_script();
		}
	}





	/**
	 * Do stuff on plugin activation.
	 *
	 * Create the plugin options, set their defaults and create any required database tables
	 * on the first plugin activation.
	 *
	 * @since 1.0.0
	 */
	public static function run_activation_script() {
		// Create plugin options if not available.
		if ( ! get_option( 'autoremove_attachments' ) ) {
			$autoremove_attachments = array(
				'version'           => AUTOREMOVE_ATTACHMENTS_VERSION,
				'db-version'        => AUTOREMOVE_ATTACHMENTS_VERSION,
				'onboarding-notice' => 'show',
				'additional-checks' => 'enabled',
			);

			add_option( 'autoremove_attachments', $autoremove_attachments );
		}
	}
}
