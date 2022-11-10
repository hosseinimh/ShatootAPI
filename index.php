<?php

require_once(__DIR__ . '/Helpers/Helper.php');
require_once(__DIR__ . '/Helpers/shatoot/ShatootApi.php');
require_once(__DIR__ . '/Data/Models/Post.php');

error_reporting(E_ALL);
set_error_handler('Helper::errorHandler');

try {
    $shatoot = new ShatootApi();
    $result = $shatoot->getGoodsbyRemaindsInStocks(date('Y-m-d'));
    $post = new Post();

    $result = $post->update(['id' => 1, 'name' => 'John']);

    Helper::print($result);
} catch (Exception $e) {
    Helper::print($e->getMessage());
}
