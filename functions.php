<?php
function build_rss_url($tag)
{
    include("wubspog_config.php");
    
    // Replaces spaces with '+' signs to be compliant with HubSpot's application.
    $formatted_tag = str_replace(" ", "+", $tag);

    /* The standard HubSpot URL, plus the attributes given by the user. */
    $url = "http://$blog_domain/CMS/UI/Modules/BizBlogger/rss.aspx?";
    $url .= "tabid=$tabid&moduleid=$moduleid&tag=$formatted_tag";
    return $url;
}

function include_magpie()
{
    /* Fetches the Magpie RSS feed reader. */
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
    return $rss;
}

function post_excerpt($item)
{
    
    $title = $item["title"];    // The blog post title
    $link = $item["link"];      // The link to the blog post
    $date_array = explode(" ", $item["pubdate"]);
    $date = "{$date_array[2]} {$date_array[1]}, {$date_array[3]}";
    $text_only = textBetween("p", $item["description"]);    // Everything between the <p>tags</p>
    $image = parse_image($item["description"]); // The first image found in the blog entry
    $text_excerpt = str_replace($image, "", $text_only);    // The 'text_only', with the image extracted
    $text_excerpt = substr($text_excerpt, 0, 140);  // Only the first 140 characters of the text
    $last_space = strrpos($text_excerpt, " ", -1);  // Don't cut off text in the middle of a word!
    $text_excerpt = substr($text_excerpt, 0, $last_space);
    $image = stripStyle($image[0]); // Remove special image formatted received from Hubspot.
    $ret = <<<HTML
\n\n
<div class="hubspot_blog_excerpt">
    <div class="hubspot image">$image</div>
    <a href="$link">$title</a><br/>
    <span class="hubspot pubdate">$date</span>
    <div class="hubspot blurb">
        $text_excerpt ... <a href="$link">read more</a>
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
