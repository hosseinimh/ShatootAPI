<?php

require_once(__DIR__ . '/../Helpers/Helper.php');
require_once(__DIR__ . '/../Services/ShatootService.php');
require_once(__DIR__ . '/../../config.php');

class ShatootController
{
    public function updateProducts()
    {
        try {
            $shatootService = new ShatootService();

            $start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
            $result = $shatootService->updateProducts($start, SHATOOT_TAKE_ITEMS);
            $affectedRows = is_int($result) && $result > 0 ? $result : 0;

            $data = ['hostUrl' => HOST_URL, 'takeItems' => SHATOOT_TAKE_ITEMS, 'affectedRows' => $affectedRows, 'start' => $start += $affectedRows];

            extract($data);

            include __DIR__ . '/../../resources/views/index.php';
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }
    }
}
