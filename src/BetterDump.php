<?php

namespace Aksoyih;

use Aksoyih\Representations\Metadata;

class BetterDump
{
    private static string $editor = 'phpstorm'; // Default editor

    public static function setEditor(string $editor): void
    {
        self::$editor = $editor;
    }

    public static function dump(mixed $data, ?string $label = null): void
    {
        $startTime = $_SERVER["REQUEST_TIME_FLOAT"] ?? microtime(true);
        $startMemory = memory_get_usage();

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
            ($endTime - $startTime) * 1000,
            $endMemory, // Use the end memory, not the difference
            memory_get_peak_usage(),
            $label
        );

        if (in_array(php_sapi_name(), ['cli', 'phpdbg'])) {
            $renderer = new \Aksoyih\Renderers\CliRenderer();
            echo $renderer->render($metadata, $representation);
        } else {
            ob_start();
            $renderer = new \Aksoyih\Renderers\HtmlRenderer();
            echo $renderer->render($metadata, $representation, self::$editor);
            ob_end_flush();
        }
    }

    /**
     * Find the first backtrace entry that is not part of this library.
     */
    private static function findCaller(array $backtrace): ?array
    {
        foreach ($backtrace as $trace) {
            if (isset($trace['file']) && !str_contains($trace['file'], 'better-dump/src')) {
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
            if (isset($current['file']) && !str_contains($current['file'], 'better-dump/src')) {
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