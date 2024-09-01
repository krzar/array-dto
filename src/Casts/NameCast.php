<?php

namespace KrZar\ArrayDto\Casts;

class NameCast
{
    public function __construct(
        public readonly string $name
    ) {}
}
