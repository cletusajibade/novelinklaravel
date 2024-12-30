<?php

namespace App\Helpers;

class UtilHelpers
{
    /**
     * A better way to dump variables to the browser
     */
    public static function dump()
    {
        $args = func_get_args();

        echo "\n<pre style=\"border:1px solid #ccc;padding:10px;" .
        "margin:10px;font:14px courier;background:whitesmoke;" .
        "display:block;border-radius:4px;\">\n";

        $trace = debug_backtrace(false);
        $offset = (@$trace[2]['function'] === 'dump_d') ? 2 : 0;

        echo "<span style=\"color:red\">" .
        @$trace[1 + $offset]['class'] . "</span>:" .
        "<span style=\"color:blue;\">" .
        @$trace[1 + $offset]['function'] . "</span>:" .
        @$trace[0 + $offset]['line'] . " " .
        "<span style=\"color:green;\">" .
        @$trace[0 + $offset]['file'] . "</span>\n";

        if (!empty($args)) {
            call_user_func_array('var_dump', $args);
        }

        echo "</pre>\n";
    }

    public static function dump_d()
    {
        call_user_func_array('dump', func_get_args());
        die();
    }
}
