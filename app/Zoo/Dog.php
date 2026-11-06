<?php

declare(strict_types=1);

namespace App\Zoo;

class Dog extends Pet
{
    public function speak(): string
    {
        return 'Woof! Woof!';
    }

    public function favoriteFood(): string
    {
        return 'a juicy bone';
    }

    // Overriding: dogs play differently, so they tire out slower.
    public function play(): string
    {
        if ($this->isTired()) {
            return parent::play(); // reuse the parent's "too tired" message
        }

        $this->burnEnergy(15);
        $this->refreshMood();

        return "{$this->name} fetches the ball again and again! 🎾 {$this->mood->emoji()}";
    }
}
