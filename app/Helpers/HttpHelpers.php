<?php

namespace App\Helpers;

class HttpHelpers
{
    public static function do_post($url, $params)
    {
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => $params
            )
        );
        $result = file_get_contents($url, false, stream_context_create($options));
    }
}
