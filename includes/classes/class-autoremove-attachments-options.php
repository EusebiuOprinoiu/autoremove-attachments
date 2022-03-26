<?php
/**
 * Create the options page
 *
 * @since   1.2.0
 * @package Autoremove_Attachments
 */

defined( 'ABSPATH' ) || exit;





/**
 * Create the options page.
 *
 * This class is used to maintain the code needed to create the options page.
 *
 * @since 1.2.0
 */
class Autoremove_Attachments_Options {

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'extend_media_options' ) );
	}





	/**
	 * Create options under Settings > Media.
	 *
	 * Create a dedicated section under Settings > Media from where we will be able
	 * to control how our plugin behaves.
	 *
	 * @since 1.0.0
	 */
	public function extend_media_options() {
		$autoremove_attachments = get_option( 'autoremove_attachments' );

		// Register settings.
		register_setting(
			'media',                              // Options group.
			'autoremove_attachments',             // Options name.
			array( $this, 'sanitize_options' )    // Sanitization function.
		);



		// Add settings section.
		add_settings_section(
			'autoremove-attachments-section',                            // Section ID.
			esc_html__( 'Removing Files', 'autoremove-attachments' ),    // Section title.
			array( $this, 'display_description' ),                       // Callback function to output content.
			'media'                                                      // Options page slug.
		);



		// Add setting fields.
		add_settings_field(
			'autoremove-attachments-checks',                                                       // Setting ID.
			esc_html__( 'Perform additional checks before removal', 'autoremove-attachments' ),    // Setting title.
			array( $this, 'display_options' ),                                                     // Callback function to output content.
			'media',                                                                               // Options page slug.
			'autoremove-attachments-section',                                                      // Section ID.
			array( 'id' => 'autoremove-attachments-checks' )                                       // Extra arguments for the callback function.
		);
	}





	/**
	 * Show section description on the Media page.
	 *
	 * Display a message at the top of our custom section on the Media page.
	 *
	 * @since 1.0.0
	 */
	public function display_description() {
		?>
			<p>
				<?php echo esc_html__( 'While Autoremove Attachments is active on your website, all media files attached as child attachments to a post, page, or custom post type are removed automatically when the parent is deleted.', 'autoremove-attachments' ); ?><br>
				<?php echo esc_html__( 'Use the settings below to control how these attachments are removed and to optimize the cleanup process on large websites with thousands of posts and media files.', 'autoremove-attachments' ); ?>
			</p>
		<?php

		do_settings_sections( 'autoremove_attachments' );
	}





	/**
	 * Display options on the Media page.
	 *
	 * Cicle through all options and display the HTML markup for the current one.
	 *
	 * @since 1.0.0
	 * @param array $args Array with additional arguments.
	 */
	public function display_options( $args ) {
		// Variables.
		$autoremove_attachments = get_option( 'autoremove_attachments' );
		$id                     = $args['id'];

		switch ( $id ) {
			case 'autoremove-attachments-checks':
				?>
					<fieldset>
						<p>
							<label class="autoremove-attachments-label">
								<input type="radio" value="enabled" name="autoremove_attachments[additional-checks]" <?php checked( $autoremove_attachments['additional-checks'], 'enabled' ); ?> />
								<?php echo esc_html__( 'Enabled (for increased security)', 'autoremove-attachments' ); ?>
							</label><br>

							<label class="autoremove-attachments-label">
								<input type="radio" value="disabled" name="autoremove_attachments[additional-checks]" <?php checked( $autoremove_attachments['additional-checks'], 'disabled' ); ?> />
								<?php echo esc_html__( 'Disabled (for increased performance)', 'autoremove-attachments' ); ?>
							</label>
						</p>

						<p class="description">
							<?php echo esc_html__( 'When this option is Enabled, we will try to determine if the child attachments marked for automatic removal are reused between posts.', 'autoremove-attachments' ); ?><br>
							<?php echo esc_html__( 'If we find that your attachments are used in multiple places, we will not remove them, to prevent broken links.', 'autoremove-attachments' ); ?><br>
							<?php echo esc_html__( 'Use this option on small and medium websites where performance is not an issue.', 'autoremove-attachments' ); ?><br><br>

							<?php echo esc_html__( 'When this option is Disabled, we will not perform any checks before removing the child attachments marked for removal.', 'autoremove-attachments' ); ?><br>
							<?php echo esc_html__( 'If you reuse your attachments and disable the additional checks, you will end up with broken links.', 'autoremove-attachments' ); ?><br>
							<?php echo esc_html__( 'Use this option on large websites with thousands of posts and media files. (if you experience slow queries and timeouts)', 'autoremove-attachments' ); ?><br><br>

							<?php echo esc_html__( 'Regardless of what option you choose, if you need to use an attachment over and over again, upload it from the global Media Library menu. ( Media > Add New )', 'autoremove-attachments' ); ?><br>
							<?php echo esc_html__( 'This way, it won\'t be attached as a child to a specific post, page, or custom post type.', 'autoremove-attachments' ); ?>
						</p>
					</fieldset>
				<?php
				break;



			// Default value for debugging.
			default:
				echo esc_html__( 'Wrong option ID.', 'autoremove-attachments' );
				break;
		}
	}





	/**
	 * Sanitize options.
	 *
	 * Validate and sanitize options upon saving.
	 * Rebuild missing keys when options are stored as an array. WordPress removes empty
	 * keys so we need to recreate them in order to avoid any possible issues that might appear.
	 *
	 * @since  1.0.0
	 * @param  array $input  Array with all options.
	 * @return array $output Array with the new options.
	 */
	public function sanitize_options( $input ) {
		$output = get_option( 'autoremove_attachments' );



		// Sanitize plugin version.
		if ( isset( $input['version'] ) ) {
			$output['version'] = wp_strip_all_tags( $input['version'], true );
		}



		// Sanitize plugin db-version.
		if ( isset( $input['db-version'] ) ) {
			$output['db-version'] = wp_strip_all_tags( $input['db-version'], true );
		}



		// Sanitize Onboarding Notice - usually called from admin notification.
		if ( isset( $input['onboarding-notice'] ) ) {
			if ( in_array( $input['onboarding-notice'], array( 'show', 'hide' ), true ) ) {
				$output['onboarding-notice'] = $input['onboarding-notice'];
			} else {
				$output['onboarding-notice'] = 'show';
			}
		}



		// Sanitize Removing Files > Perform additional checks before removal.
		if ( isset( $input['additional-checks'] ) ) {
			if ( in_array( $input['additional-checks'], array( 'enabled', 'disabled' ), true ) ) {
				$output['additional-checks'] = $input['additional-checks'];
			} else {
				$output['additional-checks'] = 'enabled';
			}
		}



		// Return.
		return $output;
	}
}
