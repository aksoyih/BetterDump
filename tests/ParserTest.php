<?php

namespace Aksoyih\Tests;

use Aksoyih\Parser;
use Aksoyih\Representations\ArrayRepresentation;
use Aksoyih\Representations\ScalarRepresentation;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParseArray()
    {
        $parser = new Parser();
        $data = [1, 'hello', true, null];
        $representation = $parser->parse($data);

        $this->assertInstanceOf(ArrayRepresentation::class, $representation);
        $this->assertEquals(4, $representation->count);

        $this->assertInstanceOf(ScalarRepresentation::class, $representation->items[0]);
        $this->assertEquals('integer', $representation->items[0]->type);
        $this->assertEquals(1, $representation->items[0]->value);

        $this->assertInstanceOf(ScalarRepresentation::class, $representation->items[1]);
        $this->assertEquals('string', $representation->items[1]->type);
        $this->assertEquals('hello', $representation->items[1]->value);

        $this->assertInstanceOf(ScalarRepresentation::class, $representation->items[2]);
        $this->assertEquals('boolean', $representation->items[2]->type);
        $this->assertEquals(true, $representation->items[2]->value);
        
        $this->assertInstanceOf(ScalarRepresentation::class, $representation->items[3]);
        $this->assertEquals('NULL', $representation->items[3]->type);
        $this->assertNull($representation->items[3]->value);
    }
}
