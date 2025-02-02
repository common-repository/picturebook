<?php
/*
Plugin Name: PictureBook
Plugin URI: https://doc4design.com/picturebook/
Description: Display your blogroll links with both text and images
Version: 1.4
Requires at least: 2.7
Author: Doc4
Author URI: https://doc4design.com/
License: GPL v2.0 or later
License URL: https://www.gnu.org/licenses/gpl-2.0.html
*/

/******************************************************************************

Copyright 2008 - 2024  Doc4 : info@doc4design.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The license is also available at https://www.gnu.org/licenses/gpl-2.0.html

*********************************************************************************/

function _picturebook($bookmarkspicture, $args = '' ) {
    $defaults = array(
        'show_updated' => 0, 'show_description' => 0,
        'show_images' => 1, 'show_name' => 0,
        'before' => '<li>', 'after' => '</li>', 'between' => "\n",
        'show_rating' => 0, 'link_before' => '', 'link_after' => ''
    );

    $r = wp_parse_args( $args, $defaults );
    extract( $r, EXTR_SKIP );

    $output = ''; // Blank string to start with.

    foreach ( (array) $bookmarkspicture as $bookmark ) {
        if ( !isset($bookmark->recently_updated) )
            $bookmark->recently_updated = false;
        $output .= $before;
        if ( $show_updated && $bookmark->recently_updated )
            $output .= get_option('links_recently_updated_prepend');

        $the_link = '#';
        if ( !empty($bookmark->link_url) )
            $the_link = clean_url($bookmark->link_url);

        $rel = $bookmark->link_rel;
        if ( '' != $rel )
            $rel = ' rel="' . $rel . '"';

        $desc = attribute_escape(sanitize_bookmark_field('link_description', $bookmark->link_description, $bookmark->link_id, 'display'));
        $name = attribute_escape(sanitize_bookmark_field('link_name', $bookmark->link_name, $bookmark->link_id, 'display'));
         $title = $desc;

        if ( $show_updated )
            if ( '00' != substr($bookmark->link_updated_f, 0, 2) ) {
                $title .= ' (';
                $title .= sprintf(__('Last updated: %s'), date(get_option('links_updated_date_format'), $bookmark->link_updated_f + (get_option('gmt_offset') * 3600)));
                $title .= ')';
            }

        if ( '' != $title )
            $title = ' title="' . $title . '"';

        $alt = ' alt="' . $name . '"';

        $target = $bookmark->link_target;
        if ( '' != $target )
            $target = ' target="' . $target . '"';

        $output .= '<a href="' . $the_link . '"' . $rel . $title . $target. '>';

        $output .= $link_before;

        if ( $bookmark->link_image != null && $show_images ) {
            if ( strpos($bookmark->link_image, 'http') !== false )
                $output .= "<img src=\"$bookmark->link_image\" $alt $title border=\"0\" />" . "&nbsp;&nbsp;&nbsp;" . $name;
            else // If it's a relative path
                $output .="<img src=\"" . get_option('siteurl') . "$bookmark->link_image\" $alt $title border=\"0\" />" . "&nbsp;&nbsp;&nbsp;" . $name;

            if ($show_name) $output .= $name;
        } else {
            $output .= $name;
        }

        $output .= $link_after;

        $output .= '</a>';

        if ( $show_updated && $bookmark->recently_updated )
            $output .= get_option('links_recently_updated_append');

        if ( $show_description && '' != $desc )
            $output .= $between . $desc;

        if ($show_rating) {
            $output .= $between . sanitize_bookmark_field('link_rating', $bookmark->link_rating, $bookmark->link_id, 'display');
        }

        $output .= "$after\n";
    } // end while

    return $output;
}


function wp_list_picturebook($args = '') {
    $defaults = array(
        'orderby' => 'name', 'order' => 'ASC',
        'limit' => -1, 'category' => '', 'exclude_category' => '',
        'category_name' => '', 'hide_invisible' => 1,
        'show_updated' => 0, 'echo' => 1,
        'categorize' => 1, 'title_li' => __('Bookmarks'),
        'title_before' => '<h2>', 'title_after' => '</h2>',
        'category_orderby' => 'name', 'category_order' => 'ASC',
        'class' => 'linkcat', 'category_before' => '<li id="%id" class="%class">',
        'category_after' => '</li>'
    );

    $r = wp_parse_args( $args, $defaults );
    extract( $r, EXTR_SKIP );

    $output = '';

    if ( $categorize ) {
        //Split the bookmarks into ul's for each category
        $cats = get_terms('link_category', array('name__like' => $category_name, 'include' => $category, 'exclude' => $exclude_category, 'orderby' => $category_orderby, 'order' => $category_order, 'hierarchical' => 0));

        foreach ( (array) $cats as $cat ) {
            $params = array_merge($r, array('category'=>$cat->term_id));
            $bookmarkspicture = get_bookmarks($params);
            if ( empty($bookmarkspicture) )
                continue;
            $output .= str_replace(array('%id', '%class'), array("linkcat-$cat->term_id", $class), $category_before);
            $catname = apply_filters( "link_category", $cat->name );
            $output .= "$title_before$catname$title_after\n\t<ul class='xoxo blogroll'>\n";
            $output .= _picturebook($bookmarkspicture, $r);
            $output .= "\n\t</ul>\n$category_after\n";
        }
    } else {
        //output one single list using title_li for the title
        $bookmarkspicture = get_bookmarks($r);

        if ( !empty($bookmarkspicture) ) {
            if ( !empty( $title_li ) ){
                $output .= str_replace(array('%id', '%class'), array("linkcat-$category", $class), $category_before);
                $output .= "$title_before$title_li$title_after\n\t<ul class='xoxo blogroll'>\n";
                $output .= _picturebook($bookmarkspicture, $r);
                $output .= "\n\t</ul>\n$category_after\n";
            } else {
                $output .= _picturebook($bookmarkspicture, $r);
            }
        }
    }

    $output = apply_filters( 'wp_list_picturebook', $output );

    if ( !$echo )
        return $output;
    echo $output;
}

?>
