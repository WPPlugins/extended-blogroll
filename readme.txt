=== Extended Blogroll ===
Contributors: ppfeufer
Donate link:
Tags: blogroll, bookmarks, links, last post, recent post, thumbnails, feed, widget, sidebar widget, atom, rss, sidebar, random, nofollow, shortcode
Requires at least: 2.9.2
Tested up to: 3.4-alpha-19978
Stable tag: 1.2

Displays your blogroll links via RSS Feeds in a customizable sidebar widget and provides also a shortcode for displaying in pages and articles.

== Description ==

This Wordpress Widget allows you to display the recent posts of your blogroll links via RSS Feeds as a sidebar widget or via shortcode `&#91;blogroll&#93;` in any post or article on every position you want to.
The Plugin works without Javascript and without AJAX. It uses the Wordpress standard links database and honors the
visible and target settings as defined for each link (private links are not shown, links are displayed in the same or
in a new window as specified). The Plugin is easy to install, the Widget is simple to use and highly customizable.
You can simply switch on / off, select or type in the various configurations and settings.

This is based on Blogroll Widget with RSS Feed Plugin from CrazyGirl.

You can configure this Widget in the Wordpress Appearance Widgets SubPanel as follows and the shortcode directly from the Menu "Blogroll":

* add an own title to the sidebar widget
* define how many items you want to display
* choose the link category of the items (all links or one of your link categories)
* select the item order (link name ascending, link name descending, link id ascending, link id descending, random order)
* show the images entered to the respective links or let the plugin generate website thumbnails (via m-software.de)
* define the image size
* show blogroll links
* add the 'rel=nofollow' attribute to the blogroll links
* define how many feed post links you want to display
* choose if you want to shorten the feed post link text and define the length in characters
* add the 'rel=nofollow' attribute to the feed post links
* show feed post excerpts
* define how many characters of the feed post excerpt you want to display

Before using the Extended Blogroll Widget with RSS Feeds make sure, that you have entered the right RSS Addresses to your links in the Links
Subpanel. Otherwise this Plugin will not work correctly. No item is shown when no RSS Address is entered! With this you
have a further possibility to configure the Widget output. When you do not enter a RSS Address to a link, it will not be
displayed in the Widget.

= Available Languages =

* German
* English

== Installation ==

1. Unzip the ZIP plugin file
2. Copy the `extended-blogroll` folder into your `wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to the Appearance Widgets SubPanel to add the Blogroll Widget with RSS Feeds to your sidebar and configure it.
5. Go to the Blogroll Menu and configure the shortcode for showing your blogroll in articles and/or pages.

== Frequently Asked Questions ==

= Where do I enter the RSS Addresses? =

1. Go to the `Links` Panel
2. Click `Edit` at the respective Link
3. Scroll down to the `Advanced` Box
4. In the field `RSS Address` type the respective RSS Address e.g. `http://blog.ppfeufer.de/feed/`
5. Be careful with the trailing slash / at the end of the RSS Address! A feed without trailing slash need to be entered
without it, a feed with trailing slash need to be entered with it. Any mistake can cause that no
feed post is shown in the respective item of Blogroll RSS Widget.


= Why is there an empty place for the feed post of a blogroll link in the Widget output? =

An empty place for the feed post of a blogroll link reports a wrong entered RSS Address. I rather decided to leave the
output empty in this cases than showing up an error message. An error message is not a lucky solution for your visitors.
Go to the details of the respective blogroll link and correct the RSS Address.


= Why is the link *Thumbnails by M-Software.de* displayed at the bottom of my Blogroll RSS Widget? =

If you have chosen the option `Create and show thumbnails` in the Blogroll Widget with RSS Feeds, the thumbnails are generated
automatically via m-software.de. This service is free of charge. As condition for using this service the
link *Thumbnails by M-Software.de* has to be displayed.
Alternatively to `Create and show thumbnails` you can choose `Show my own images` and enter your own images to the respective links.


= Where can I enter my own images to blogroll links? =

1. Go to the `Links` Panel
2. Click `Edit` at the respective Link
3. Scroll down to the `Advanced` Box
4. In the field `Image Address` type the respective Address of the image. If you do not have own images you could enter here the
gravatar address of the autohr, e.g. http://www.gravatar.com/avatar/ef5de17b226669a2a7a335ea8167e29d


== Screenshots ==

1. Widget-Settings
2. Shortcode-Settings


== Changelog ==

= 1.2 =
* (27.02.2011)
* Moved CSS from external file to inline. Loads faster.

= 1.1.6 =
* (09.11.2011)
* Ready for WordPress 3.3

= 1.1.5 =
* (09.02.2011)
* Fix: hide widget-titlebar if its empty.

= 1.1.4 =
* (06.02.2011)
* Fix: Warning if a feed could not be fetched -> fixed (thx to <a href="http://www.chaosweib.com/">Chaosweib</a>)

= 1.1.3 =
* (23.01.2011)
* Fix: corrected CSS for compatibility with older themes.

= 1.1.2 =
* (09.01.2011)
* Fix: corrected loading of JavaScript after saving action in widget settings.
* Update: translation

= 1.1.1 =
* (08.01.2011)
 * Update: JavaScript

= 1.1.0 =
* Test: Ready for WordPress 3.1 (Tested on WP 3.1-RC1)
* Added: Flattr Button for some support :-)

= 1.0.3 =
* Replaced deprecated function for language support with new one

= 1.0.2 =
* Removed obsolete function
* Major code cleanup

= 1.0.1 =
* Fixed a little bug with the shortcode (thanks <a href="http://www.chaosweib.com">Chaosweib</a>)

= 1.0.0 =
* Initial release

== Upgrade Notice ==

Just upgrade
