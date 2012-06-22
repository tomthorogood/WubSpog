<?php

/*
 * Plugin Name: Hubspot Blog Reader
 * Plugin URI: http://www.tomthorogood.com
 * Description: Reads RSS feeds from HubSpot, based on tags.
 * Version: 0.1
 * Author: Tom Thorogood
 * Author URI: http://www.tomthorogood.com
 * License: GPLv3
 *
 * Note: This relies on Magpie RSS, available at magpierss.sourceforge.net
 */

require_once("style.php");
require_once("functions.php");
include("wubspog_config.php");

function show_posts_with_tag($_args)
{
    $defaults = Array(
        "tag" => "clearpoint"
    );
    $args = shortcode_atts($defaults, $_args);
    $feed = get_feed($args["tag"]);
    ob_start();
    getStyle();
    foreach ($feed->items as $item)
    {
        echo post_excerpt($item);
    }
    
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function show_blog_titles($_args)
{
    $defaults = Array(
        "tag" => "clearpoint",
        "limit" => 5
    );
    $args = shortcode_atts($defaults, $_args);
    $feed = get_feed($args["tag"]);
    $html = "<ul>";
    $_limit = 0;
    foreach ($feed->items as $item)
    {
        if ($_limit >= $args["limit"]) break;
        $html .= list_item_link($item);
        $_limit++;
    }
    $html .= "</ul>";
    return $html;
}

add_shortcode("hubspot_blogs", "show_posts_with_tag");
add_shortcode("hubspot_titles", "show_blog_titles");
?>
