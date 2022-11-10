<?php

require_once(__DIR__ . '/../Db.php');
require_once(__DIR__ . '/base/Model.php');

class Post extends Model
{
    protected string $tblName = 'tbl_tests';

    public function __construct()
    {
        parent::__construct($this->tblName);
    }

    public function getAll(): Iterator
    {
        $query = sprintf('SELECT * FROM `%s`', $this->tblName);

        return parent::get($query);
    }
}
