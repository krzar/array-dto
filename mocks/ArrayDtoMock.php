<?php

namespace KrZar\ArrayDto\Mocks;

use KrZar\ArrayDto\ArrayDto;
use KrZar\ArrayDto\Casts\ClosureCast;
use KrZar\ArrayDto\Casts\MultidimensionalCast;
use KrZar\ArrayDto\Casts\NameCast;

class ArrayDtoMock extends ArrayDto
{
    public string $stringField;
    public int $intField = 0;
    public float $floatField = 0;
    public bool $boolField = false;
    public array $arrayField = [];
    public ArrayDtoMock|string $unionTypeFieldObject = '';
    public ArrayDtoMock|string $unionTypeFieldString = '';
    public string $mappedNameField = '';
    public int $mappedTypeField = 0;
    public ?ArrayDtoMock $objectField = null;
    public array $multidimensionalField = [];
    public int $customField = 0;

    protected function casts(): array
    {
        return array(
            'mapped_name_field' => new NameCast('mappedNameField'),
            'multidimensionalField' => new MultidimensionalCast(NestedArrayDtoMock::class),
            'customField' => new ClosureCast(fn(mixed $value, array $raw) => ($raw['intField'] ?? 0) + 5)
        );
    }
}
