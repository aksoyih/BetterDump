<?php

namespace Aksoyih\Representations;

class ArrayRepresentation implements Representation
{
    /**
     * @param Representation[] $items
     */
    public function __construct(
        public int $count,
        public array $items
    ) {
    }
}
