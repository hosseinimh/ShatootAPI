<?php

require_once(__DIR__ . '/base/Db.php');
require_once(__DIR__ . '/base/Model.php');
require_once(__DIR__ . '/../Helpers/Helper.php');

class User extends Model
{
    protected $tblName = 'wpclbm_users';

    public function __construct()
    {
        parent::__construct($this->tblName);
    }

    public function first()
    {
        try {
            $query = sprintf('SELECT * FROM `%s` ORDER BY ID LIMIT 0,1', $this->tblName);

            parent::get($query);

            return $this->next();
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return null;
    }
}
