<?php

require_once(__DIR__ . '/../config.php');

class Db
{
    private $mysqli;
    private string $host;
    private string $username;
    private string $password;
    private string $db;

    public function __construct()
    {
        $this->host = DB_HOST;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
        $this->db = DB_NAME;

        $this->connect();
    }

    public function connect(): bool
    {
        try {
            $this->mysqli = new mysqli($this->host, $this->username, $this->password, $this->db);

            if ($this->mysqli->connect_errno) {
                throw new Exception('Connection error. ' . mysqli_connect_error());
            }

            return true;
        } catch (Exception) {
        }

        return false;
    }

    public function __call(string $name, array $arguments): mixed
    {
        try {
            return is_callable([$this->mysqli, $name]) ? call_user_func_array([$this->mysqli, $name], $arguments) : null;
        } catch (Exception) {
        }

        return null;
    }

    public function __get(string $name): mixed
    {
        try {
            return $this->mysqli->$name;
        } catch (Exception) {
        }

        return null;
    }
}
