=== PictureBook ===
Contributors: Doc4
Tags: blogroll, blogroll titles, blogroll images, links, link images, bookmarks, bookmark images
Requires at least: 2.3
Tested up to: 6.4.3
Stable tag: 1.4
License: GPL-2.0+
License URL: http://www.gnu.org/licenses/gpl-2.0.txt



== Description ==
Built for the sole purpose of displaying WordPress Bookmark Links that show both text and any attached icons. Utilizing the 'Links' tab ( WordPress 2.7 + up ), it is possible to display them as either a list of text links or a list of icon links but not both at the same time.

Enter PictureBook, a small plugin that provides the necessary code to list your Bookmarks with both text and icons. An example use case might be in the footer of a website that contains social bookmarking links, each link could show the name of the social media website and a small icon next to it just by entering your information into the WordPress 'Links' tab. Achieving this goal is possible with a little core manipulation but we thought it would be more productive to simply install a plugin and not worry about the next WordPress update.

The plugin code itself is just a re-functioning the current 'wp_list_bookmarks'. By altering the 'link_image' output to display the 'link_name', its possible to achieve the desired effect. The biggest benefit of this plugin is that a User is allowed to display their Bookmarks in three separate formats: text-only, image-only and a combination of both.

Please note that as of WordPress 3.5 the 'Links' manager has been deprecated and removed from the Dashboard. If you currently have links this option will still be available. If not, we recommend the 'Link Manager' plugin from WordPress which is required to run PictureBook. Download here: https://wordpress.org/plugins/link-manager/

In the example (see the Installation tab), we are using the standard WordPress 'wp_list_bookmarks' formatting with a new function name 'wp_list_picturebook'. All parameters associated with the original tag are functional. The code shown here will display a list of Bookmarks with both text and images, no headline, and only list those Bookmarks from 'Category 4'. WordPress auto adds our line item tags and therefore they do not need to be included.

= Plugin URL =
[PictureBook](https://doc4design.com/picturebook/)

= Screenshots =
[View Screenshots](https://doc4design.com/picturebook/)



== Installation ==

To install the plugin just follow these simple steps:

1. Download the plugin and expand it.
2. Copy the picturebook folder into your plugins folder ( wp-content/plugins ).
3. Log-in to the WordPress administration panel and visit the Plugins page.
4. Locate the PictureBook plugin and click on the activate link.
5. Replace wp_list_bookmarks with wp_list_picturebook

Ex: See [PictureBook](https://doc4design.com/picturebook/)

<code><div id="links">
<ul><?php wp_list_picturebook('title_li=&categorize=0&category=4'); ?></ul>
</div></code>


== Frequently Asked Questions ==

= Can't I do this with WordPress? =

With more recent versions of WordPress using the following code will achieve the same effect by way of "show_images=TRUE&show_name=TRUE":

<code><?php wp_list_bookmarks('title_li=&categorize=0&category=4&show_images=TRUE&show_name=TRUE'); ?></code>


== Changelog ==

= 1.4 =
* Updated code to ensure functionality with WordPress 6.4.3+
* Updated Required Headers for readme.txt
* Updated Required Headers for picturebook.php

= 1.3 =
* Updated code to ensure functionality with WordPress 6.1.1+
* Additional instructions included for usage