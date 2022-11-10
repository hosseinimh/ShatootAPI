<?php

require_once(__DIR__ . '/../../Db.php');

class Model
{
    private Db $db;

    public function __construct(protected string $tblName)
    {
        $this->db = new Db();
    }

    protected function get(string $query): Iterator
    {
        return $this->onSelect($query);
    }

    protected function create(array $data): bool
    {
        [$keys, $values] = $this->prepareCreateItems($data);
        $query = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', $this->tblName, $keys, $values);

        return $this->onExecute($query);
    }

    protected function update(array $data, string $where = ''): bool
    {
        $subQuery = $this->prepareUpdateItems($data);
        $where = $where === '' ? $where : ' WHERE ' . $where;
        $query = sprintf('UPDATE `%s` SET %s %s', $this->tblName, $subQuery, $where);

        return $this->onExecute($query);
    }

    private function onExecute(string $query): bool
    {
        try {
            $this->db->query($query);

            return $this->db->affected_rows > 0 ? true : false;
        } catch (Exception) {
        }

        return false;
    }

    private function onSelect(string $query): Iterator
    {
        try {
            return $this->db->query($query)->getIterator();
        } catch (Exception) {
        }

        return null;
    }

    private function prepareCreateItems(array $data)
    {
        [$keyItems, $keysCount, $valueItems, $valuesCount] = $this->prepareItems($data);

        $i = 0;
        $keys = '';

        foreach ($keyItems as $key) {
            $keys .= sprintf('`%s`', $key);
            $keys .= (++$i < $keysCount) ? ',' : '';
        }

        $i = 0;
        $values = '';

        foreach ($valueItems as $value) {
            $values .= is_string($value) ? sprintf('%s', '\'' . $value . '\'') : sprintf('%s', $value);
            $values .= (++$i < $valuesCount) ? ',' : '';
        }

        return [$keys, $values];
    }

    private function prepareUpdateItems(array $data)
    {
        [$keyItems, $keysCount, $valueItems] = $this->prepareItems($data);
        $subQuery = '';

        for ($i = 0; $i < $keysCount; $i++) {
            $subQuery .= is_string($valueItems[$i]) ? $keyItems[$i] . '=\'' . $valueItems[$i] . '\'' : $keyItems[$i] . '=' . $valueItems[$i];
            $subQuery .= $i + 1 < $keysCount ? ',' : '';
        }

        return $subQuery;
    }

    private function prepareItems(array $data)
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

    public function __call(string $name, array $arguments): mixed
    {
        try {
            if (is_callable([$this, $name])) {
                $reflection = new ReflectionMethod($this, $name);

                return ($reflection->isPublic() || $reflection->isProtected()) ? call_user_func_array([$this, $name], $arguments) : null;
            }
        } catch (Exception) {
        }

        return null;
    }
}
