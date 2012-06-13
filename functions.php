<?php
require_once("config.php");
function build_rss_url($tag)
{
    global $blog_domain;
    global $moduleid;
    global $tabid;

    $formatted_tag = str_replace(" ", "+", $tag);

    $url = "http://$blog_domain/CMS/UI/Modules/BizBlogger/rss.aspx?";
    $url .= "tabid=$tabid&moduleid=$moduleid&tag=$formatted_tag";
    return $url;
}

function include_magpie()
{
    global $magpie_version;
    $include_dir = "magpierss-$magpie_version";
    $module = $include_dir . "/rss_fetch.inc";
    require_once($module);
}

function get_feed($tag)
{
    $url = build_rss_url($tag);
    $rss = fetch_rss($url);
    //print_r($rss);
    return $rss;
}

function post_excerpt($item)
{
    $title = $item["title"];
    $link = $item["link"];
    $text_only = textBetween("p", $item["description"]);
    $image = parse_image($item["description"]);
    $text_excerpt = str_replace($image, "", $text_only);
    $text_excerpt = substr($text_excerpt, 0, 140);
    $last_space = strrpos($text_excerpt, " ", -1);
    $text_excerpt = substr($text_excerpt, 0, $last_space);
    $ret = <<<HTML
<div class="hubspot_blog_excerpt">
<a href="$link">$title</a><br/>
$image
$text_excerpt ...<a href="$link">[read more]</a>
</div>
HTML;
    return $ret;
}

function parse_image($html)
{
    preg_match_all("/<img[^>]+>/i", $html, $result);
    return $result[0];
}

function textBetween($tag, $html)
{
    $_pattern = $tag . "[^>]";
    $pattern = "/<$_pattern*?>(.*?)<\/$tag>/i";
    preg_match($pattern, $html, $results);
    return $results[1];
}


?>


