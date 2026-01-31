<?php

namespace Aksoyih\Representations;

class ObjectRepresentation implements Representation
{
    /**
     * @param PropertyRepresentation[] $properties
     */
    public function __construct(
        public string $className,
        public array $properties
    ) {
    }
}
