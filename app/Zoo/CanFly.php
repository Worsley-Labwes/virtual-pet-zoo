<?php

declare(strict_types=1);

namespace App\Zoo;

// An interface: a promise. Any class that implements CanFly MUST have fly().
interface CanFly
{
    public function fly(): string;
}
