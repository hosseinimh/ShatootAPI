<?php

require_once(__DIR__ . '/base/Db.php');
require_once(__DIR__ . '/base/Model.php');
require_once(__DIR__ . '/../Helpers/Helper.php');
require_once(__DIR__ . '/../../config.php');

class Post extends Model
{
    protected $tblName = 'wpclbm_posts';

    public function __construct()
    {
        parent::__construct($this->tblName);
    }

    public function get($id)
    {
        try {
            $query = sprintf('SELECT * FROM `%s` WHERE id=%d LIMIT 0,1', $this->tblName, $id);

            parent::get($query);

            return $this->next();
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return null;
    }

    public function createRow($authorId, $title)
    {
        try {
            $data = [
                'post_author' => $authorId,
                'post_date' => date('Y-m-d H:i:s'),
                'post_date_gmt' => gmdate('Y-m-d H:i:s'),
                'post_content' => '',
                'post_title' => $title,
                'post_excerpt' => '',
                'post_status' => 'publish',
                'comment_status' => 'open',
                'ping_status' => 'open',
                'post_password' => '',
                'post_name' => urlencode(str_replace(' ', '-', strtolower(substr($title, 0, 40)))),
                'to_ping' => '',
                'pinged' => '',
                'post_modified' => '0000-00-00 00:00:00',
                'post_modified_gmt' => '0000-00-00 00:00:00',
                'post_content_filtered' => '',
                'post_parent' => 0,
                'guid' => '',
                'menu_order' => 0,
                'post_type' => 'product',
                'post_mime_type' => '',
                'comment_count' => 0,
            ];

            return parent::create($data);
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return false;
    }

    public function updateRow($id, $title)
    {
        try {
            $data = [
                'post_title' => $title,
                'post_status' => 'publish',
                'comment_status' => 'open',
                'post_name' => urlencode(str_replace(' ', '-', strtolower(substr($title, 0, 40)))),
                'post_modified' => date('Y-m-d H:i:s'),
                'post_modified_gmt' => gmdate('Y-m-d H:i:s'),
                'guid' => HOST_URL . '/?post_type=product&#001;p=' . $id,
                'post_type' => 'product',
            ];

            return parent::update($data, 'ID=' . $id);
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return false;
    }
}
