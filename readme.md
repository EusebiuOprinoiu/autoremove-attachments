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

If you use a plugin to optimize and clean your database of revisions, trashed posts, etc, make sure you use one that relies on native WordPress functions to perform the maintenance tasks. We recommend [WP-Sweep](https://wordpress.org/plugins/wp-sweep).
