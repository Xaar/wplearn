=== Ajax Content Renderer ===
Contributors: cbetancourt
Donate Link: http://betancourt.us/wordpress/plugins/ajax-content/
Tags: ajax-content-renderer json, xhr, ajax, jquery, extjs, prototype, httpxmlrequest
Requires at least: 2.7.1
Tested up to: 3.5.1
Stable tag: 1.3.3

== Description ==

This plugin detects Ajax requests and returns just the formatted body of the post or page.

== Installation ==

= Install =

1. Unzip plugin and upload the *betancourt-ajax-content* directory to your blog's *wp-content/plugins* directory.
1. Activate the plugin through the *Plugins* menu in WordPress.
1. That's it. Now you can request a post via Ajax. The response will be clean HTML containing the text of the post.

Please visit the forum for questions or comments: http://wordpress.org/tags/ajax-content-renderer/

== Frequently Asked Questions ==

== Screenshots ==

None

== Changelog ==

= 1.0.0 =

Initial release

= 1.1.0 =

* Minor change to evaluation of XHR headers. Avoids PHP warnings.
* Tested against WP 3.1.2

= 1.1.1 =

Updated documentation and added link to sample implementation.

= 1.1.0 =

Upgrade to avoid PHP warnings.

= 1.2.0 =

Cleaned up code.

= 1.3.0 =

Fixed bug reported by zulien on 30-Jun-2011. The plugin was only supporting single pages. It now supports single posts, pages and multi-post homepages.

= 1.3.1 =

= 1.3.2 =

* Tested on WordPress 3.5.1
* Updated installation instructions.

= 1.3.3 =

Corrected error in changelog.

== Upgrade Notice ==