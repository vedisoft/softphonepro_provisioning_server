<?php

if (!defined('READFILE')) {
    exit('Error, you have not access to this file');
}

class Request
{

    public static function filter($request)
    {
        $result = array();

        if (empty($request['login'])) {
            throw new Exception('Empty login or password', 500);
        }

        foreach ($request as $key => $value) {
            $value = trim($value);
            $value = htmlspecialchars($value);

            $result[$key] = $value;
        }

        return $result;
    }

}
