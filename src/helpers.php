<?php

if (!function_exists('dd')) {

    function dd(...$vars)
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
            'Memory Used' => formatBytes($memoryUsage),
            'Peak Memory Used' => formatBytes($peakMemoryUsage),
            'Execution Time' => $executionTime . ' ms',
        ];

        echo '<style>
            body {
                background-color: #1e1e1e;
                color: #abb2bf;
                font-family: Consolas, Menlo, monospace;
                margin: 0;
                padding: 20px;
            }
            .debug-output {
                background-color: #282c34;
                color: #abb2bf;
                border: 1px solid #61dafb;
                border-radius: 8px;
                padding: 20px;
                margin: 20px 0;
                word-wrap: break-word;
                white-space: pre-wrap;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
                font-size: 14px;
                line-height: 1.6;
                position: relative;
            }
            .debug-controls {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }
            .control-button {
                background: #2c313a;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 11px;
                cursor: pointer;
                color: #61dafb;
                border: 1px solid #3a3f4b;
                transition: background-color 0.2s;
            }
            .control-button:hover {
                background: #353b45;
            }
            .debug-output pre {
                margin: 0;
                padding: 0;
            }
            .line-numbers {
                float: left;
                margin-right: 10px;
                text-align: right;
                color: #636d83;
                padding-right: 10px;
                border-right: 1px solid #3a3f4b;
                user-select: none;
            }
            .code-block {
                display: inline-block;
                white-space: pre-wrap;
            }
            .debug-header {
                font-size: 10px;
                color: #61dafb;
                text-align: right;
            }
            .string { color: #98c379; }
            .number { color: #d19a66; }
            .boolean { color: #c678dd; }
            .null { color: #e06c75; }
            .array { color: #61afef; }
            .object { color: #56b6c2; }
            .collapsible {
                cursor: pointer;
                padding: 2px 8px;
                background: #2c313a;
                border-radius: 4px;
                display: inline-block;
                margin: 2px 0;
            }
            .content {
                display: none;
                padding-left: 20px;
            }
            .debug-info {
                background: #2c313a;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 4px;
                font-size: 12px;
                display: none;
            }
        </style>';

        echo '<script>
            function toggleCollapse(element) {
                const content = element.nextElementSibling;
                content.style.display = content.style.display === "none" ? "block" : "none";
            }
            function toggleDebugInfo(element) {
                const debugInfo = document.querySelector(".debug-info");
                debugInfo.style.display = debugInfo.style.display === "none" ? "block" : "none";
            }
            function expandAll() {
                document.querySelectorAll(".content").forEach(element => {
                    element.style.display = "block";
                });
            }
        </script>';

        echo '<div class="debug-output">';

        // Control buttons
        echo '<div class="debug-controls">';
        echo '<div class="control-button" onclick="expandAll()">↓ Expand All</div>';
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

        // Rest of the code remains the same...
        function formatVar($var) {
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

        foreach ($vars as $i => $var) {
            $name = 'Variable #' . ($i + 1);
            if (preg_match('/dd\((.*?)\)/', file_get_contents($caller['file']), $matches)) {
                $args = explode(',', $matches[1]);
                if (isset($args[$i])) {
                    $name = trim($args[$i]);
                }
            }

            echo "<strong>$name:</strong><br>";
            echo formatVar($var);
            echo "<br><br>";
        }

        echo '</div>';
        die(1);
    }

    function formatBytes($bytes)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . " " . $sizes[$factor];
    }
}