<html>
<body>
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

require_once("functions.php");
include_magpie();

$feed = get_feed("clearpoint");
foreach ($feed->items as $item)
{
    //echo 
    post_excerpt($item);
}
?>
</body>
</html>

