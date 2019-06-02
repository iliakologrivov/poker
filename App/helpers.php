<?php

if (!function_exists('dd')) {
    function dd()
    {
        var_dump(...func_get_args());
        die();
    }
}

if (!function_exists('dump')) {
    function dump()
    {
        var_dump(...func_get_args());
    }
}