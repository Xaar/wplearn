=== Flowplayer Playlist ===

Contributors: eye8
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LT8MH5R8SGGMU
Plugin URI: http://eye8.me/flplaylist
Author URI: http://eye8.me
Tags: video, Flowplayer, Youtube, playlist
Requires at least: 3.2
Tested up to: 3.5.1
Stable tag: 0.25
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Flowplayer Playlist is a free plugin to embed video playlist in WordPress.

== Description ==

Flowplayer Playlist is a free plugin to embed video playlist in WordPress.

* Uses the open-source web video player Flowplayer (latest Flash-based version). Flowplayer package is upgradable when a new version is available.
* Supports FLV, MP4, and F4V video formats (all those supported by Flowplayer).
* Mix up Youtube videos with regular videos in the same playlist.
* Provide your Flowlayer license to use the Flowplayer commercial version. If no license key is provided, it will use the free version bearing the Flowplayer trademark.
* Flowplayer license supports multisite. Subdomains automatically inherit the license key from the main blog (if any). License key specified in the subdomain will overwrite the key from the main blog, allowing flexibility in larger WordPress blog network.

Right now you have to upload your videos to somewhere over the web with public access (e.g. the 'Public' folder in your Dropbox account) and use the public URLs to embed. But I am considering future features such as integrating the media library in WordPress or a Content Delivery Network (CDN) such as Dropbox or Box.net. Some other features in consideration:

* More Flowplayer configuration parameters such as background color, controlbar color, splash image, custom branding.
* Advertisement mode (repeated playback without controlbar).

Please make feature requests in the support section. I will decide which ones to implement first based the feedback.

== Installation ==

1. Upload the plugin package 'flplaylist' to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in Wordpress. A menu item called FlPlaylist will show up in the left menu.
3. Go to menu FlPlaylist->Add New, provide a title, choose a few options, and add one or more web video URLs to create a video playlist.
4. Publish the playlist. 
5. Use the shortcode beneath the Video URLs textbox in your post or page to embed the playlist.

== Frequently asked questions ==

= Where do I upload my videos? =

In v.0.1 you have to find your own content delivery service in order to use this plugin. A suggested service is Dropbox (free or paid) where you can upload your videos in the 'Public' folder and sync them over the web. Then you can use the publicly accessible URLs that Dropbox provided in this plugin.

= Can I use audio files in the playlist? =

Sorry not right now. But if you find this is really what you need please make a feature request.

= Is Flowplayer free to use for my website? =

Yes. Flowplayer is an open-source software and is absolutely free to use. However, the free version of Flowplayer bears a Flowplayer logo on the video. In order to remove that logo you have to purchase a Flowplayer license key (see next question).

= How can I remove the Flowplayer logo on the video? =

You need to purchase a [Flowplayer license key](http://flash.flowplayer.org/download/) and register that key for your Wordpress site. Then go to the Configuration page of the Flowplayer Playlist plugin and save the key. The key you purchased can only be used for the website you registered (including subdomains) on the Flowplayer website so don't worry that someone else might steal your key. Make sure to purchase the key for the Flash-based rather than the HTML5 version of Flowplayer.

= Does this plugin support iPad and iPhone? =

No. This plugin is Flash-based which is not supported by iOS devices. But future releases might add support for iOS devices.

== Screenshots ==

1. Each playlist is a custom post type. Create as many playlists as you like. 
2. Customize each playlist by specifying width, height, autoplay, and autobuffering. Add as many videos as you like (you can use Youtube videos!), each URL in a new line. Update the videos whenever you need to.
3. Save your Flowplayer license key to use the commercial player without the Flowplayer logo. Only one license key is needed for all multisites under the same root domain.
4. Use a simple shortcode to embed the playlist in any post or page.
5. Both Youtube and progressive download videos are supported.

== Changelog ==

= 0.25 =

* Fixed seeking bugs in Youtube plugin

= 0.2 =

* Mix up Youtube videos with progressive download videos
* Playlist prev/next controls in Flowplayer

= 0.1 =

First stable release. Features list:

* Video playlist post type
* Embed playlist with short code
* Provide Flowplayer license key to use the commercial player without logo
* Supports multisites. License specified in subdomain will override that in the main blog.

