<?php
/**
 * Plugin updates and migrations
 *
 * @since   1.0.0
 * @package Autoremove_Attachments
 */

defined( 'ABSPATH' ) || exit;





/**
 * Plugin updates and migrations.
 *
 * This class handles all database changes required after a plugin update.
 * It also makes sure the changes propagate on all sites when using Multisite.
 *
 * @since 1.0.0
 */
class Autoremove_Attachments_Updates {

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'plugins_loaded', array( $this, 'maybe_run_recursive_updates' ) );
		add_action( 'wpmu_new_blog', array( $this, 'maybe_run_activation_script' ), 10, 6 );
	}





	/**
	 * Migrate and update options on plugin updates.
	 *
	 * Compare the current plugin version with the one stored in the options table
	 * and migrate recursively if needed after a plugin update. The migration code for each
	 * version is stored in individual files and it's triggered only if the 'db-version' key
	 * is older than versions where changes have been made.
	 *
	 * @since    1.0.0
	 */
	public function maybe_run_recursive_updates() {
		$autoremove_attachments = get_option( 'autoremove_attachments' );

		// Migrate from 'plugin-version' key to 'version'.
		if ( isset( $autoremove_attachments['plugin-version'] ) ) {
			$autoremove_attachments['version'] = $autoremove_attachments['plugin-version'];
			update_option( 'autoremove_attachments', $autoremove_attachments );
		}

		// Migrate from 'last-updated-version' key to 'db-version'.
		if ( isset( $autoremove_attachments['last-updated-version'] ) ) {
			$autoremove_attachments['db-version'] = $autoremove_attachments['last-updated-version'];
			update_option( 'autoremove_attachments', $autoremove_attachments );
		}





		if ( ! isset( $autoremove_attachments['version'] ) ) {
			$autoremove_attachments['version'] = AUTOREMOVE_ATTACHMENTS_VERSION;
			update_option( 'autoremove_attachments', $autoremove_attachments );
		}

		if ( ! isset( $autoremove_attachments['db-version'] ) ) {
			$autoremove_attachments['db-version'] = AUTOREMOVE_ATTACHMENTS_VERSION;
			update_option( 'autoremove_attachments', $autoremove_attachments );
		}

		if ( version_compare( AUTOREMOVE_ATTACHMENTS_VERSION, $autoremove_attachments['version'] ) > 0 ) {
			// Migrate options to version 1.0.8.
			if ( version_compare( $autoremove_attachments['version'], '1.0.8' ) < 0 ) {
				require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/classes/updates/update-to-version-1.0.8.php';
				$autoremove_attachments['db-version'] = '1.0.8';
			}

			// Migrate options to version 1.2.0.
			if ( version_compare( $autoremove_attachments['version'], '1.2.0' ) < 0 ) {
				require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/classes/updates/update-to-version-1.2.0.php';
				$autoremove_attachments['db-version'] = '1.2.0';
			}



			// Update plugin version.
			$autoremove_attachments['version'] = AUTOREMOVE_ATTACHMENTS_VERSION;

			// Update plugin options.
			update_option( 'autoremove_attachments', $autoremove_attachments );
		}
	}





	/**
	 * Run activation script for new sites.
	 *
	 * If we are running WordPress Multisite and our plugin is network activated,
	 * run the activation script every time a new site is created.
	 *
	 * @since 1.0.0
	 * @param int    $blog_id Blog ID of the created blog. Optional.
	 * @param int    $user_id User ID of the user creating the blog. Required.
	 * @param string $domain  Domain used for the new blog. Optional.
	 * @param string $path    Path to the new blog. Optional.
	 * @param int    $site_id Site ID. Only relevant on multi-network installs. Optional.
	 * @param array  $meta    Meta data. Used to set initial site options. Optional.
	 */
	public function maybe_run_activation_script( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		if ( $blog_id ) {
			if ( is_plugin_active_for_network( plugin_basename( AUTOREMOVE_ATTACHMENTS_FILE ) ) ) {
				switch_to_blog( $blog_id );

				require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/class-autoremove-attachments-activator.php';
				Autoremove_Attachments_Activator::run_activation_script();

				restore_current_blog();
			}
		}
	}
}
