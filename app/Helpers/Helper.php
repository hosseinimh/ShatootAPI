<?php

class Helper
{
    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
        self::print(['Error no' => $errno, 'Message' => $errstr, 'File' => $errfile, 'Line' => $errline]);
    }

    public static function print($data)
    {
        $data = is_bool($data) ? ($data ? 'true' : 'false') : (is_null($data) ? 'null' : $data);

        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
