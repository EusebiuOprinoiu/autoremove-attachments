<?php
/**
 * The core plugin class
 *
 * @since   1.0.0
 * @package Autoremove_Attachments
 */





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
	 * Execute all hooks.
	 *
	 * Load dependencies, the plugin text-domain and execute all hooks
	 * we previously registered inside the function define_hooks().
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->load_dependencies();
		$this->load_textdomain();
		$this->define_hooks();
	}





	/**
	 * Load required dependencies.
	 *
	 * Load the files required to create our plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function load_dependencies() {
		require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/class-autoremove-attachments-textdomain.php';
		require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/general/class-autoremove-attachments-admin.php';
		require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/general/class-autoremove-attachments-options.php';
		require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/general/class-autoremove-attachments-updates.php';
	}





	/**
	 * Load plugin text-domain.
	 *
	 * Uses the Autoremove_Attachments_Textdomain class in order to set the text-domain and define
	 * the location of our translation files.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_textdomain() {
		$textdomain = new Autoremove_Attachments_Textdomain();

		add_action( 'after_setup_theme', array( $textdomain, 'load_plugin_textdomain' ) );
	}





	/**
	 * Register hooks with WordPress.
	 *
	 * Create objects from classes and register all hooks with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_hooks() {
		// Create objects from classes.
		$admin   = new Autoremove_Attachments_Admin();
		$options = new Autoremove_Attachments_Options();
		$updates = new Autoremove_Attachments_Updates();

		// Register admin hooks.
		add_action( 'network_admin_notices', array( $admin, 'onboarding_notice' ) );
		add_action( 'admin_notices', array( $admin, 'onboarding_notice' ) );
		add_action( 'before_delete_post', array( $admin, 'remove_attachments' ) );

		// Register options hooks.
		add_action( 'admin_init', array( $options, 'extend_media_options' ) );

		// Register db update hooks.
		add_action( 'plugins_loaded', array( $updates, 'maybe_run_recursive_updates' ) );
		add_action( 'wpmu_new_blog', array( $updates, 'maybe_run_activation_script' ), 10, 6 );
	}
}
