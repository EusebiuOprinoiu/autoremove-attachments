<?php
/**
 * Update plugin to version 1.2.0
 *
 * @since   1.2.0
 * @package Autoremove_Attachments
 */

defined( 'ABSPATH' ) || exit;





// Migrate.
$autoremove_attachments = get_option( 'autoremove_attachments' );

if ( isset( $autoremove_attachments['plugin-version'] ) ) {
	unset( $autoremove_attachments['plugin-version'] );
}

if ( isset( $autoremove_attachments['last-updated-version'] ) ) {
	unset( $autoremove_attachments['last-updated-version'] );
}

if ( isset( $autoremove_attachments['usage-restrictions'] ) ) {
	unset( $autoremove_attachments['usage-restrictions'] );
}

$autoremove_attachments['onboarding-notice'] = 'hide';
$autoremove_attachments['additional-checks'] = 'enabled';

update_option( 'autoremove_attachments', $autoremove_attachments );
