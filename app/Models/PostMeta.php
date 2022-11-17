<?php

require_once(__DIR__ . '/base/Db.php');
require_once(__DIR__ . '/base/Model.php');
require_once(__DIR__ . '/../Helpers/Helper.php');

class PostMeta extends Model
{
    protected $tblName = 'wpclbm_postmeta';

    public function __construct()
    {
        parent::__construct($this->tblName);
    }

    public function createRow($postId, $metaKey, $metaValue)
    {
        try {
            $data = [
                'post_id' => $postId,
                'meta_key' => $metaKey,
                'meta_value' => $metaValue,
            ];

            return parent::create($data);
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return false;
    }

    public function deleteRows($postId)
    {
        $query = sprintf('DELETE FROM `%s` WHERE `post_id`=%d', $this->tblName, $postId);

        return parent::onExecute($query);
    }
}
