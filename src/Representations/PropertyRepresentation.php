<?php

namespace Aksoyih\Representations;

class PropertyRepresentation implements Representation
{
    public function __construct(
        public string $name,
        public Representation $value,
        public string $visibility,
        public ?string $className = null
    ) {
    }
}
