<?php
require dirname(__FILE__) . '/bootstrap.php';

$adTags = Model_TagAd::getTree();

var_dump(array_get_column($adTags, 'id'));

$mediaTags = Model_TagMedia::getTree();
var_dump(array_get_column($mediaTags, 'id'));
