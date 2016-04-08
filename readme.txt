=== Sticky Post Cleanup ===
Contributors: norcross
Website Link: https://github.com/norcross/sticky-post-cleanup
Donate link: https://andrewnorcross.com/donate
Tags: sticky posts
Requires at least: 4.4
Tested up to: 4.5
Stable tag: 0.0.1
License: MIT
License URI: http://norcross.mit-license.org/

Set an automatic expiration date to sticky posts.

== Description ==

Set an automatic expiration date to sticky posts via cron job. Also allows to set a maximum number of sticky posts to have on a site.

Features

* runs a twice-daily cron job (which can be changed via filter) to check for sticky posts past the allowed range.
* checks on each save post to make sure we don't have more than the max allowed.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `sticky-post-cleanup` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to the "Writing" settings and set your total number of days and maximum posts.

== Frequently Asked Questions ==

= Why do I need this? =

Because you're still using sticky posts for some reason.


== Screenshots ==


== Changelog ==

= 0.0.1 - 2016/04/08
* Initial release.


== Upgrade Notice ==

= 0.0.1 =
Initial release
