<?php

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        \Aksoyih\BetterDump\Debugger::dump(...$vars);
        die(1);
    }
}