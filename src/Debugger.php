<?php

namespace Aksoyih\BetterDump;

class Debugger
{
    private static function formatBytes($bytes)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . " " . $sizes[$factor];
    }

    public static function dump(...$vars)
    {
        $backtrace = debug_backtrace();
        $caller = $backtrace[0];
        $memoryUsage = memory_get_usage(true);
        $peakMemoryUsage = memory_get_peak_usage(true);
        $executionTime = round((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000, 2);

        $requestInfo = [
            'URL' => $_SERVER['REQUEST_URI'] ?? 'N/A',
            'Method' => $_SERVER['REQUEST_METHOD'] ?? 'N/A',
            'Time' => date('Y-m-d H:i:s'),
            'Memory Used' => self::formatBytes($memoryUsage),
            'Peak Memory Used' => self::formatBytes($peakMemoryUsage),
            'Execution Time' => $executionTime . ' ms',
        ];

        include __DIR__ . '/templates/styles.php';
        include __DIR__ . '/templates/scripts.php';

        echo '<div class="debug-output">';
        
        // Control buttons
        echo '<div class="debug-controls">';
        echo '<div class="control-button" onclick="expandAll()">⮚ Expand All</div>';
        echo '<div class="control-button" onclick="toggleDebugInfo(this)">⚙️ Debug Info</div>';
        echo '</div>';

        // Debug info panel
        echo '<div class="debug-info">';
        echo '<strong>File:</strong> ' . $caller['file'] . '<br>';
        echo '<strong>Line:</strong> ' . $caller['line'] . '<br>';
        foreach ($requestInfo as $key => $value) {
            echo "<strong>$key:</strong> $value<br>";
        }
        echo '</div>';

        foreach ($vars as $i => $var) {
            $name = 'Variable #' . ($i + 1);
            if (preg_match('/dump\((.*?)\)/', file_get_contents($caller['file']), $matches)) {
                $args = explode(',', $matches[1]);
                if (isset($args[$i])) {
                    $name = trim($args[$i]);
                }
            }

            echo "<strong>$name:</strong><br>";
            echo self::formatVar($var);
            echo "<br><br>";
        }

        echo '</div>';
    }

    private static function formatVar($var)
    {
        if (is_string($var)) {
            if (filter_var($var, FILTER_VALIDATE_URL)) {
                return '<span class="string"><a href="' . htmlentities($var) . '" target="_blank" style="color: #98c379;">"' . htmlentities($var) . '"</a></span>';
            }
            return '<span class="string">"' . htmlentities($var) . '"</span>';
        } elseif (is_numeric($var)) {
            return '<span class="number">' . $var . '</span>';
        } elseif (is_bool($var)) {
            return '<span class="boolean">' . ($var ? 'true' : 'false') . '</span>';
        } elseif (is_null($var)) {
            return '<span class="null">null</span>';
        } elseif (is_array($var) || is_object($var)) {
            $output = '';
            $isArray = is_array($var);
            $type = $isArray ? 'Array' : get_class($var);

            $output .= "<div class='collapsible' onclick='toggleCollapse(this)'>";
            $output .= "<span class='" . ($isArray ? 'array' : 'object') . "'>$type</span>";
            $output .= " (" . count((array)$var) . " items)";
            $output .= "</div>";

            $output .= "<div class='content' style='display:none;'>";
            foreach ((array)$var as $key => $value) {
                $output .= htmlentities($key) . " => " . formatVar($value) . "<br>";
            }
            $output .= "</div>";
            return $output;
        }
        return htmlentities(print_r($var, true));
    }
}