<?php

declare(strict_types=1);

namespace App\Zoo;

class Parrot extends Pet implements CanFly
{
    public function speak(): string
    {
        return 'Polly wants a cracker!';
    }

    public function favoriteFood(): string
    {
        return 'sunflower seeds';
    }

    public function fly(): string
    {
        $this->burnEnergy(10);
        $this->refreshMood();

        return "{$this->name} flutters around the room. 🦜";
    }
}
