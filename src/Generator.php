<?php

namespace KrZar\PhpArrayObjects;

class Generator
{
    public static function generate(string|ArrayObject $class, array $data): ArrayObject
    {
        return $class::create($data);
    }

    public static function generateMultiple(string|ArrayObject $class, array $dataArray): array
    {
        foreach($dataArray as $data) {
            $array[] = self::generate($class, $data);
        }

        return $array ?? [];
    }
}
