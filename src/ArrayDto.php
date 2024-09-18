<?php

namespace KrZar\ArrayDto;

use BackedEnum;
use KrZar\ArrayDto\Casts\Cast;
use KrZar\ArrayDto\Casts\ClosureCast;
use KrZar\ArrayDto\Casts\MultidimensionalCast;
use KrZar\ArrayDto\Casts\NameCast;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

abstract class ArrayDto
{
    private const PROPERTIES_TO_IGNORE = ['castedNames', 'multidimensionalCast', 'closureCast'];

    /** @var NameCast[] */
    private array $castedNames = [];

    /** @var MultidimensionalCast[] */
    private array $multidimensionalCast = [];

    /** @var ClosureCast[] */
    private array $closureCast = [];

    private array $_raw;

    public function __construct(array $_raw)
    {
        $this->_raw = $_raw;
    }

    public static function create(array $data): static
    {
        return (new static($data))->generate();
    }

    public function generate(): static
    {
        $this->prepareCasts();

        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $this->assignProperty($property);
        }

        return $this;
    }

    public function getRaw(): array
    {
        return $this->_raw;
    }

    protected function casts(): array
    {
        return [];
    }

    private function assignProperty(ReflectionProperty $property): void
    {
        $name = $property->getName();

        if ($type = $this->getCorrectType($property)) {
            if ($this->isArrayDto($type)) {
                $this->assignArrayDto($name, $type);
            } else {
                $this->assignOther($name, $type);
            }
        }
    }

    private function assignOther(string $name, ReflectionNamedType $type): void
    {
        if ($className = $this->getArrayDtoClass($name)) {
            $value = Generator::generateMultiple($className, $this->getValueByName($name));
        } else {
            $value = $this->getValueByName($name);
        }

        if ($type->isBuiltin()) {
            settype($value, $type->getName());
        }

        if (enum_exists($type->getName())) {
            /** @var BackedEnum $enum */
            $enum = $type->getName();
            $value = $enum::from($value);
        }

        $this->{$name} = $this->applyClosureCast($name, $value);
    }

    private function assignArrayDto(string $name, ReflectionNamedType $type): void
    {
        $className = $type->getName();
        $value = $this->getValueByName($name);

        if ($value instanceof ArrayDto) {
            $this->{$name} = $value;
        } else {
            $this->{$name} = Generator::generate($className, $value);
        }
    }

    private function isPropertyToAssign(string $name): bool
    {
        if (in_array($name, self::PROPERTIES_TO_IGNORE)) {
            return false;
        }

        return $this->isDataSet($name) || isset($this->closureCast[$name]);
    }

    private function isDataSet(string $name): bool
    {
        return isset($this->_raw[$this->getCorrectItemName($name)]);
    }

    private function getValueByName(string $name): mixed
    {
        return $this->_raw[$this->getCorrectItemName($name)] ?? null;
    }

    private function getCorrectItemName(string $name): string
    {
        if (isset($this->castedNames[$name])) {
            return $this->castedNames[$name]->name;
        }

        return $name;
    }

    private function getArrayDtoClass(string $name): ?string
    {
        if (isset($this->multidimensionalCast[$name])) {
            return $this->multidimensionalCast[$name]->className;
        }

        return null;
    }

    private function getCorrectType(ReflectionProperty $property): ?ReflectionNamedType
    {
        $name = $property->getName();

        if ($this->isPropertyToAssign($name)) {
            $type = $property->getType();

            if ($type instanceof ReflectionUnionType) {
                return $this->getCurrentTypeFromUnion($type, $this->getValueByName($name));
            }

            return $type;
        }

        return null;
    }

    private function getCurrentTypeFromUnion(
        ReflectionUnionType $unionType,
        mixed               $dataItem
    ): ?ReflectionNamedType
    {
        if (is_array($dataItem)) {
            foreach ($unionType->getTypes() as $type) {
                if (!$type->isBuiltin()) {
                    return $type;
                }
            }
        } else {
            foreach ($unionType->getTypes() as $type) {
                if ($type->isBuiltin()) {
                    return $type;
                }
            }
        }

        return null;
    }

    private function isArrayDto(ReflectionNamedType $type): bool
    {
        $className = $type->getName();

        if (class_exists($className) && !enum_exists($className)) {
            return is_subclass_of($className, ArrayDto::class);
        }

        return false;
    }

    private function prepareCasts(): void
    {
        foreach ($this->casts() as $field => $value) {
            if (is_array($value)) {
                foreach ($value as $cast) {
                    $this->assignCast($field, $cast);
                }

                continue;
            }

            $this->assignCast($field, $value);
        }
    }

    private function applyClosureCast(string $name, mixed $value): mixed
    {
        if (isset($this->closureCast[$name])) {
            $closure = $this->closureCast[$name]->closure;

            return $closure($value, $this->_raw);
        }

        return $value;
    }

    private function assignCast(string $field, Cast $cast): void
    {
        match (get_class($cast)) {
            NameCast::class => $this->castedNames[$field] = $cast,
            MultidimensionalCast::class => $this->multidimensionalCast[$field] = $cast,
            ClosureCast::class => $this->closureCast[$field] = $cast,
        };
    }
}
