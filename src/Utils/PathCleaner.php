<?php

namespace Aksoyih\Utils;

class PathCleaner
{
    private const CLEAN_PATTERN = '#^/var/www/(html/)?#';
    private static ?string $rootDirectory = null;

    public static function clean(string $path): string
    {
        return preg_replace(self::CLEAN_PATTERN, '', $path);
    }

    public static function setRootDirectory(?string $rootDirectory): void
    {
        self::$rootDirectory = self::normalizeRootDirectory($rootDirectory);
    }

    public static function hasRootDirectory(): bool
    {
        return self::$rootDirectory !== null && self::$rootDirectory !== '';
    }

    public static function toLocalPath(string $path): string
    {
        $cleaned = self::clean($path);

        if (!self::hasRootDirectory()) {
            return $cleaned;
        }

        if (self::isAbsolutePath($cleaned)) {
            return $cleaned;
        }

        $relative = ltrim($cleaned, DIRECTORY_SEPARATOR);
        $relative = preg_replace('#^\./#', '', $relative);

        return self::$rootDirectory . DIRECTORY_SEPARATOR . $relative;
    }

    public static function patternForJs(): string
    {
        return '^/var/www/(html/)?';
    }

    private static function normalizeRootDirectory(?string $rootDirectory): ?string
    {
        if ($rootDirectory === null) {
            return null;
        }

        $trimmed = trim($rootDirectory);
        if ($trimmed === '') {
            return null;
        }

        $normalized = rtrim($trimmed, DIRECTORY_SEPARATOR);
        return $normalized === '' ? DIRECTORY_SEPARATOR : $normalized;
    }

    public static function isAbsolutePath(string $path): bool
    {
        if ($path === '') {
            return false;
        }

        if (str_starts_with($path, DIRECTORY_SEPARATOR)) {
            return true;
        }

        return (bool) preg_match('#^[A-Za-z]:[\\\\/]#', $path);
    }
}
