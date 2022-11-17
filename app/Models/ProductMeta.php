<?php

require_once(__DIR__ . '/base/Db.php');
require_once(__DIR__ . '/base/Model.php');
require_once(__DIR__ . '/../Helpers/Helper.php');

class ProductMeta extends Model
{
    protected $tblName = 'wpclbm_wc_product_meta_lookup';

    public function __construct()
    {
        parent::__construct($this->tblName);
    }

    public function getBySKU($sku)
    {
        try {
            $query = sprintf('SELECT * FROM `%s` WHERE sku LIKE "%s" LIMIT 0,1', $this->tblName, $sku);

            parent::get($query);

            return $this->next();
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return null;
    }

    public function createRow($productId, $sku, $minPrice, $maxPrice, $remained)
    {
        try {
            $data = [
                'product_id' => $productId,
                'sku' => $sku . '',
                'virtual' => 0,
                'downloadable' => 0,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'onsale' => 0,
                'stock_quantity' => $remained > 0 ? $remained : 0,
                'stock_status' => $remained > 0 ? 'instock' : 'outofstock',
                'rating_count' => 0,
                'average_rating' => 0,
                'total_sales' => 0,
            ];

            return parent::create($data);
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return false;
    }

    public function updateRow($productId, $sku, $minPrice, $maxPrice, $remained)
    {
        try {
            $data = [
                'product_id' => $productId,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'stock_quantity' => $remained > 0 ? $remained : 0,
                'stock_status' => $remained > 0 ? 'instock' : 'outofstock',
            ];

            return parent::update($data, 'sku LIKE "' . $sku . '"');
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return false;
    }
}
