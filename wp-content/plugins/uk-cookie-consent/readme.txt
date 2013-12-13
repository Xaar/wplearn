=== Plugin Name ===
Contributors: catapult, husobj
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=M2FLCU6Z4A2LA
Tags: cookies, eu, cookie law, implied consent, uk cookie consent
Requires at least: 3.5.0
Tested up to: 3.8
Stable tag: 1.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Shows an unobtrusive yet clear message to users that your site uses cookies.

== Description ==

We think this is the simplest but most effective method of dealing with the legislation.

The plug-in is a straightforward approach to help you comply with the UK interpretation of the EU regulations regarding usage of website cookies. It follows the notion of "implied consent" as described by the UK's Information Commissioner and makes the assumption that most users who choose not to accept cookies will do so for all websites. A user to your site is presented with a clear yet unobtrusive notification that the site is using cookies and may then acknowledge and dismiss the notification or click to find out more. The plug-in automatically creates a new page with pre-populated information on cookies and how to disable them, which you may edit further if you wish.

Importantly, the plug-in does not disable cookies on your site or prevent the user from continuing to browse the site. Several plug-ins have adopted the "explicit consent" approach which obliges users to opt in to cookies on your site. This is likely to deter visitors.

== Installation ==

1. Upload the `uk-cookie-consent` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Browse to the Cookie Consent option page in Settings to configure

== Frequently Asked Questions ==

= Where can I find out more about this plug-in? =

You can find out more about the plug-in on [its plug-in page](http://catapultdesign.co.uk/plugin/uk-cookie-consent/).

= Does this definitely cover me under the legislation? =

You have to make up your own mind about that or consult a legal expert.

= Where can I find out more about the UK laws regarding cookies? =

We have written a short article on [our interpretation of the UK cookie law](http://catapultdesign.co.uk/uk-cookie-consent/). This outlines some of the background to the regulations and the reasons for choosing the 'implied consent' method.

You will find more details of the regulations on the [Information Commissioner's Office site](http://www.ico.gov.uk/for_organisations/privacy_and_electronic_communications/the_guide/cookies.aspx).

== Screenshots ==

1. The plug-in places an unobtrusive notification at the top of the page. The user can acknowledge and dismiss the notification by clicking the green button - this is consent for the use of cookies. Alternatively, the user can click the link for more information to be directed to a pre-populated page describing what cookies are and how to disable them.

1. Simple settings page allows you to modify the message and button text.

== Changelog ==

= 1.7.1 =
* Ready for WP 3.8

= 1.7 =
* Updates to settings page

= 1.6 =
* Moved JS to footer (thanks to Andreas Larsen for the suggestion)

= 1.5 =
* Switched the logic so that the bar is initially hidden on the page and only displays if user has not previously dismissed it.
* Gives a slightly better performance.
* Thanks to chrisHe for the suggestion.

= 1.4.2. =
* Policy page created on register_activation_hook now

= 1.4.1 =
* Tweak to ensure jQuery is a dependency

= 1.4 =
* This plug-in now uses JavaScript to test whether the user has dismissed the front-end notification in order to solve issues with caching plug-ins.
* Added configuration options for colour and position of bar.
* Set notification button and link to first element in tab list.
* Thanks to husobj for contributions and suggestions including localisation and enqueueing scripts and stylesheets

= 1.3 =
* Reinstated user-defined permalink field

= 1.25 =
* Minor admin update

= 1.24 =
* Fixed text alignment issue with Thesis framework (thanks to cavnit for pointing this one out)

= 1.23 =
* Minor admin update

= 1.22 =
* Minor admin update

= 1.21 =
* Added resources to Settings page

= 1.2 =
* Change title of Cookies page to Cookie Policy and removed option to change title
* Added trailing slash to Cookie Policy url (thanks to mikeotgaar for spotting this)

= 1.1 =
* Added default text to messages

== Upgrade Notice ==

Recommended