<?php

namespace Aksoyih\Renderers;

use Aksoyih\Representations\Metadata;
use Aksoyih\Representations\Representation;
use Aksoyih\Representations\ScalarRepresentation;

class HtmlRenderer
{
    public function render(Metadata $metadata, Representation $representation, string $editor): string
    {
        $templatePath = __DIR__ . '/../Templates/template.php';

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
                        $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', parse_url($representation->value, PHP_URL_PATH));
                        $imageClass = $isImage ? 'bd-image-link' : '';
                        
                        $link = "<a href=\"{$escapedValue}\" target=\"_blank\" class=\"syntax-string hover:underline {$imageClass}\">\"{$escapedValue}\"";
                        if ($isImage) {
                            $link .= "<span class=\"bd-preview\"><img src=\"{$escapedValue}\" alt=\"Preview\" /></span>";
                        }
                        $link .= "</a>";
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
                $output .= '<span class="material-symbols-outlined bd-arrow">arrow_right</span>';
                $output .= '<div class="bd-summary-content">';
                $output .= "<span class=\"syntax-key\">array:{$representation->count}</span>";
                $output .= '</div>';
                $output .= '</summary>';

                $output .= '<div class="bd-content">';
                foreach ($representation->items as $key => $item) {
                    $output .= '<div class="bd-row">';
                    $output .= "<span class=\"syntax-string\">\"{$key}\"</span>";
                    $output .= '<span class="syntax-operator">=&gt;</span>';
                    $output .= $this->renderRepresentation($item);
                    $output .= '</div>';
                }
                $output .= '</div>';
                $output .= '</details>';
                return $output;

            case \Aksoyih\Representations\ObjectRepresentation::class:
                /** @var \Aksoyih\Representations\ObjectRepresentation $representation */
                $output = '<details class="bd-details" open>';
                $output .= '<summary class="bd-summary">';
                $output .= '<span class="material-symbols-outlined bd-arrow">arrow_right</span>';
                $output .= '<div class="bd-summary-content">';
                $output .= "<span class=\"syntax-type\">object</span><span class=\"syntax-comment\">({$representation->className})</span>";
                $output .= '</div>';
                $output .= '</summary>';

                $output .= '<div class="bd-content">';
                foreach ($representation->properties as $property) {
                    $visibility = $property->visibility;
                    $badgeClass = "bd-badge-{$visibility}";

                    $output .= '<div class="bd-row">';
                    $output .= '<div class="bd-badge-wrapper">';
                    $output .= "<span class=\"bd-badge {$badgeClass}\">" . substr($visibility, 0, 4) . "</span>";
                    $output .= '</div>';
                    $output .= '<div class="bd-property">';
                    $output .= "<span class=\"syntax-key\">{$property->name}:</span>";
                    $output .= $this->renderRepresentation($property->value);
                    $output .= '</div>';
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
