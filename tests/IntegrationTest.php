<?php

namespace Aksoyih\Tests;

use Aksoyih\Parser;
use Aksoyih\Representations\ArrayRepresentation;
use Aksoyih\Representations\MaxDepthRepresentation;
use Aksoyih\Representations\ObjectRepresentation;
use Aksoyih\Representations\PropertyRepresentation;
use Aksoyih\Representations\RecursionRepresentation;
use Aksoyih\Representations\ResourceRepresentation;
use Aksoyih\Representations\ScalarRepresentation;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function testObjectParsing()
    {
        $parser = new Parser();
        $obj = new class {
            public string $name = 'Test';
            protected int $age = 20;
            private bool $active = true;
        };

        $representation = $parser->parse($obj);

        $this->assertInstanceOf(ObjectRepresentation::class, $representation);
        $this->assertCount(3, $representation->properties);

        // Check properties (order isn't guaranteed by reflection, so we find by name)
        $props = [];
        foreach ($representation->properties as $prop) {
            $props[$prop->name] = $prop;
        }

        $this->assertEquals('public', $props['name']->visibility);
        $this->assertEquals('Test', $props['name']->value->value);

        $this->assertEquals('protected', $props['age']->visibility);
        $this->assertEquals(20, $props['age']->value->value);

        $this->assertEquals('private', $props['active']->visibility);
        $this->assertEquals(true, $props['active']->value->value);
    }

    public function testRecursion()
    {
        $parser = new Parser();
        $a = new \stdClass();
        $b = new \stdClass();
        $a->b = $b;
        $b->a = $a;

        $representation = $parser->parse($a);

        $this->assertInstanceOf(ObjectRepresentation::class, $representation);
        $propB = $representation->properties[0]; // 'b'
        $this->assertEquals('b', $propB->name);
        
        $objB = $propB->value;
        $this->assertInstanceOf(ObjectRepresentation::class, $objB);
        
        $propA = $objB->properties[0]; // 'a' inside 'b'
        $this->assertEquals('a', $propA->name);
        
        // This should be recursion
        $this->assertInstanceOf(RecursionRepresentation::class, $propA->value);
    }

    public function testMaxDepth()
    {
        $parser = new Parser(maxDepth: 3);
        
        $data = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'level4' => 'too deep'
                    ]
                ]
            ]
        ];

        $representation = $parser->parse($data);
        
        $l1 = $representation->items['level1'];
        $l2 = $l1->items['level2'];
        $l3 = $l2->items['level3'];
        
        // At level 3, the NEXT item ('level4') would be at depth 4.
        // Our parser increments depth BEFORE processing.
        // Depth 0: root
        // Depth 1: level1 array
        // Depth 2: level2 array
        // Depth 3: level3 array
        // Depth 4: level4 value -> Should be MaxDepth if check is >= maxDepth
        
        // Let's trace parser logic:
        // parse(root) depth=0. 0 < 3. depth++ (1). Casts array.
        //   parse(level1) depth=1. 1 < 3. depth++ (2). Casts array.
        //     parse(level2) depth=2. 2 < 3. depth++ (3). Casts array.
        //       parse(level3) depth=3. 3 >= 3. Returns MaxDepth.
        
        // So level3 should be MaxDepthRepresentation? Or inside it?
        // Ah, `parse` checks depth at start.
        // If maxDepth is 3.
        // parse(root) -> depth 0. OK.
        //   parse(l1) -> depth 1. OK.
        //     parse(l2) -> depth 2. OK.
        //       parse(l3) -> depth 3. MAX DEPTH.
        
        $this->assertInstanceOf(MaxDepthRepresentation::class, $l3);
    }

    public function testResource()
    {
        $parser = new Parser();
        $resource = fopen('php://memory', 'r');
        
        $representation = $parser->parse($resource);
        
        $this->assertInstanceOf(ResourceRepresentation::class, $representation);
        $this->assertEquals('stream', $representation->type);
        $this->assertIsInt($representation->id);
        
        fclose($resource);
    }
}
