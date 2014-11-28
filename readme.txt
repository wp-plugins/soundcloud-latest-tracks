=== Soundcloud Latest Tracks ===
Plugin Name: Soundcloud Latest Tracks
Contributors: ph08n1x
Author URI: http://campbell-designs.com
Author: Campbell Designs
Donate link: http://campbell-designs.com/donate
Tags: soundcloud, music, tracks
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin simply allows you to choose a Soundcloud user and display an x amount of latest tracks from that user using a nice lil' shortcode.

== Description ==

This plugin is to fill the hole in the Wordpress plugin directory where users wish to show their latest X amount of tracks on X amount of rows each row being an embedded Soundcloud player.

The user will also have the option to add a "hear more" button to get the next X amount of tracks from the chosen Soundcloud user.

The reason I find this better than embedding a single Soundcloud player (that plays multiple tracks) is that users will be interacting more with the site instead of heading off to Soundcloud and also get a better sense of your website's style with my plugin rather than a player you slapped on the site. 

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `soundcloud-latest-tracks` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Now you can use the shortcode in any piece of text on the site:

	[soundcloud_latest_tracks user="YOUR_SOUNDCLOUD_USER_ID" show="NUMBER_OF_TRACKS_SHOWN" hear_more="YES_OR_NO"]


Shortcode options:
- user: The Soundcloud user name that can be found in the URL of the user's Soundcloud account.
- show: The number of tracks to show of that user (showing the newest to the oldest).
- hear_more: Show/hide a "hear more" button that will get the next 'show' amount of tracks for the given user.
- show_comments: Show/hide comments on the SoundCloud player.
- visual: Use the visual SoundCloud player (artwork is used as a background for the player) or the normal HTML5 player.


You can also use the widget name "Soundcloud Latest Tracks Widget" and fill in the same attributes as above.


== Upgrade Notice ==
= 1.1 =
* More options for the plugin to customise the SoundCloud player more
== Screenshots ==
1. The SoundCloud Latest Tracks shortcode in action:
== Changelog ==
= 1.1 =
* Added a visual option to change the type of SoundCloud player chosen.
* Shortcode and widget can be used multiple time on the same page now.
* Fixed bug where hear more button breaks when no more tracks to fetch.
== Frequently Asked Questions ==
No one has asked anything yet but leave a ticket and I'll try and get back to you ASAP.
== Donations ==
Still need to set this up!