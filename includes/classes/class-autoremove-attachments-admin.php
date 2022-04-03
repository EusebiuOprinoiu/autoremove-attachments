<?php
/**
 * Do things in the admin area
 *
 * @since   1.0.0
 * @package Autoremove_Attachments
 */

defined( 'ABSPATH' ) || exit;





/**
 * Do things in the admin area.
 *
 * This class is used to maintain the functionality for the admin-facing side
 * of our website.
 *
 * @since 1.0.0
 */
class Autoremove_Attachments_Admin {

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'network_admin_notices', array( $this, 'onboarding_notice' ) );
		add_action( 'admin_notices', array( $this, 'onboarding_notice' ) );
		add_action( 'before_delete_post', array( $this, 'remove_attachments' ) );
	}





	/**
	 * Remove attachments.
	 *
	 * Get the list of attachments for the post we are about to delete and if the attachments
	 * are not reused, remove them.
	 *
	 * @since 1.0.0
	 * @param int $post_id ID of the curent post.
	 */
	public function remove_attachments( $post_id ) {
		// Force type as int.
		$post_id = (int) $post_id;

		if ( $post_id ) {
			$autoremove_attachments = get_option( 'autoremove_attachments' );
			$additional_checks      = isset( $autoremove_attachments['additional-checks'] ) ? $autoremove_attachments['additional-checks'] : 'enabled';
			$allowed_to_remove      = apply_filters( 'autoremove_attachments_allowed', true );



			if ( $allowed_to_remove ) {
				$args  = array(
					'post_type'              => 'attachment',
					'post_parent'            => $post_id,
					'post_status'            => 'any',
					'posts_per_page'         => -1,
					'nopaging'               => true,

					// Optimize query for performance.
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				);
				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						switch ( $additional_checks ) {
							case 'enabled':
								$attachment_used_in = $this->get_attachment_used_in( $query->post->ID );

								// Remove current parent ID and normalize array.
								if ( in_array( $post_id, $attachment_used_in, true ) ) {
									unset( $attachment_used_in[ array_search( $post_id, $attachment_used_in, true ) ] );
									$attachment_used_in = array_values( $attachment_used_in );    // Make consecutive keys.
								}

								// Change the parent ID if the attachment is reused. Delete otherwise.
								if ( ! empty( $attachment_used_in ) ) {
									$args = array(
										'ID'          => $query->post->ID,
										'post_parent' => $attachment_used_in[0],
									);
									wp_update_post( $args );
								} else {
									wp_delete_attachment( $query->post->ID, true );
								}
								break;



							default:
								wp_delete_attachment( $query->post->ID, true );
								break;
						}
					}
				}

				wp_reset_postdata();
			}
		}
	}





	/**
	 * Find where the attachment is used.
	 *
	 * Find where the attachment is used and return an array with all the IDs of
	 * posts, pages and custom post types that use it either as a Featured Image,
	 * or inline, in the main content.
	 *
	 * @since 1.2.0
	 * @param int $attachment_id ID of the curent attachment.
	 */
	public function get_attachment_used_in( $attachment_id ) {
		$attachment_used_in = array();
		$attachment_urls    = array(
			wp_get_original_image_url( $attachment_id ),
			wp_get_attachment_url( $attachment_id ),
		);
		$attachment_urls    = array_filter( $attachment_urls );    // Remove empty values caused by wp_get_original_image_url() when the attachment is not an image.



		// If the attachment is an image, find where it's used as a Featured Image.
		if ( wp_attachment_is_image( $attachment_id ) ) {
			$args = array(
				'post_type'              => 'any',
				'post_status'            => array( 'any', 'publish', 'private', 'pending', 'future', 'draft', 'trash' ),
				'meta_key'               => '_thumbnail_id',    // phpcs:ignore
				'meta_value'             => $attachment_id,     // phpcs:ignore
				'posts_per_page'         => -1,
				'nopaging'               => true,
				'fields'                 => 'ids',

				// Optimize query for performance.
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			);
			$query = new WP_Query( $args );

			$attachment_used_in = array_merge( $attachment_used_in, $query->posts );
		}



		// If the attachment is an image, find the URLs for all intermediate sizes.
		if ( wp_attachment_is_image( $attachment_id ) ) {
			foreach ( get_intermediate_image_sizes() as $size ) {
				$intermediate_size = image_get_intermediate_size( $attachment_id, $size );

				if ( $intermediate_size ) {
					$attachment_urls[] = $intermediate_size['url'];
				}
			}
		}



		// Find where the attachment URLs are used.
		foreach ( $attachment_urls as $attachment_url ) {
			$args = array(
				'post_type'              => 'any',
				'post_status'            => array( 'any', 'publish', 'private', 'pending', 'future', 'draft', 'trash' ),
				's'                      => $attachment_url,
				'posts_per_page'         => -1,
				'nopaging'               => true,
				'fields'                 => 'ids',

				// Optimize query for performance.
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			);
			$query = new WP_Query( $args );

			$attachment_used_in = array_merge( $attachment_used_in, $query->posts );
		}



		// Check if the attachment is used in a WooCommerce gallery.
		if ( class_exists( 'WooCommerce' ) ) {
			$args = array(
				'post_type'              => 'product',
				'post_status'            => array( 'any', 'publish', 'private', 'pending', 'future', 'draft', 'trash' ),
				'posts_per_page'         => -1,
				'nopaging'               => true,

				// Optimize query for performance.
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$product             = wc_get_product( $query->post->ID );
					$gallery_attachments = $product->get_gallery_image_ids();
					if ( in_array( $attachment_id, $gallery_attachments, true ) ) {
						$attachment_used_in[] = $query->post->ID;
					}
				}
			}
		}



		// Normalize array before returning.
		$attachment_used_in = array_unique( $attachment_used_in );    // Keep unique values only.
		$attachment_used_in = array_filter( $attachment_used_in );    // Remove empty values.
		$attachment_used_in = array_values( $attachment_used_in );    // Make consecutive keys.

		// Return an array with all post IDs where the attachment is used.
		return $attachment_used_in;
	}





	/**
	 * Display a warning about automatic removal of media files.
	 *
	 * Display a warning about the automatic removal of media files to make sure
	 * people understand the consequences of reusing images between posts.
	 *
	 * @since 1.0.0
	 */
	public function onboarding_notice() {
		if ( current_user_can( 'manage_options' ) ) {
			$autoremove_attachments = get_option( 'autoremove_attachments' );
			$onboarding_notice      = isset( $autoremove_attachments['onboarding-notice'] ) ? $autoremove_attachments['onboarding-notice'] : 'show';



			// Hide notice if the button Ignore Notice is clicked by adding a flag.
			// phpcs:ignore -- no need to use nonces for this
			if ( isset( $_GET['autoremove_attachments_hide_notice'] ) && ( $_GET['autoremove_attachments_hide_notice'] === 'true' ) ) {
				$autoremove_attachments['onboarding-notice'] = 'hide';

				update_option( 'autoremove_attachments', $autoremove_attachments );

				return; // Do not display the notice on page reload.
			}



			// Display the actual notice if the user didn't click on the Hide Notice button.
			if ( $onboarding_notice === 'show' ) {
				?>
					<div class="notice notice-info">
						<p></p>
						<p>
							<b><?php echo esc_html__( 'New behavior for attachments', 'autoremove-attachments' ); ?></b>
						</p>
						<p>
							<?php echo esc_html__( 'Autoremove Attachments is now active on your website.', 'autoremove-attachments' ); ?><br>
							<?php echo esc_html__( 'All media files attached as child attachments to a post, page, or custom post type are now removed as well when the parent is deleted.', 'autoremove-attachments' ); ?><br>
						</p>
						<p>
							<b><?php echo esc_html__( 'Other important considerations', 'autoremove-attachments' ); ?></b>
						</p>
						<ol>
							<li><?php echo esc_html__( 'A soft delete that places your post, page, or custom post type in Trash will not trigger the removal of its child attachments. The purge happens when you empty your trash.', 'autoremove-attachments' ); ?></li>
							<li><?php echo esc_html__( 'When you delete a post, page or custom post type, we try to determine if its child attachments are used anywhere else on your website. If they are, we do not remove them, to prevent broken links.', 'autoremove-attachments' ); ?><br>
								<?php echo esc_html__( 'Please, keep in mind, this is not something fool-proof, especially if you store media file IDs or URLs in custom fields. That\'s why we recommend you avoid reusing your attachments between posts, if possible.', 'autoremove-attachments' ); ?><br>
								<?php echo esc_html__( 'If you need to use an attachment over and over again, upload it from the global Media Library menu ( Media > Add New ). This way, it won\'t be attached as a child to a specific post, page, or custom post type.', 'autoremove-attachments' ); ?></li>
							<li><?php echo esc_html__( 'The additional checks before the automatic removal can be disabled from the Media Settings for improved performance on large websites with thousands of posts and media files.', 'autoremove-attachments' ); ?><br>
								<?php echo esc_html__( 'Disable the additional checks if you experience slow queries and timeouts when you empty your trash.', 'autoremove-attachments' ); ?></li>
							<li><?php echo esc_html__( 'The plugin only removes files tracked by WordPress. Some poorly coded themes generate additional thumbnail sizes that are not tracked by WordPress and this always leads to orphan files left on your server.', 'autoremove-attachments' ); ?><br>
								<?php echo esc_html__( 'Though technically not an issue related to Autoremove Attachments, this is something you should be aware of if you\'re using a very old theme relying on third-party scripts (like TimThumb or Aqua Resizer) for generating thumbnails.', 'autoremove-attachments' ); ?></li>
						</ol>
						<p>
							<a href="<?php echo esc_url( get_site_url( null, 'wp-admin/options-media.php', 'admin' ) ); ?>">
								<b><?php echo esc_html__( 'Change Settings', 'autoremove-attachments' ); ?></b>
							</a>
							|
							<a href="<?php echo esc_url( add_query_arg( 'autoremove_attachments_hide_notice', 'true' ) ); ?>">
								<b><?php echo esc_html__( 'Hide Notice', 'autoremove-attachments' ); ?></b>
							</a>
						</p>
						<p></p>
					</div>
				<?php
			}
		}
	}
}
