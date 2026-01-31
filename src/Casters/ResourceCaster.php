<?php

namespace Aksoyih\Casters;

use Aksoyih\Parser;
use Aksoyih\Representations\ResourceRepresentation;
use Aksoyih\Representations\Representation;

class ResourceCaster implements Caster
{
    public function supports(mixed $data): bool
    {
        return is_resource($data);
    }

    public function cast(mixed $data, Parser $parser): Representation
    {
        $type = get_resource_type($data);
        $id = (int) $data;
        return new ResourceRepresentation($type, $id);
    }
}
