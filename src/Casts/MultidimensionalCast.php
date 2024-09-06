<?php

namespace KrZar\ArrayDto\Casts;

class MultidimensionalCast implements Cast
{
    public function __construct(public readonly string $className)
    {}
}
