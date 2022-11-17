<?php

require_once(__DIR__ . '/Db.php');
require_once(__DIR__ . '/../../Helpers/Helper.php');

class Model
{
    private $db;
    protected $tblName;
    protected $result;

    public function __construct($tblName)
    {
        $this->tblName = $tblName;
        $this->db = new Db();
    }

    public function next()
    {
        try {
            return mysqli_fetch_array($this->result);
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return null;
    }

    protected function get($query)
    {
        return $this->onSelect($query);
    }

    protected function create($data)
    {
        $arr = $this->prepareCreateItems($data);
        $query = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', $this->tblName, $arr[0], $arr[1]);
        $insertedId = 0;

        if ($this->onExecute($query)) {
            $insertedId = intval($this->db->insert_id);
        }

        return $insertedId > 0 ? $insertedId : false;
    }

    protected function update($data, $where = '')
    {
        $subQuery = $this->prepareUpdateItems($data);
        $where = $where === '' ? $where : ' WHERE ' . $where;
        $query = sprintf('UPDATE `%s` SET %s %s', $this->tblName, $subQuery, $where);

        return $this->onExecute($query);
    }

    protected function onExecute($query)
    {
        try {
            $this->db->query($query);

            return $this->db->affected_rows > 0 ? true : false;
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return false;
    }

    private function onSelect($query)
    {
        try {
            $this->result = $this->db->query($query);
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return null;
    }

    private function prepareCreateItems($data)
    {
        $arr = $this->prepareItems($data);

        $i = 0;
        $keys = '';

        foreach ($arr[0] as $key) {
            $keys .= sprintf('`%s`', $key);
            $keys .= (++$i < $arr[1]) ? ',' : '';
        }

        $i = 0;
        $values = '';

        foreach ($arr[2] as $value) {
            $values .= is_string($value) ? sprintf('%s', '\'' . $value . '\'') : sprintf('%s', $value);
            $values .= (++$i < $arr[3]) ? ',' : '';
        }

        return [$keys, $values];
    }

    private function prepareUpdateItems($data)
    {
        $arr = $this->prepareItems($data);
        $subQuery = '';

        for ($i = 0; $i < $arr[1]; $i++) {
            $subQuery .= is_string($arr[2][$i]) ? $arr[0][$i] . '=\'' . $arr[2][$i] . '\'' : $arr[0][$i] . '=' . $arr[2][$i];
            $subQuery .= $i + 1 < $arr[1] ? ',' : '';
        }

        return $subQuery;
    }

    private function prepareItems($data)
    {
        $keyItems = array_keys($data);
        $valueItems = array_values($data);
        $keysCount = count($keyItems);
        $valuesCount = count($valueItems);

        if ($keysCount !== $valuesCount) {
            throw new Exception('Keys count is not the same as values count.');
        }

        return [$keyItems, $keysCount, $valueItems, $valuesCount];
    }

    public function __call($name, $arguments)
    {
        try {
            if (is_callable([$this, $name])) {
                $reflection = new ReflectionMethod($this, $name);

                return ($reflection->isPublic() || $reflection->isProtected()) ? call_user_func_array([$this, $name], $arguments) : null;
            }
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return null;
    }
}
