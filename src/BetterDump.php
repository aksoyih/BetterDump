<?php

namespace Aksoyih;

use Aksoyih\Representations\Metadata;

class BetterDump
{
    private static string $editor = 'phpstorm'; // Default editor
    private static bool $jsonMode = false;

    public static function setEditor(string $editor): void
    {
        self::$editor = $editor;
    }

    public static function outputJson(bool $enable = true): void
    {
        self::$jsonMode = $enable;
    }

    public static function dump(mixed $data, ?string $label = null): void
    {
        $startTime = $_SERVER["REQUEST_TIME_FLOAT"] ?? microtime(true);

        // Pass the backtrace to the helper methods
        $backtrace = debug_backtrace();
        $caller = self::findCaller($backtrace);
        $secondaryTrace = self::getSecondaryTrace($backtrace);

        $parser = new Parser();
        $representation = $parser->parse($data);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $metadata = new Metadata(
            $caller['file'] ?? 'unknown',
            $caller['line'] ?? 0,
            $secondaryTrace, // Use the new secondary trace method
            array_values(self::getCleanTrace($backtrace)),
            ($endTime - $startTime) * 1000,
            $endMemory, // Use the end memory, not the difference
            memory_get_peak_usage(),
            $label
        );

        if (self::$jsonMode) {
            if (!headers_sent()) {
                header('Content-Type: application/json');
            }
            $renderer = new \Aksoyih\Renderers\JsonRenderer();
            echo $renderer->render($metadata, $representation);
            return;
        }

        $dumpId = uniqid('bd_');

        if (in_array(php_sapi_name(), ['cli', 'phpdbg'])) {
            $renderer = new \Aksoyih\Renderers\CliRenderer();
            echo $renderer->render($metadata, $representation);
        } else {
            ob_start();
            $renderer = new \Aksoyih\Renderers\HtmlRenderer();
            echo $renderer->render($metadata, $representation, self::$editor, $dumpId);
            ob_end_flush();
        }
    }

    /**
     * Filter the backtrace to remove internal library calls.
     */
    private static function getCleanTrace(array $backtrace): array
    {
        $cleanTrace = [];
        foreach ($backtrace as $trace) {
            // Filter by Class (internal library classes)
            if (isset($trace['class']) && str_starts_with($trace['class'], 'Aksoyih\\')) {
                continue;
            }

            // Filter by specific file (helpers.php) for the global function wrapper
            // Check if it's the specific helpers file of this package to avoid false positives
            if (isset($trace['file']) && str_ends_with($trace['file'], DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'helpers.php')) {
                continue;
            }
            
            // Also catch the case where helpers.php might be loaded differently or symlinked
            // If the function is 'bd' and we are in a file named helpers.php, skip it.
            if (isset($trace['function']) && $trace['function'] === 'bd' && isset($trace['file']) && str_ends_with($trace['file'], 'helpers.php')) {
                continue;
            }

            $cleanTrace[] = $trace;
        }
        return $cleanTrace;
    }

    /**
     * Find the first backtrace entry that is not part of this library.
     */
    private static function findCaller(array $backtrace): ?array
    {
        foreach ($backtrace as $trace) {
            if (
                isset($trace['file']) &&
                !str_contains($trace['file'], 'better-dump/src') &&
                !str_ends_with($trace['file'], 'helpers.php')
            ) {
                return $trace;
            }
        }
        return null; // Should ideally not be reached
    }

    /**
     * Find the caller of the function where bd() was called.
     */
    private static function getSecondaryTrace(array $backtrace): ?string
    {
        $primaryCaller = null;
        for ($i = 0; $i < count($backtrace) - 1; $i++) {
            $current = $backtrace[$i];
            if (
                isset($current['file']) &&
                !str_contains($current['file'], 'better-dump/src') &&
                !str_ends_with($current['file'], 'helpers.php')
            ) {
                $primaryCaller = $i;
                break;
            }
        }

        if ($primaryCaller === null) {
            return null;
        }

        $secondaryCaller = $backtrace[$primaryCaller + 1] ?? null;

        if (isset($secondaryCaller['class'])) {
            return $secondaryCaller['class'] . $secondaryCaller['type'] . $secondaryCaller['function'];
        }

        if (isset($secondaryCaller['function'])) {
            return $secondaryCaller['function'];
        }

        return null;
    }
}