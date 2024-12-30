<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Generate unique random number
     */
    public static function str_rand(int $length = 64)
    { // 64 = 32
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }
}
