<?php

use Aksoyih\BetterDump;

if (!function_exists('bd')) {
    function bd(mixed $data, ?string $label = null): void
    {
        BetterDump::dump($data, $label);
    }
}