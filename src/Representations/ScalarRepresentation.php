<?php

namespace Aksoyih\Representations;

class ScalarRepresentation implements Representation
{
    public function __construct(
        public string $type,
        public mixed $value
    ) {
    }
}
