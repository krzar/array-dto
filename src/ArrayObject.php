<?php

namespace KrZar\PhpArrayObjects;

use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

abstract class ArrayObject
{
    private const PROPERTIES_TO_IGNORE = ['arrayMap'];

    protected array $arrayMap = [];
    protected array $namesMap = [];

    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function create(array $data)
    {
        $class = get_called_class();

        return (new $class($data))->generate();
    }

    public function generate(): self
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $this->assignProperty($property);
        }

        return $this;
    }

    private function assignProperty(ReflectionProperty $property)
    {
        $name = $property->getName();

        if ($type = $this->getCorrectType($property)) {
            if ($type->isBuiltin()) {
                $this->assignBuildIn($name);
            } else {
                $this->assignCustom($name, $type);
            }
        }
    }

    private function assignBuildIn(string $name)
    {
        if ($className = $this->getArrayMapClass($name)) {
            $this->{$this->getCorrectPropertyName($name)} = Generator::generateMultiple(
                $className,
                $this->data[$name]
            );
        } else {
            $this->{$this->getCorrectPropertyName($name)} = $this->data[$name];
        }
    }

    private function assignCustom(string $name, ReflectionNamedType $type)
    {
        $className = $type->getName();
        $this->{$this->getCorrectPropertyName($name)} = Generator::generate(
            $className,
            $this->data[$name]
        );
    }

    private function getCorrectPropertyName(string $name): string
    {
        return $this->namesMap[$name] ?? $name;
    }

    private function isPropertyToAssign(string $name): bool
    {
        return !in_array($name, self::PROPERTIES_TO_IGNORE) && isset($this->data[$name]);
    }

    private function getArrayMapClass(string $name): ?string
    {
        return $this->arrayMap[$name] ?? null;
    }

    private function getCorrectType(ReflectionProperty $property): ?ReflectionNamedType
    {
        $name = $property->getName();

        if ($this->isPropertyToAssign($name)) {
            $type = $property->getType();

            if ($type instanceof ReflectionUnionType) {
                return $this->getCurrentTypeFromUnion($type, $this->data[$name]);
            }

            return $type;
        }

        return null;
    }

    #[Pure] private function getCurrentTypeFromUnion(
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
}
