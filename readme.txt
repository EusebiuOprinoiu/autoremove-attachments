# Autoremove Attachments

Contributors:      PolygonThemes, EusebiuOprinoiu
Tags:              attachment, media, post, page, custom post type
Stable tag:        trunk
Requires PHP:      7.2
Tested up to:      5.9
Requires at least: 5.0
License:           GPLv3 or later
License URI:       https://choosealicense.com/licenses/gpl-3.0
Donate link:       https://paypal.me/EusebiuOprinoiu

Remove child attachments when parent post, page or custom post type is deleted.

## Description

Autoremove Attachments helps you keep the Media Library clean by deleting all media files attached as child attachments to a post, page, or custom post type when the parent is deleted.

By default, when you delete content from your website, regardless if it's a post, a page, a product, or any kind of post type, WordPress keeps the media files previously associated with it, even if after the removal of your content they are not used anywhere else.

Autoremove Attachments tries to solve this problem by automating the removal of all media files that have a child-parent relationship with the removed content. (so you don't have to manually track and remove orphan files left on your server)

### Important Considerations

- A soft delete that places your post, page, or custom post type in Trash will not trigger the removal of its child attachments. The purge happens when you empty your trash.
- When you delete a post, page or custom post type, we try to determine if its child attachments are used anywhere else on your website. If they are, we do not remove them, to prevent broken links.
- The additional checks before the automatic removal can be disabled from the Media Settings for improved performance on large websites with thousands of posts and media files.
- The plugin only removes files tracked by WordPress. Some poorly coded themes generate additional thumbnail sizes that are not tracked by WordPress and this always leads to orphan files left on your server.

### Compatibility and Third-Party Support

- [WooCommerce](https://wordpress.org/plugins/woocommerce)
- [Easy Digital Downloads](https://wordpress.org/plugins/easy-digital-downloads)
- All themes and plugins that do things the WordPress way

If you use a plugin to optimize and clean your database of revisions, trashed posts, etc, make sure you use one that relies on native WordPress functions to perform the maintenance tasks. We recommend [WP-Sweep](https://wordpress.org/plugins/wp-sweep).

## Installation

### Automatic Installation

The automatic installation is the easiest option to install a plugin as WordPress handles the file transfers itself. To do an automatic install, log in to your WordPress dashboard and follow the steps below:

1. Navigate to the Plugins menu and click "Add New"
2. In the search field type "Autoremove Attachments" and click "Search Plugins"
3. Once you have found the plugin install it by clicking "Install Now"
4. Activate "Autoremove Attachments" from the "Plugins" menu

### Manual Installation

The manual installation method involves downloading the plugin and uploading it on your server via SFTP. To do a manual install follow the steps below:

1. Download the plugin to your local computer
2. If downloaded as a zip archive extract it to your Desktop
3. Upload the plugin folder to the /wp-content/plugins/ directory
4. Activate "Autoremove Attachments" from the "Plugins" menu

## Frequently Asked Questions

### Does it work with custom post types?

Yes, it does. It works with posts, pages and custom post types. All child attachments are removed when the parent is deleted.

### When are the attachments removed?

The files are removed when the parent post, page, or custom post type is permanently removed. A soft delete that places it in Trash will not trigger the removal of your attachments.

The purge happens when you empty your trash.

### Can I control what attachments are removed?

Yes, you can. By default, all media files attached to a post, page or custom post type are removed automatically. If you need granular control you can use the filter `autoremove_attachments_allowed` to define custom rules for controlling when the child attachments should be removed automatically.

Here is an example on how you can remove the attachments only for specific custom post types:

`
function autoremove_attachments_custom_rule() {
	// Global variables.
	global $post_id;

	// Variables.
	$post_type          = get_post_type( $post_id );
	$allowed_post_types = array(
		'project',
		'product',
	);

	// Default return value.
	$allowed_to_remove = false;



	// Custom rules for removing attachments.
	if ( in_array( $post_type, $allowed_post_types ) ) {
		$allowed_to_remove = true;
	}



	// Return.
	return $allowed_to_remove;
}
add_filter( 'autoremove_attachments_allowed', 'autoremove_attachments_custom_rule' );
`

The returned value should be true for the cases in which you want the attachments removed. (false otherwise)

### Are there any restrictions on how I can use my attachments?

Depending on how you use the plugin, the answer can be either yes, or no.

If you keep the plugin running with the additional checks enabled, you have no restrictions on how you can use your attachments. (you can use your media files in multiple locations, without the fear of broken links caused by their removal)

If you disable the additional checks, you need to make sure you don't reuse your media files between posts. If you do and the parent is deleted, you will end up with broken links in all the other locations where the removed attachments were used.

Regardless of how you use the plugin, if you need to use an attachment over and over again, upload it from the global Media Library menu. ( Media > Add New ) This way, it won't be attached as a child to a specific post, page, or custom post type and you will be able to use it without restrictions.

## Changelog

#### Version 1.3.1
- Minor improvements to admin notices

#### Version 1.3.0
- Minor code refactoring
- Language files updated

#### Version 1.2.1
- Fixed a bug that blocked changes to image sizes under Settings > Media

#### Version 1.2.0
- Minor code refactoring
- Extended support for WooCommerce and Easy Digital Downloads.
- New safety checks when you empty your trash - We now try to determine if the child attachments marked for removal are used anywhere else on your website. If they are, we do not remove them, to prevent broken links.
- New option under Settings > Media to disable the extra checks for improved performance on large websites with thousands of posts and media files. (where you might experience slow queries and timeouts when you empty your trash)

**Other important considerations**

- With the new safety checks enabled it's fairly safe to reuse attachments between posts. But please, keep in mind, they are not fool-proof, especially if you store media file IDs or URLs in custom fields. That's why we recommend you avoid reusing your attachments between posts, if possible.
- If you need to use an attachment over and over again, upload it from the global Media Library menu ( Media > Add New ). This way, it won't be attached as a child to a specific post, page, or custom post type.

#### Version 1.1.2
- New filter added to allow developers define custom rules for controlling when the child attachments should be removed automatically
- Removed the old 'autoremove_attachments_post_types' filter in favor of the new one - instructions available in FAQ

#### Version 1.1.1
- New filter added to allow developers to change for what post types the child attachments should be removed automatically ( see FAQ for instructions )
- Fixed a minor incompatibility with WP-Sweep

#### Version 1.1.0
- Minor improvements for the admin notice

#### Version 1.0.9
- Performance improvements on websites with a large number of posts and attachments

#### Version 1.0.8
- Added extra security checks before the removal of attachments
- Added an admin notice with a warning about the limitations of this plugin and the consequences of its improper usage. ( for new users only )

#### Version 1.0.7
- Minor code refactoring
- Added full support for WordPress Multisite

#### Version 1.0.6
- Code refactored using wpcs

#### Version 1.0.5
- Improved the warning displayed when very old PHP versions are used

#### Version 1.0.4
- Remove all options on uninstall, even for multisite

#### Version 1.0.3
- Added a security check to avoid deleting attachments when the ID of the parent post is invalid

#### Version 1.0.2
- Fixed a bug that was causing media files to be removed when revisions were deleted with wp-cron

#### Version 1.0.1
- Minimum required version of PHP set to 5.3

#### Version 1.0.0
- First release
