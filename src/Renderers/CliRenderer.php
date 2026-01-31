<?php

namespace Aksoyih\Renderers;

use Aksoyih\Representations\ArrayRepresentation;
use Aksoyih\Representations\MaxDepthRepresentation;
use Aksoyih\Representations\Metadata;
use Aksoyih\Representations\ObjectRepresentation;
use Aksoyih\Representations\RecursionRepresentation;
use Aksoyih\Representations\Representation;
use Aksoyih\Representations\ResourceRepresentation;
use Aksoyih\Representations\ScalarRepresentation;

class CliRenderer
{
    private const INDENT = '  ';

    public function render(Metadata $metadata, Representation $representation): string
    {
        $output = '';

        if ($metadata->label) {
            $output .= "\033[1;36m" . $metadata->label . "\033[0m" . PHP_EOL;
        }

        $output .= "\033[1;30m" . $metadata->file . ':' . $metadata->line . "\033[0m" . PHP_EOL;
        $output .= $this->renderRepresentation($representation);
        $output .= PHP_EOL;

        return $output;
    }

    private function renderRepresentation(Representation $representation, int $level = 0): string
    {
        $indent = str_repeat(self::INDENT, $level);

        switch (get_class($representation)) {
            case ScalarRepresentation::class:
                /** @var ScalarRepresentation $representation */
                if (is_string($representation->value)) {
                    return "\033[32m\"" . $representation->value . "\"\033[0m";
                }
                if (is_bool($representation->value)) {
                    return "\033[35m" . ($representation->value ? 'true' : 'false') . "\033[0m";
                }
                if (is_null($representation->value)) {
                    return "\033[31mnull\033[0m";
                }
                return "\033[33m" . $representation->value . "\033[0m";

            case ArrayRepresentation::class:
                /** @var ArrayRepresentation $representation */
                $output = "\033[1marray:".$representation->count."
";
                foreach ($representation->items as $key => $item) {
                    $output .= $indent . self::INDENT . "\033[32m\"{$key}\"\033[0m => " . $this->renderRepresentation($item, $level + 1) . "\n";
                }
                $output .= $indent . "]";
                return $output;

            case ObjectRepresentation::class:
                /** @var ObjectRepresentation $representation */
                $output = "\033[1mobject({".$representation->className."})\033[0m {
";
                foreach ($representation->properties as $property) {
                    $visibilityColor = match($property->visibility) {
                        'public' => "\033[32m",
                        'protected' => "\033[33m",
                        'private' => "\033[31m",
                        default => "\033[37m",
                    };
                    $output .= $indent . self::INDENT . $visibilityColor . ($property->visibility === 'public' ? '+' : ($property->visibility === 'protected' ? '#' : '-')) . "\033[0m ";
                    $output .= "{$property->name}: " . $this->renderRepresentation($property->value, $level + 1) . "\n";
                }
                $output .= $indent . "}";
                return $output;

            case ResourceRepresentation::class:
                /** @var ResourceRepresentation $representation */
                return "\033[1mresource({".$representation->type."})\033[0m #{$representation->id}";

            case RecursionRepresentation::class:
                return "\033[36m*RECURSION*\033[0m";

            case MaxDepthRepresentation::class:
                return "\033[36m*MAX DEPTH*\033[0m";

            default:
                return 'unknown';
        }
    }
}
