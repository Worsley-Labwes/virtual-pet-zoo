<?php

declare(strict_types=1);

namespace App\Zoo;

// A trait: reusable code you can paste into any class with `use Hungry;`
trait Hungry
{
    protected int $hunger = 50; // 0 = full, 100 = starving
    protected int $energy = 50; // 0 = exhausted, 100 = bursting with energy

    public function getHunger(): int
    {
        return $this->hunger;
    }

    public function getEnergy(): int
    {
        return $this->energy;
    }

    public function isHungry(): bool
    {
        return $this->hunger >= 70;
    }

    public function isTired(): bool
    {
        return $this->energy <= 30;
    }

    protected function eat(int $amount): void
    {
        $this->hunger = max(0, $this->hunger - $amount);
    }

    protected function burnEnergy(int $amount): void
    {
        $this->energy = max(0, $this->energy - $amount);
        $this->hunger = min(100, $this->hunger + intdiv($amount, 2)); // activity makes you hungry
    }

    protected function rest(int $amount): void
    {
        $this->energy = min(100, $this->energy + $amount);
    }
}
