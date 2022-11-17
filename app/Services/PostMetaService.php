<?php

require_once(__DIR__ . '/../Models/PostMeta.php');

class PostMetaService
{
    public function createRows($postId, $sku, $price, $remained)
    {
        $postMeta = new PostMeta();

        $postMeta->deleteRows($postId);

        $postMeta->createRow($postId, 'post_views_count', 0);
        $postMeta->createRow($postId, '_vc_post_settings', 'a:1:{s:10:"vc_grid_id";a:0:{}}');
        $postMeta->createRow($postId, '_edit_lock', '');
        $postMeta->createRow($postId, '_edit_last', '');
        $postMeta->createRow($postId, '_thumbnail_id', '');
        $postMeta->createRow($postId, '_sku', $sku);
        $postMeta->createRow($postId, '_regular_price', $price);
        $postMeta->createRow($postId, 'total_sales', '0');
        $postMeta->createRow($postId, '_tax_status', 'taxable');
        $postMeta->createRow($postId, '_tax_class', '');
        $postMeta->createRow($postId, '_manage_stock', 'yes');
        $postMeta->createRow($postId, '_backorders', 'no');
        $postMeta->createRow($postId, '_sold_individually', 'yes');
        $postMeta->createRow($postId, '_virtual', 'no');
        $postMeta->createRow($postId, '_downloadable', 'no');
        $postMeta->createRow($postId, '_download_limit', '-1');
        $postMeta->createRow($postId, '_download_expiry', '-1');
        $postMeta->createRow($postId, '_stock', $remained);
        $postMeta->createRow($postId, '_stock_status', $remained > 0 ? 'instock' : 'otofstock');
        $postMeta->createRow($postId, '_wc_average_rating', '0');
        $postMeta->createRow($postId, '_wc_review_count', '0');
        $postMeta->createRow($postId, '_product_version', '');
        $postMeta->createRow($postId, '_price', $price);
        $postMeta->createRow($postId, '_area', '');
        $postMeta->createRow($postId, '_volume', '');
    }
}
