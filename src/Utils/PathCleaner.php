<?php

namespace Aksoyih\Utils;

class PathCleaner
{
    private const CLEAN_PATTERN = '#^/var/www/(html/)?#';

    public static function clean(string $path): string
    {
        return preg_replace(self::CLEAN_PATTERN, '', $path);
    }

    public static function patternForJs(): string
    {
        return '^/var/www/(html/)?';
    }
}
