<?php

namespace Aksoyih\Representations;

class Metadata
{
    public function __construct(
        public string $file,
        public int $line,
        public ?string $caller,
        public float $executionTime,
        public float $memoryUsage,
        public float $peakMemoryUsage,
        public ?string $label
    ) {
    }
}
