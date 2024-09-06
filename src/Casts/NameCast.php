<?php

namespace KrZar\ArrayDto\Casts;

class NameCast implements Cast
{
    public function __construct(
        public readonly string $name
    ) {}
}
