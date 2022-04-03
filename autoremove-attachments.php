<?php
/**
 * Plugin Name:       Autoremove Attachments
 * Plugin URI:        https://wordpress.org/plugins/autoremove-attachments
 * Author:            Polygon Themes
 * Author URI:        https://polygonthemes.com
 * Description:       Autoremove Attachments helps you keep your media library clean by deleting all media files attached to a post when that post is permanently removed from your system.
 * Version:           1.3.1
 * Requires PHP:      7.2
 * Requires at least: 5.0
 *
 * Text Domain:       autoremove-attachments
 * Domain Path:       /languages/
 *
 * License:           GPLv3 or later
 * License URI:       https://choosealicense.com/licenses/gpl-3.0
 *
 * This program is free software.
 * You can redistribute it and/or modify it under the terms of GNU General Public License,
 * as published by the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY.
 * Without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * For more details, see the GNU General Public License.
 *
 * @since   1.0.0
 * @package Autoremove_Attachments
 */

defined( 'ABSPATH' ) || exit;





define( 'AUTOREMOVE_ATTACHMENTS_VERSION', '1.3.1' );
define( 'AUTOREMOVE_ATTACHMENTS_SLUG', 'autoremove-attachments' );

define( 'AUTOREMOVE_ATTACHMENTS_FILE', __FILE__ );
define( 'AUTOREMOVE_ATTACHMENTS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'AUTOREMOVE_ATTACHMENTS_DIR_PATH', plugin_dir_path( __FILE__ ) );

define( 'AUTOREMOVE_ATTACHMENTS_MIN_PHP_VERSION', '7.2' );
define( 'AUTOREMOVE_ATTACHMENTS_REC_PHP_VERSION', '8.0' );





/**
 * Code that runs during the plugin activation.
 *
 * @since 1.0.0
 * @param bool $network_wide Boolean value with the network-wide activation status.
 */
function activate_autoremove_attachments( $network_wide ) {
	require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/class-autoremove-attachments-activator.php';
	Autoremove_Attachments_Activator::activate( $network_wide );
}
register_activation_hook( AUTOREMOVE_ATTACHMENTS_FILE, 'activate_autoremove_attachments' );





/**
 * Code that runs during the plugin deactivation.
 *
 * @since 1.0.0
 * @param bool $network_wide Boolean value with the network-wide activation status.
 */
function deactivate_autoremove_attachments( $network_wide ) {
	require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/class-autoremove-attachments-deactivator.php';
	Autoremove_Attachments_Deactivator::deactivate( $network_wide );
}
register_deactivation_hook( AUTOREMOVE_ATTACHMENTS_FILE, 'deactivate_autoremove_attachments' );





/**
 * Load and execute if all requirements are met.
 *
 * @since 1.0.0
 */
function run_autoremove_attachments() {
	require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/class-autoremove-attachments.php';
	require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/class-autoremove-attachments-textdomain.php';
	require_once AUTOREMOVE_ATTACHMENTS_DIR_PATH . 'includes/class-autoremove-attachments-requirements.php';

	$textdomain = new Autoremove_Attachments_Textdomain();
	$textdomain->init();

	$requirements = new Autoremove_Attachments_Requirements();
	$requirements->init();

	if ( $requirements->check() ) {
		$plugin = new Autoremove_Attachments();
		$plugin->run();
	}
}
add_action( 'plugins_loaded', 'run_autoremove_attachments' );
