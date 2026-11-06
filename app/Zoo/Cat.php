<?php

declare(strict_types=1);

namespace App\Zoo;

class Cat extends Pet
{
    public function speak(): string
    {
        return 'Meow... (purrrr)';
    }

    public function favoriteFood(): string
    {
        return 'tuna';
    }

    // Overriding: cats are picky and only eat when they're properly hungry.
    public function feed(): string
    {
        if (!$this->isHungry()) {
            return "{$this->name} sniffs the food and walks away. 😼";
        }

        return parent::feed();
    }
}
