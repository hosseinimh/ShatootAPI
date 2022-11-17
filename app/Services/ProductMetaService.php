<?php

require_once(__DIR__ . '/../Models/ProductMeta.php');

class ProductMetaService
{
    public function insert($row, $productId, $sku, $salePrice, $remained)
    {
        $productMeta = new ProductMeta();

        $row ? $productMeta->updateRow($productId, $sku, $salePrice, $salePrice, $remained) :
            $productMeta->createRow($productId, $sku, $salePrice, $salePrice, $remained);
    }
}
