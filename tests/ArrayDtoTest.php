<?php
declare(strict_types=1);

namespace KrZar\ArrayDto\tests;

use KrZar\ArrayDto\ArrayDto;
use KrZar\ArrayDto\Mocks\ArrayDtoMock;
use KrZar\ArrayDto\Mocks\NestedArrayDtoMock;
use PHPUnit\Framework\TestCase;

class ArrayDtoTest extends TestCase
{
    private ArrayDto $testedClass;

    public function setUp(): void
    {
        $data = [
            'stringField' => 'test',
            'intField' => 10,
            'floatField' => 10.55,
            'boolField' => false,
            'arrayField' => [
                'one' => 'oneValue'
            ],
            'multidimensionalField' => [
                [
                    'field' => 'one'
                ],
                [
                    'field' => 'two'
                ]
            ],
            'unionTypeFieldString' => 'unionField',
            'mapped_name_field' => 'mappedField',
            'mappedTypeField' => '15'
        ];

        $data['objectField'] = $data;
        $data['unionTypeFieldObject'] = $data;


        $this->testedClass = ArrayDtoMock::create($data);
    }

    public function testBaseTypes()
    {
        $this->assertEquals('test', $this->testedClass->stringField);
        $this->assertEquals(10, $this->testedClass->intField);
        $this->assertEquals(10.55, $this->testedClass->floatField);
        $this->assertFalse($this->testedClass->boolField);
        $this->assertEquals(['one' => 'oneValue'], $this->testedClass->arrayField);
        $this->assertEquals(15, $this->testedClass->customField);
    }

    public function testNested()
    {
        $this->assertInstanceOf(ArrayDtoMock::class, $this->testedClass->objectField);
        $this->assertEquals('test', $this->testedClass->objectField->stringField);
        $this->assertEquals(null, $this->testedClass->objectField->objectField);
    }

    public function testNestedMultidimensional()
    {
        $this->assertIsArray($this->testedClass->multidimensionalField);
        $this->assertInstanceOf(NestedArrayDtoMock::class, $this->testedClass->multidimensionalField[0]);
        $this->assertEquals('one', $this->testedClass->multidimensionalField[0]->field);
        $this->assertInstanceOf(NestedArrayDtoMock::class, $this->testedClass->multidimensionalField[1]);
        $this->assertEquals('two', $this->testedClass->multidimensionalField[1]->field);
    }

    public function testUnionTypes()
    {
        $this->assertInstanceOf(ArrayDtoMock::class, $this->testedClass->unionTypeFieldObject);
        $this->assertEquals('unionField', $this->testedClass->unionTypeFieldString);
    }

    public function testNameMap()
    {
        $this->assertEquals('mappedField', $this->testedClass->mappedNameField);
    }

    public function testTypeMap()
    {
        $this->assertEquals(15, $this->testedClass->mappedTypeField);
    }
}
