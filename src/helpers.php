<?php

use Aksoyih\BetterDump;

if (!function_exists('bd')) {
    /**
     * Dump data and continue execution.
     */
    function bd(mixed $data, ?string $label = null): void
    {
        BetterDump::dump($data, $label, false);
    }
}

if (!function_exists('bdd')) {
    /**
     * Dump data and die (exit).
     */
    function bdd(mixed $data, ?string $label = null): void
    {
        BetterDump::dump($data, $label, true);
        exit(1);
    }
}
