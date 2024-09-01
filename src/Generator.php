<?php

namespace KrZar\ArrayDto;

class Generator
{
    public static function generate(string|ArrayDto $class, array $data): ArrayDto
    {
        return $class::create($data);
    }

    public static function generateMultiple(string|ArrayDto $class, array $dataArray): array
    {
        foreach($dataArray as $data) {
            $array[] = self::generate($class, $data);
        }

        return $array ?? [];
    }
}
