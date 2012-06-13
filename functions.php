<?php
require_once("wubspog_config.php");
function build_rss_url($tag)
{
    include("wubspog_config.php");

    $formatted_tag = str_replace(" ", "+", $tag);

    $url = "http://$blog_domain/CMS/UI/Modules/BizBlogger/rss.aspx?";
    $url .= "tabid=$tabid&moduleid=$moduleid&tag=$formatted_tag";
    return $url;
}

function include_magpie()
{
    include("wubspog_config.php");
    assert($magpie_version !="");
    $include_dir = "magpierss-$magpie_version";
    $module = $include_dir . "/rss_fetch.inc";
    include($module);
}

function get_feed($tag)
{
    include_magpie();
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
    $image = stripStyle($image[0]);
    $ret = <<<HTML
\n\n
<div class="hubspot_blog_excerpt">
    <div class="hubspot image">$image</div>
    <a href="$link">$title</a><br/>
    <div class="hubspot blurb">
        $text_excerpt ...<a href="$link">[read more]</a>
    </div>
</div>
\n\n
HTML;
    return $ret;
}

function parse_image($html)
{
    preg_match_all("/<img[^>]+>/i", $html, $result);
    return $result[0];
}

function stripStyle($image)
{
    $styleTags = Array(
        "style",
        "height",
        "width",
        "float",
        "class",
        "id"
    );
    foreach ($styleTags as $tag)
    {
        $image = stripAttribute($tag,$image);
    }
    return $image;
}

function stripAttribute($attr, $str)
{
    $pattern = "/$attr=\"[^\"]*?\"/i";
    $str =preg_replace($pattern, "", $str);
    return $str;
}

function getStyle()
{
    include("style.php");
    echo $EXCERPT_STYLE;
}
    
    
function textBetween($tag, $html)
{
    $_pattern = $tag . "[^>]";
    $pattern = "/<$_pattern*?>(.*?)<\/$tag>/i";
    preg_match($pattern, $html, $results);
    return $results[1];
}

?>
