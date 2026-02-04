<?php

namespace Aksoyih\Renderers;

use Aksoyih\Representations\Metadata;
use Aksoyih\Representations\Representation;
use Aksoyih\Representations\ScalarRepresentation;

class HtmlRenderer
{
    private array $icons = [];

    public function render(Metadata $metadata, Representation $representation, string $editor, string $dumpId): string
    {
        $templatePath = __DIR__ . '/../Templates/template.php';
        
        // Read Assets
        $prismCss = file_get_contents(__DIR__ . '/../Templates/assets/prism.css');
        $prismJs = file_get_contents(__DIR__ . '/../Templates/assets/prism.js');
        $fontsCss = file_get_contents(__DIR__ . '/../Templates/assets/fonts.css');

        $this->icons = [
            'arrow_right' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 400 -280 v -400 l 200 200 -200 200 Z"/></svg>',
            'content_copy' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 360 -240 q -33 0 -56.5 -23.5 T 280 -320 v -480 q 0 -33 23.5 -56.5 T 360 -880 h 360 q 33 0 56.5 23.5 T 800 -800 v 480 q 0 33 -23.5 56.5 T 720 -240 H 360 Z m 0 -80 h 360 v -480 H 360 v 480 Z M 200 -80 q -33 0 -56.5 -23.5 T 120 -160 v -560 h 80 v 560 h 440 v 80 H 200 Z m 160 -240 v -480 480 Z"/></svg>',
            'light_mode' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 480 -360 q 50 0 85 -35 t 35 -85 q 0 -50 -35 -85 t -85 -35 q -50 0 -85 35 t -35 85 q 0 50 35 85 t 85 35 Z m 0 80 q -83 0 -141.5 -58.5 T 280 -480 q 0 -83 58.5 -141.5 T 480 -680 q 83 0 141.5 58.5 T 680 -480 q 0 83 -58.5 141.5 T 480 -280 Z M 200 -440 H 40 v -80 h 160 v 80 Z m 720 0 H 760 v -80 h 160 v 80 Z M 440 -760 v -160 h 80 v 160 h -80 Z m 0 720 v -160 h 80 v 160 h -80 Z M 256 -650 l -101 -97 57 -59 96 100 -52 56 Z m 492 496 -97 -101 53 -55 101 97 -57 59 Z m -98 -550 97 -101 59 57 -100 96 -56 -52 Z M 154 -212 l 101 -97 55 53 -97 101 -59 -57 Z m 326 -268 Z"/></svg>',
            'dark_mode' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 480 -120 q -150 0 -255 -105 T 120 -480 q 0 -150 105 -255 t 255 -105 q 14 0 27.5 1 t 26.5 3 q -41 29 -65.5 75.5 T 444 -660 q 0 90 63 153 t 153 63 q 55 0 101 -24.5 t 75 -65.5 q 2 13 3 26.5 t 1 27.5 q 0 150 -105 255 T 480 -120 Z m 0 -80 q 88 0 158 -48.5 T 740 -375 q -20 5 -40 8 t -40 3 q -123 0 -209.5 -86.5 T 364 -660 q 0 -20 3 -40 t 8 -40 q -78 32 -126.5 102 T 200 -480 q 0 116 82 198 t 198 82 Z m -10 -270 Z"/></svg>',
            'search' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 784 -120 532 -372 q -30 24 -69 38 t -83 14 q -109 0 -184.5 -75.5 T 120 -580 q 0 -109 75.5 -184.5 T 380 -840 q 109 0 184.5 75.5 T 640 -580 q 0 44 -14 83 t -38 69 l 252 252 -56 56 Z M 380 -400 q 75 0 127.5 -52.5 T 560 -580 q 0 -75 -52.5 -127.5 T 380 -760 q -75 0 -127.5 52.5 T 200 -580 q 0 75 52.5 127.5 T 380 -400 Z"/></svg>',
            'history' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 480 -120 q -138 0 -240.5 -91.5 T 122 -440 h 82 q 14 104 92.5 172 T 480 -200 q 117 0 198.5 -81.5 T 760 -480 q 0 -117 -81.5 -198.5 T 480 -760 q -69 0 -129 32 t -101 88 h 110 v 80 H 120 v -240 h 80 v 94 q 51 -64 124.5 -99 T 480 -840 q 75 0 140.5 28.5 t 114 77 q 48.5 48.5 77 114 T 840 -480 q 0 75 -28.5 140.5 t -77 114 q -48.5 48.5 -114 77 T 480 -120 Z m 112 -192 L 440 -464 v -216 h 80 v 184 l 128 128 -56 56 Z"/></svg>',
            'unfold_less' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m 356 -160 -56 -56 180 -180 180 180 -56 56 -124 -124 -124 124 Z m 124 -404 L 300 -744 l 56 -56 124 124 124 -124 56 56 -180 180 Z"/></svg>',
            'unfold_more' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 480 -120 300 -300 l 58 -58 122 122 122 -122 58 58 -180 180 Z M 358 -598 l -58 -58 180 -180 180 180 -58 58 -122 -122 -122 122 Z"/></svg>',
            'close' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m 256 -200 -56 -56 224 -224 -224 -224 56 -56 224 224 224 -224 56 56 -224 224 224 224 -56 56 -224 -224 -224 224 Z"/></svg>',
            'bug_report' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 480 -200 q 66 0 113 -47 t 47 -113 v -160 q 0 -66 -47 -113 t -113 -47 q -66 0 -113 47 t -47 113 v 160 q 0 66 47 113 t 113 47 Z m -80 -120 h 160 v -80 H 400 v 80 Z m 0 -160 h 160 v -80 H 400 v 80 Z m 80 40 Z m 0 320 q -65 0 -120.5 -32 T 272 -240 H 160 v -80 h 84 q -3 -20 -3.5 -40 t -.5 -40 h -80 v -80 h 80 q 0 -20 .5 -40 t 3.5 -40 h -84 v -80 h 112 q 14 -23 31.5 -43 t 40.5 -35 l -64 -66 56 -56 86 86 q 28 -9 57 -9 t 57 9 l 88 -86 56 56 -66 66 q 23 15 41.5 34.5 T 688 -640 h 112 v 80 h -84 q 3 20 3.5 40 t .5 40 h 80 v 80 h -80 q 0 20 -.5 40 t -3.5 40 h 84 v 80 H 688 q -32 56 -87.5 88 T 480 -120 Z"/></svg>',
            'schedule' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m 612 -292 56 -56 -148 -148 v -184 h -80 v 216 l 172 172 Z M 480 -80 q -83 0 -156 -31.5 T 197 -197 q -54 -54 -85.5 -127 T 80 -480 q 0 -83 31.5 -156 T 197 -763 q 54 -54 127 -85.5 T 480 -880 q 83 0 156 31.5 T 763 -763 q 54 54 85.5 127 T 880 -480 q 0 83 -31.5 156 T 763 -197 q -54 54 -127 85.5 T 480 -80 Z m 0 -400 Z m 0 320 q 133 0 226.5 -93.5 T 800 -480 q 0 -133 -93.5 -226.5 T 480 -800 q -133 0 -226.5 93.5 T 160 -480 q 0 133 93.5 226.5 T 480 -160 Z"/></svg>',
            'memory' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 360 -360 v -240 h 240 v 240 H 360 Z m 80 -80 h 80 v -80 h -80 v 80 Z m -80 320 v -80 h -80 q -33 0 -56.5 -23.5 T 200 -280 v -80 h -80 v -80 h 80 v -80 h -80 v -80 h 80 v -80 q 0 -33 23.5 -56.5 T 280 -760 h 80 v -80 h 80 v 80 h 80 v -80 h 80 v 80 h 80 q 33 0 56.5 23.5 T 760 -680 v 80 h 80 v 80 h -80 v 80 h 80 v 80 h -80 v 80 q 0 33 -23.5 56.5 T 680 -200 h -80 v 80 h -80 v -80 h -80 v 80 h -80 Z m 320 -160 v -400 H 280 v 400 h 400 Z M 480 -480 Z"/></svg>',
            'terminal' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 160 -160 q -33 0 -56.5 -23.5 T 80 -240 v -480 q 0 -33 23.5 -56.5 T 160 -800 h 640 q 33 0 56.5 23.5 T 880 -720 v 480 q 0 33 -23.5 56.5 T 800 -160 H 160 Z m 0 -80 h 640 v -400 H 160 v 400 Z m 140 -40 -56 -56 103 -104 -104 -104 57 -56 160 160 -160 160 Z m 180 0 v -80 h 240 v 80 H 480 Z"/></svg>',
            'warning' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m 40 -120 440 -760 440 760 H 40 Z m 138 -80 h 604 L 480 -720 178 -200 Z m 302 -40 q 17 0 28.5 -11.5 T 520 -280 q 0 -17 -11.5 -28.5 T 480 -320 q -17 0 -28.5 11.5 T 440 -280 q 0 17 11.5 28.5 T 480 -240 Z m -40 -120 h 80 v -200 h -80 v 200 Z m 40 -100 Z"/></svg>',
            'check' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M 382 -240 154 -468 l 57 -57 171 171 367 -367 57 57 -424 424 Z"/></svg>',
        ];
        
        // Pass icons to template
        $icons = $this->icons;

        // Using an output buffer to capture the output of the template file
        ob_start();
        include $templatePath;
        $content = ob_get_clean();

        return $content;
    }

