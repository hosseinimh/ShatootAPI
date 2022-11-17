<?php

require_once(__DIR__ . '/../Helpers/Helper.php');
require_once(__DIR__ . '/../Helpers/shatoot/ShatootApi.php');
require_once(__DIR__ . '/../Models/User.php');
require_once(__DIR__ . '/../Models/ProductMeta.php');
require_once(__DIR__ . '/PostService.php');
require_once(__DIR__ . '/PostMetaService.php');
require_once(__DIR__ . '/ProductMetaService.php');

class ShatootService
{
    protected $shatootApi;
    protected $user;
    protected $productMeta;
    protected $postService;
    protected $postMetaService;
    protected $productMetaService;

    public function __construct()
    {
        $this->shatootApi = new ShatootApi();
        $this->user = new User();
        $this->productMeta = new ProductMeta();
        $this->postService = new PostService();
        $this->postMetaService = new PostMetaService();
        $this->productMetaService = new ProductMetaService();
    }

    public function updateProducts($start, $take)
    {
        try {
            $result = $this->shatootApi->getGoodsbyRemaindsInStocksBySalePrice(date('Y-m-d'), $start, $take);
            $length = is_array($result) ? count($result) : 0;

            for ($i = 0; $i < $length; $i++) {
                $this->handleRefresh(intval($result[$i]->id), $result[$i]->name, intval($result[$i]->remiand), intval($result[$i]->finalPrice));
            }

            return $i;
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return false;
    }

    private function handleRefresh($sku, $name, $remained, $salePrice)
    {
        try {
            if (!($userRow = $this->user->first())) {
                return;
            }

            $productMetaRow = $this->productMeta->getBySKU($sku);

            if (!($postRow = $this->postService->insert($productMetaRow ? intval($productMetaRow['product_id']) : 0, $name, intval($userRow['ID'])))) {
                return;
            }

            $postId = intval($postRow['ID']);

            $this->postMetaService->createRows($postId, $sku, $salePrice, $remained);
            $this->productMetaService->insert($productMetaRow, $postId, $sku, $salePrice, $remained);
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }
    }
}
