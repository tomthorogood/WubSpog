<?php

include("../functions.php");
$tag = "Chris";

$feed = get_feed($tag);
$entry = $feed->items[0];

$title = $entry['title'];
$title = attempt_encoding_fix($title);
$text_only = textBetween("p", $item["description"]);
