<?php

namespace Aksoyih\Casters;

use Aksoyih\Parser;
use Aksoyih\Representations\ArrayRepresentation;
use Aksoyih\Representations\Representation;

class ArrayCaster implements Caster
{
    public function supports(mixed $data): bool
    {
        return is_array($data);
    }

    public function cast(mixed $data, Parser $parser): Representation
    {
        $items = [];
        foreach ($data as $key => $value) {
            $items[$key] = $parser->parse($value);
        }
        return new ArrayRepresentation(count($items), $items);
    }
}
