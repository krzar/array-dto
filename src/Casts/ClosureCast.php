<?php

namespace KrZar\ArrayDto\Casts;

use Closure;

class ClosureCast implements Cast
{
    public function __construct(public readonly Closure $closure)
    {
    }
}
