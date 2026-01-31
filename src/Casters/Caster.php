<?php

namespace Aksoyih\Casters;

use Aksoyih\Parser;
use Aksoyih\Representations\Representation;

interface Caster
{
    public function supports(mixed $data): bool;
    public function cast(mixed $data, Parser $parser): Representation;
}
