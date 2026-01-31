<?php

namespace Aksoyih\Casters;

use Aksoyih\Parser;
use Aksoyih\Representations\ScalarRepresentation;
use Aksoyih\Representations\Representation;

class ScalarCaster implements Caster
{
    public function supports(mixed $data): bool
    {
        return is_scalar($data) || $data === null;
    }

    public function cast(mixed $data, Parser $parser): Representation
    {
        return new ScalarRepresentation(gettype($data), $data);
    }
}
