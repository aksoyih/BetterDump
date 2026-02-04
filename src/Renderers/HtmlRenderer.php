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
            'arrow_right' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M400-280v-400l200 200-200 200Z"/></svg>',
            'content_copy' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M360-240q-33 0-56.5-23.5T280-320v-480q0-33 23.5-56.5T360-880h360q33 0 56.5 23.5T800-800v480q0 33-23.5 56.5T720-240H360Zm0-80h360v-480H360v480ZM200-80q-33 0-56.5-23.5T120-160v-560h80v560h440v80H200Zm160-240v-480 480Z"/></svg>',
            'light_mode' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Zm326-268Z"/></svg>',
            'dark_mode' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z"/></svg>',
            'search' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>',
            'history' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120q-138 0-240.5-91.5T122-440h82q14 104 92.5 172T480-200q117 0 198.5-81.5T760-480q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h110v80H120v-240h80v94q51-64 124.5-99T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z"/></svg>',
            'unfold_less' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m356-160-56-56 180-180 180 180-56 56-124-124-124 124Zm124-404L300-744l56-56 124 124 124-124 56 56-180 180Z"/></svg>',
            'unfold_more' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120 300-300l58-58 122 122 122-122 58 58-180 180ZM358-598l-58-58 180-180 180 180-58 58-122-122-122 122Z"/></svg>',
            'close' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224-224 56 56-224 224 224-224 224Z"/></svg>',
            'bug_report' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-200q66 0 113-47t47-113v-160q0-66-47-113t-113-47q-66 0-113 47t-47 113v160q0 66 47 113t113 47Zm-80-120h160v-80H400v80Zm0-160h160v-80H400v80Zm80 40Zm0 320q-65 0-120.5-32T272-240H160v-80h84q-3-20-3.5-40t-.5-40h-80v-80h80q0-20 .5-40t3.5-40h-84v-80h112q14-23 31.5-43t40.5-35l-64-66 56-56 86 86q28-9 57-9t57 9l88-86 56 56-66 66q23 15 41.5 34.5T688-640h112v80h-84q3 20 3.5 40t.5 40h80v80h-80q0 20-.5 40t-3.5 40h84v80H688q-32 56-87.5 88T480-120Z"/></svg>',
            'schedule' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z"/></svg>',
            'memory' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M360-360v-240h240v240H360Zm80-80h80v-80h-80v80Zm-80 320v-80h-80q-33 0-56.5-23.5T200-280v-80h-80v-80h80v-80h-80v-80h80v-80q0-33 23.5-56.5T280-760h80v-80h80v80h80v-80h80v80h80q33 0 56.5 23.5T760-680v80h80v80h-80v80h80v80h-80v80q0 33-23.5 56.5T680-200h-80v80h-80v-80h-80v80h-80Zm320-160v-400H280v400h400ZM480-480Z"/></svg>',
            'terminal' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H160v400Zm140-40-56-56 103-104-104-104 57-56 160 160-160 160Zm180 0v-80h240v80H480Z"/></svg>',
            'warning' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m40-120 440-760 440 760H40Zm138-80h604L480-720 178-200Zm302-40q17 0 28.5-11.5T520-280q0-17-11.5-28.5T480-320q-17 0-28.5 11.5T440-280q0 17 11.5 28.5T480-240Zm-40-120h80v-200h-80v200Zm40-100Z"/></svg>',
            'check' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>',
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
                    $output .= '<span class="syntax-operator">=&gt;</span>';
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