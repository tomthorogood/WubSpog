<?php

include("../wubspog_config.php");
include("../functions.php");

$rss = get_feed("clearpoint");
foreach ($rss->items as $item)
{
    print_r($item);
}

?>
