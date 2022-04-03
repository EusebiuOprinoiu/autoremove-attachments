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
- Removed the old 'autoremove_attachments_post_types' filter in favour of the new one - instructions available in FAQ

#### Version 1.1.1
- New filter added to allow developers to change for what post types the child attachments should be removed automatically
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
- Remove all traces of the plugin, even on multisite installs

#### Version 1.0.3
- Added a security check to avoid deleting attachments when the ID of the parent post is invalid

#### Version 1.0.2
- Fixed a bug that was causing media files to be removed when revisions were deleted with wp-cron

#### Version 1.0.1
- Minimum required version of PHP set to 5.3

#### Version 1.0.0
- First release
