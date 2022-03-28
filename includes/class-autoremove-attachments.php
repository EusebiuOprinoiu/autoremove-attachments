<?php
/**
 * The core plugin class
 *
 * @since   1.0.0
 * @package Autoremove_Attachments
 */

defined( 'ABSPATH' ) || exit;





/**
 * The core plugin class.
 *
 * This class is used to load all dependencies, prepare the plugin for translation
 * and register all actions and filters with WordPress.
 *
 * @since 1.0.0
 */
class Autoremove_Attachments {

	/**
	 * Get things started.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->includes();
		$this->init();
	}





	/**
	 * Load required dependencies.
	 *
	 * Load the files required to create our plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function includes() {
		require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/classes/class-autoremove-attachments-admin.php';
		require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/classes/class-autoremove-attachments-options.php';
		require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/classes/class-autoremove-attachments-updates.php';
	}





	/**
	 * Register hooks with WordPress.
	 *
	 * Create objects from classes and hook into actions and filters.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function init() {
		$admin = new Autoremove_Attachments_Admin();
		$admin->init();

		$options = new Autoremove_Attachments_Options();
		$options->init();

		$updates = new Autoremove_Attachments_Updates();
		$updates->init();
	}
}
