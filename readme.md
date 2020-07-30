# Autoremove Attachments

Autoremove Attachments helps you keep the Media Library clean by deleting all media files attached as child attachments to a post, page, or custom post type when the parent is deleted.

By default, when you delete content from your website, regardless if it's a post, a page, a product, or any kind of post type, WordPress keeps the media files previously associated with it, even if after the removal of your content they are not used anywhere else.

Autoremove Attachments tries to solve this problem by automating the removal of all media files that have a child-parent relationship with the removed content. (so you don't have to manually track and remove orphan files left on your server)

## Important Considerations

- A soft delete that places your post, page, or custom post type in Trash will not trigger the removal of its child attachments. The purge happens when you empty your trash.
- When you delete a post, page or custom post type, we try to determine if its child attachments are used anywhere else on your website. If they are, we do not remove them, to prevent broken links.
- The additional checks before the automatic removal can be disabled from the Media Settings for improved performance on large websites with thousands of posts and media files.
- The plugin only removes files tracked by WordPress. Some poorly coded themes generate additional thumbnail sizes that are not tracked by WordPress and this always leads to orphan files left on your server.

## Compatibility and Third-Party Support

- [WooCommerce](https://wordpress.org/plugins/woocommerce)
- [Easy Digital Downloads](https://wordpress.org/plugins/easy-digital-downloads)
- All themes and plugins that do things the WordPress way

If you use a plugin to optimize and clean your database of revisions, trashed posts, etc, make sure you use one that relies on native WordPress functions to perform the maintenance tasks. Plugins we recommend:

- [WP-Sweep](https://wordpress.org/plugins/wp-sweep)


## Frequently Asked Questions

#### Does it work with custom post types?

Yes, it does. It works with posts, pages and custom post types. All child attachments are removed when the parent is deleted.

#### When are the attachments removed?

The files are removed when the parent post, page, or custom post type is permanently removed. A soft delete that places it in Trash will not trigger the removal of your attachments.

The purge happens when you empty your trash.

#### Can I control what attachments are removed?

Yes, you can. By default, all media files attached to a post, page or custom post type are removed automatically. If you need granular control you can use the filter `autoremove_attachments_allowed` to define custom rules for controlling when the child attachments should be removed automatically.

Here is an example:

```php
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
```

#### Are there any restrictions on how I can use my attachments?

Depending on how you use the plugin, the answer can be either yes, or no.

- **If you enable the additional checks** before the automatic removal of your attachments, you can use your media files in multiple locations without the fear of broken links caused by their removal.

- **If you disable the additional checks**, you need to make sure you don't reuse your media files between posts. If you do and the parent is deleted, you will end up with broken links in all the other locations where the attachment was used.

Regardless of how you use the plugin, if you need to use an attachment over and over again, upload it from the global Media Library menu. ( Media > Add New ) This way, it won't be attached as a child to a specific post, page, or custom post type and you will be able to use it without restrictions.
