<?php

namespace App\Utils;

class Validate
{
    public static function notNull($value, $length = null): bool
    {
        if ($value == '0') {
            return false;
        }
        if ($length != null and strlen($value) > $length) {
            return false;
        }
        if (is_array($value)) {
            return sizeof($value) > 0;
        } else {
            return ($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0);
        }
    }
}