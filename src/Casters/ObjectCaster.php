<?php

namespace Aksoyih\Casters;

use Aksoyih\Parser;
use Aksoyih\Representations\ObjectRepresentation;
use Aksoyih\Representations\PropertyRepresentation;
use Aksoyih\Representations\Representation;
use ReflectionClass;
use ReflectionProperty;

class ObjectCaster implements Caster
{
    public function supports(mixed $data): bool
    {
        return is_object($data);
    }

    public function cast(mixed $data, Parser $parser): Representation
    {
        $reflection = new ReflectionClass($data);
        $className = get_class($data);
        $properties = [];

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $name = $property->getName();
            $value = $property->getValue($data);
            $visibility = $this->getVisibility($property);
            $declaringClass = $property->getDeclaringClass()->getName();

            $properties[] = new PropertyRepresentation(
                $name,
                $parser->parse($value),
                $visibility,
                $declaringClass
            );
        }

        return new ObjectRepresentation($className, $properties);
    }

    private function getVisibility(ReflectionProperty $property): string
    {
        if ($property->isPublic()) {
            return 'public';
        }

        if ($property->isProtected()) {
            return 'protected';
        }

        if ($property->isPrivate()) {
            return 'private';
        }

        return 'public';
    }
}
