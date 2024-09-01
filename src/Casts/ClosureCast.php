<?php

namespace KrZar\ArrayDto\Casts;

use Closure;

class ClosureCast
{
    public function __construct(public readonly Closure $closure)
    {
    }
}
