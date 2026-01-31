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
use Aksoyih\Utils\PathCleaner;

class JsonRenderer
{
    public function render(Metadata $metadata, Representation $representation): string
    {
        $trace = [];
        foreach ($metadata->trace as $index => $item) {
            $file = isset($item['file']) ? PathCleaner::clean($item['file']) : 'internal';
            $line = $item['line'] ?? '';
            $method = $item['function'] ?? 'global';
            if (isset($item['class'])) {
                $type = $item['type'] ?? '::';
                $function = $item['function'] ?? '';
                $method = $item['class'] . $type . $function;
            }
            $trace[] = "#{$index} {$file}:{$line} {$method}()";
        }

        $data = [
            'meta' => [
                'file' => PathCleaner::clean($metadata->file),
                'line' => $metadata->line,
                'caller' => $metadata->caller,
                'trace' => $trace,
                'execution_time' => round($metadata->executionTime, 4) . ' ms',
                'memory_usage' => $metadata->memoryUsage,
                'label' => $metadata->label,
            ],
            'data' => $this->normalize($representation),
        ];

        return json_encode(
            $data,
            JSON_PRETTY_PRINT
            | JSON_UNESCAPED_SLASHES
            | JSON_UNESCAPED_UNICODE
            | JSON_INVALID_UTF8_SUBSTITUTE
            | JSON_PARTIAL_OUTPUT_ON_ERROR
        );
    }

    private function normalize(Representation $representation): mixed
    {
        if ($representation instanceof ScalarRepresentation) {
            return $representation->value;
        }

        if ($representation instanceof ArrayRepresentation) {
            $result = [];
            foreach ($representation->items as $key => $item) {
                $result[$key] = $this->normalize($item);
            }
            return $result;
        }

        if ($representation instanceof ObjectRepresentation) {
            $result = [
                '@class' => $representation->className,
            ];
            foreach ($representation->properties as $property) {
                $result[$property->name] = $this->normalize($property->value);
            }
            return $result;
        }

        if ($representation instanceof ResourceRepresentation) {
            return "resource({$representation->type}) #{$representation->id}";
        }

        if ($representation instanceof RecursionRepresentation) {
            return '*RECURSION*';
        }

        if ($representation instanceof MaxDepthRepresentation) {
            return '*MAX DEPTH*';
        }

        return null;
    }
}
