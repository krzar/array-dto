<?php

namespace KrZar\ArrayDto\tests;

use KrZar\ArrayDto\Generator;
use KrZar\ArrayDto\Mocks\ArrayDtoMock;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testSingle()
    {
        $object = Generator::generate(ArrayDtoMock::class, ['stringField' => 'generated']);
        $this->assertInstanceOf(ArrayDtoMock::class, $object);
        $this->assertEquals('generated', $object->stringField);
    }

    public function testMultiple()
    {
        $objects = Generator::generateMultiple(
            ArrayDtoMock::class,
            [
                ['stringField' => 'first'],
                ['stringField' => 'second']
            ]
        );
        $this->assertIsArray($objects);
        $this->assertCount(2, $objects);
    }
}
