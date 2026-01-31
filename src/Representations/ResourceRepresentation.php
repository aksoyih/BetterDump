<?php

namespace Aksoyih\Representations;

class ResourceRepresentation implements Representation
{
    public function __construct(
        public string $type,
        public int $id
    ) {
    }
}
