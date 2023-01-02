<?php

namespace KrZar\tests;

use KrZar\PhpArrayObjects\Generator;
use KrZar\PhpArrayObjects\Mocks\ArrayObjectMock;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testSingle()
    {
        $object = Generator::generate(ArrayObjectMock::class, ['stringField' => 'generated']);
        $this->assertInstanceOf(ArrayObjectMock::class, $object);
        $this->assertEquals('generated', $object->stringField);
    }

    public function testMultiple()
    {
        $objects = Generator::generateMultiple(
            ArrayObjectMock::class,
            [
                ['stringField' => 'first'],
                ['stringField' => 'second']
            ]
        );
        $this->assertIsArray($objects);
        $this->assertCount(2, $objects);
    }
}
