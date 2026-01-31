<?php

namespace Aksoyih;

use Aksoyih\Casters\ArrayCaster;
use Aksoyih\Casters\Caster;
use Aksoyih\Casters\ObjectCaster;
use Aksoyih\Casters\ResourceCaster;
use Aksoyih\Casters\ScalarCaster;
use Aksoyih\Representations\MaxDepthRepresentation;
use Aksoyih\Representations\RecursionRepresentation;
use Aksoyih\Representations\ScalarRepresentation;
use Aksoyih\Representations\Representation;

class Parser
{
    /** @var Caster[] */
    private array $casters;
    private array $path = [];
    private int $depth = 0;
    private int $maxDepth;

    public function __construct(int $maxDepth = 20)
    {
        $this->maxDepth = $maxDepth;
        // Order matters: more specific checks could go first if we had them.
        $this->casters = [
            new ScalarCaster(),
            new ArrayCaster(),
            new ResourceCaster(),
            new ObjectCaster(),
        ];
    }

    public function parse(mixed $data): Representation
    {
        if ($this->depth >= $this->maxDepth) {
            return new MaxDepthRepresentation();
        }

        // Generic recursion detection for ANY object type
        $isObject = is_object($data);
        if ($isObject) {
            $hash = spl_object_hash($data);
            if (in_array($hash, $this->path)) {
                return new RecursionRepresentation();
            }
            $this->path[] = $hash;
        }

        $this->depth++;
        $result = null;

        foreach ($this->casters as $caster) {
            if ($caster->supports($data)) {
                $result = $caster->cast($data, $this);
                break;
            }
        }

        $this->depth--;

        if ($isObject) {
            array_pop($this->path);
        }

        return $result ?? new ScalarRepresentation('unknown', 'unknown');
    }
}