    private function renderRepresentation(Representation $representation): string
    {
        switch (get_class($representation)) {
            case ScalarRepresentation::class:
                /** @var ScalarRepresentation $representation */
                if (is_string($representation->value)) {
                    $escapedValue = htmlspecialchars($representation->value, ENT_QUOTES, 'UTF-8');
                    
                    if (filter_var($representation->value, FILTER_VALIDATE_URL)) {
                        $path = parse_url($representation->value, PHP_URL_PATH);
                        $path = is_string($path) ? $path : '';
                        $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $path);
                        $imageClass = $isImage ? 'bd-image-link' : '';
                        
                        $link = "<a href=\"{$escapedValue}\" target=\"_blank\" class=\"syntax-string hover:underline {$imageClass}\">\"{$escapedValue}\"</a>";
                        if ($isImage) {
                            $link .= "<span class=\"bd-preview\"><img src=\"{$escapedValue}\" alt=\"Preview\" /></span>";
                        }
                        return $link;
                    }
                    
                    return "<span class=\"syntax-string\">\"" . $escapedValue . '"</span>';
                }
                if (is_null($representation->value)) {
                    return "<span class=\"syntax-null\">null</span>";
                }
                if (is_bool($representation->value)) {
                    return "<span class=\"syntax-bool\">" . ($representation->value ? 'true' : 'false') . "</span>";
                }
                $value = htmlspecialchars($representation->value, ENT_QUOTES, 'UTF-8');
                $type = $representation->type;
                return "<span class=\"syntax-{$type}\">{$value}</span>";

            case \Aksoyih\Representations\ArrayRepresentation::class:
                /** @var \Aksoyih\Representations\ArrayRepresentation $representation */
                $output = '<details class="bd-details" open>';
                $output .= '<summary class="bd-summary">';
                $output .= '<span class="material-symbols-outlined bd-arrow">' . $this->icons['arrow_right'] . '</span>';
                $output .= '<div class="bd-summary-content">';
                $output .= "<span class=\"syntax-key\">array:{$representation->count}</span>";
                $output .= '</div>';
                $output .= '</summary>';

                $output .= '<div class="bd-content">';
                foreach ($representation->items as $key => $item) {
                    $escapedKey = htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8');
                    $output .= '<div class="bd-row">';
                    $output .= '<div class="bd-badge-wrapper"></div>';
                    $output .= '<div class="bd-key">';
                    $output .= "<span class=\"syntax-string\">\"{$escapedKey}\"</span>";
                    $output .= '<span class="syntax-operator">=></span>';
                    $output .= '</div>';
                    $output .= '<div class="bd-value">';
                    $output .= $this->renderRepresentation($item);
                    $output .= '</div>';
                    
                    // Add copy button for scalars
                    if ($item instanceof ScalarRepresentation) {
                        $json = json_encode([
                            'key' => $key,
                            'value' => $item->value,
                            'type' => $item->type
                        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                        $escapedJson = htmlspecialchars($json, ENT_QUOTES, 'UTF-8');
                        $output .= '<button class="bd-copy-line" title="Copy as JSON" data-json="' . $escapedJson . '">';
                        $output .= '<span class="material-symbols-outlined" style="font-size: 14px;">' . $this->icons['content_copy'] . '</span>';
                        $output .= '</button>';
                    }
                    
                    $output .= '</div>';
                }
                $output .= '</div>';
                $output .= '</details>';
                return $output;

            case \Aksoyih\Representations\ObjectRepresentation::class:
                /** @var \Aksoyih\Representations\ObjectRepresentation $representation */
                $output = '<details class="bd-details" open>';
                $output .= '<summary class="bd-summary">';
                $output .= '<span class="material-symbols-outlined bd-arrow">' . $this->icons['arrow_right'] . '</span>';
                $output .= '<div class="bd-summary-content">';
                $output .= "<span class=\"syntax-type\">object</span><span class=\"syntax-comment\">({$representation->className})</span>";
                $output .= '</div>';
                $output .= '</summary>';

                $output .= '<div class="bd-content">';
                foreach ($representation->properties as $property) {
                    $visibility = $property->visibility;
                    $badgeClass = "bd-badge-{$visibility}";
                    $escapedPropertyName = htmlspecialchars($property->name, ENT_QUOTES, 'UTF-8');

                    $output .= '<div class="bd-row">';
                    $output .= '<div class="bd-badge-wrapper">';
                    $output .= "<span class=\"bd-badge {$badgeClass}\">" . substr($visibility, 0, 4) . "</span>";
                    $output .= '</div>';
                    $output .= '<div class="bd-key">';
                    $output .= "<span class=\"syntax-key\">{$escapedPropertyName}:</span>";
                    $output .= '</div>';
                    $output .= '<div class="bd-value">';
                    $output .= $this->renderRepresentation($property->value);
                    $output .= '</div>';
                    
                    // Add copy button for scalars
                    if ($property->value instanceof ScalarRepresentation) {
                        $json = json_encode([
                            'property' => $property->name,
                            'value' => $property->value->value,
                            'type' => $property->value->type,
                            'visibility' => $property->visibility
                        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                        $escapedJson = htmlspecialchars($json, ENT_QUOTES, 'UTF-8');
                        $output .= '<button class="bd-copy-line" title="Copy as JSON" data-json="' . $escapedJson . '">';
                        $output .= '<span class="material-symbols-outlined" style="font-size: 14px;">' . $this->icons['content_copy'] . '</span>';
                        $output .= '</button>';
                    }
                    
                    $output .= '</div>';
                }
                $output .= '</div>';
                $output .= '</details>';
                return $output;

            case \Aksoyih\Representations\ResourceRepresentation::class:
                /** @var \Aksoyih\Representations\ResourceRepresentation $representation */
                return "<span class=\"syntax-type\">resource</span><span class=\"syntax-comment\">({$representation->type})</span> <span class=\"syntax-comment\">#{$representation->id}</span>";

            case \Aksoyih\Representations\RecursionRepresentation::class:
                return '<span class="syntax-comment italic">*RECURSION*</span>';

            case \Aksoyih\Representations\MaxDepthRepresentation::class:
                return '<span class="syntax-comment italic">*MAX DEPTH*</span>';

            default:
                return '';
        }
    }

    private function formatBytes(float $bytes): string
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        if ($bytes == 0) {
            return '0 B';
        }
        $factor = floor((strlen((string)$bytes) - 1) / 3);
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . " " . $sizes[$factor];
    }
}
