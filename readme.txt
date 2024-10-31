=== SF Archiver ===
Contributors: GregLone
Tags: content, archive, post types
Requires at least: 4.4
Tested up to: 4.5
Stable tag: trunk
License: GPLv3
License URI: https://www.screenfeed.fr/gpl-v3.txt

Add some small and useful utilities for managing your Custom Post Types archives.

== Description ==
Historically, the main purpose of SF Archiver was to allow users to simply add links in the menus to any Custom Post Type archive.  
Since WordPress 4.4 adds this feature, it has been removed from SF Archiver version 3.0 (see the "Developers" tab for older versions). Still, SF Archiver provides some other cool stuff:

1. Set the number of posts per page to list for each post type.
1. Quickly visit your archives by using a handy link in your administration area (in your posts list, see the icon between the title and the "Add New" button).
1. Small quirk: when displaying one of your Custom Post Type single, "highlight" the corresponding archive menu item (WordPress does the same for the Posts when they are not displayed on the front page).
1. Small WordPress bug fix: WordPress wrongly "highlights" the Blog menu item when a Custom Post Type single or archive is displayed.

= Translations =
* English
* French

== Installation ==

1. Extract the plugin folder from the downloaded ZIP file.
1. Upload SF Archiver folder to your `/wp-content/plugins/` directory.
1. Activate the plugin from the "Plugins" page.
1. Go to *Settings* -> *Reading* and set the number of posts per page for each Custom Post Type.

== Frequently Asked Questions ==
= Why some of my Custom Post Types don't appear in the settings? =
They're probably not public, or do not have an archive page.

Eventually, check out [my blog](https://www.screenfeed.fr/plugin-wp/sf-archiver/) for more infos or tips (sorry guys, it's in French, but feel free to leave a comment in English).

== Screenshots ==
1. The settings, at the bottom of the "Reading Settings" page.
3. A link to the post type archive page in the administration area.

== Changelog ==

= 3.0.2 =
* 2016/04/03
* Tested on WP 4.5.
* Improved code quality.

= 3.0.1 =
* 2015/12/09
* Bugfix `Call to undefined function register_setting()`. `register_setting()`, I so hate you ಠ_ಠ.

= 3.0.0 =
* 2015/11/08
* This version is compatible only with WordPress 4.4 and superior.
* WordPress 4.4 enables the possibility to add links to the Custom Post Type archives in the menu, so let's get rid of our useless metabox. You will find these items in *Appearance* -> *Menus* -> *YOUR-CUSTOM-POST-TYPE* -> *View All* -> *All YOUR-CUSTOM-POST-TYPE*.
* When upgrading to version 3, your SF Archiver menu items will be converted to the new WordPress 4.4 menu items. You won't need to do anything :)

= 2.2.1 =
* 2015/11/08
* Next version will be compatible only with WordPress 4.4.
* Bugfix in uninstall process.
* Removed back-compat code.

= 2.2.0 =
* 2015/11/07
* New: migration process to get rid of old data.
* New: removed back-compat code.
* Improvement: better uninstallation.
* Bugfix: removed unused parameters in some filters.

= 2.1.1 =
* 2015/09/27
* Bugfix: the world fell apart. Sorry everybody, a silly typo during code cleanup and everything broke. ( ; Д ; )

= 2.1 =
* 2015/09/20
* New: use the CSS class "current_page_parent" on post type archive menu items when displaying a post type singular.
* Bugfix: stop WordPress to be confused between post type archive menu items and blog menu item.
* Code cleanup.

= 2.0.1 =
* 2015/08/05
* New: ready for the new WordPress 4.3 headings in admin screens.

= 2.0 =
* 2015/05/25
* Trash it, rebuild it: this version is a complete rewrite.
* **PLEASE NOTE BEFORE UPDATING**: some features have been removed. No URL customization, RSS feed, nor archive page activation anymore. The "Posts per page" setting still remains though.
* **WordPress 3.5+ is now required.**
* The settings are now located in `Settings` -> `Reading`.
* The box in `Appearance` -> `Menus` is still there and I got rid of some old bugs.
* New: in the administration area, now you can find a link to your Post Types archives (look at the title when visiting `wp-admin/edit.php?post_type=my-cpt`).

= 1.1.3 =
* 2013/09/13
* Small security fix.

= 1.1.2 =
* 2012/12/04
* Small change for WP 3.5 compatibility.
* Code improvements.
* Use string as domain for gettext.

= 1.1.1 =
* 2012/08/14
* Small bugfix due to some core changes in WP 3.4.1.

= 1.1 =
* 2012/03/08
* Meta box rebuild in nav menu admin page. Delete your old archive links in your menus and add them again. Now you won't need to change them again if you change the archives slug.
* Minor changes in French translation.

= 1.0 =
* 2012/02/24
* First public release

== Upgrade Notice ==

Meh
