<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 16-12-4
 * Time: 下午9:58
 */

namespace Jaeger;

class EsTools
{
    public static function isJson($str)
    {
        return !is_null(json_decode($str));
    }
}