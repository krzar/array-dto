<?php
declare(strict_types=1);

namespace KrZar\tests;

use KrZar\PhpArrayObjects\ArrayObject;
use KrZar\PhpArrayObjects\Mocks\ArrayObjectMock;
use KrZar\PhpArrayObjects\Mocks\NestedArrayObjectMock;
use PHPUnit\Framework\TestCase;

class ArrayObjectTest extends TestCase
{
    private ArrayObject $testedClass;

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


        $this->testedClass = ArrayObjectMock::create($data);
    }

    public function testBaseTypes()
    {
        $this->assertEquals('test', $this->testedClass->stringField);
        $this->assertEquals(10, $this->testedClass->intField);
        $this->assertEquals(10.55, $this->testedClass->floatField);
        $this->assertEquals(false, $this->testedClass->boolField);
        $this->assertEquals(['one' => 'oneValue'], $this->testedClass->arrayField);
    }

    public function testNested()
    {
        $this->assertInstanceOf(ArrayObjectMock::class, $this->testedClass->objectField);
        $this->assertEquals('test', $this->testedClass->objectField->stringField);
        $this->assertEquals(null, $this->testedClass->objectField->objectField);
    }

    public function testNestedMultidimensional()
    {
        $this->assertIsArray($this->testedClass->multidimensionalField);
        $this->assertInstanceOf(NestedArrayObjectMock::class, $this->testedClass->multidimensionalField[0]);
        $this->assertEquals('one', $this->testedClass->multidimensionalField[0]->field);
        $this->assertInstanceOf(NestedArrayObjectMock::class, $this->testedClass->multidimensionalField[1]);
        $this->assertEquals('two', $this->testedClass->multidimensionalField[1]->field);
    }

    public function testUnionTypes()
    {
        $this->assertInstanceOf(ArrayObjectMock::class, $this->testedClass->unionTypeFieldObject);
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
