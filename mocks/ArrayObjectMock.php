<?php

namespace KrZar\PhpArrayObjects\Mocks;

use KrZar\PhpArrayObjects\ArrayObject;

class ArrayObjectMock extends ArrayObject
{
    public string $stringField;
    public int $intField = 0;
    public float $floatField = 0;
    public bool $boolField = false;
    public array $arrayField = [];
    public ArrayObjectMock|string $unionTypeFieldObject = '';
    public ArrayObjectMock|string $unionTypeFieldString = '';
    public string $mappedNameField = '';
    public int $mappedTypeField = 0;
    public ?ArrayObjectMock $objectField = null;
    public array $multidimensionalField = [];

    protected array $arrayMap = [
        'multidimensionalField' => NestedArrayObjectMock::class
    ];

    protected array $namesMap = [
        'mappedNameField' => 'mapped_name_field'
    ];

    protected array $typesMap = [
        'mappedTypeField' => 'int'
    ];
}
