<?php

declare(strict_types=1);

namespace App\Zoo;

// An enum: a fixed set of allowed values. A pet can only ever be one of these.
enum Mood: string
{
    case Happy = 'happy';
    case Sleepy = 'sleepy';
    case Grumpy = 'grumpy';

    public function emoji(): string
    {
        return match ($this) {
            self::Happy => '😄',
            self::Sleepy => '😴',
            self::Grumpy => '😠',
        };
    }
}
