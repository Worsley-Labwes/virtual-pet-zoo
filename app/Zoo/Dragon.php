<?php

declare(strict_types=1);

namespace App\Zoo;

class Dragon extends Pet implements CanFly
{
    public function speak(): string
    {
        return 'ROOOAR! 🔥';
    }

    public function favoriteFood(): string
    {
        return 'a whole roasted ox';
    }

    public function fly(): string
    {
        $this->burnEnergy(10);
        $this->refreshMood();

        return "{$this->name} soars above the clouds. 🐉";
    }
}
