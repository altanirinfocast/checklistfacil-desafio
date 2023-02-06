<?php

namespace App\Utilities;

use Illuminate\Support\Arr;

class Result
{

    protected static $original = [
        'success'     => false,
        'data'        => null,
        'message'     => null,
        'errors'      => null,
        // 'total'       => 0,
        // 'status'      => null,
    ];

    protected static $attributes = [];

    function __construct()
    {
        self::$attributes = self::$original;
    }

    public static function setData($data = [])
    {
        Arr::set(self::$attributes, 'data', $data);
        // // se for uma array
        // if (is_array($data))
        //     Arr::set(self::$attributes, 'total', count($data));
        // // se for uma collection
        // if (is_object($data))
        //     Arr::set(self::$attributes, 'total', $data->count());
    }

    public static function response()
    {
        return self::$attributes;
    }

    public static function setErrors($errors = null)
    {
        Arr::set(self::$attributes, 'errors', $errors);
    }

    public static function setMessage($message = null)
    {
        Arr::set(self::$attributes, 'message', $message);
    }

    public static function setSuccess($success = false)
    {
        Arr::set(self::$attributes, 'success', $success);
    }

    public static function setTotal($total = 0)
    {
        // Arr::set(self::$attributes, 'total', $total);
    }
}
