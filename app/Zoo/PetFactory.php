<?php

declare(strict_types=1);

namespace App\Zoo;

// The Factory pattern: one place that knows which class belongs to which type name.
class PetFactory
{
    private const TYPES = [
        'dog' => Dog::class,
        'cat' => Cat::class,
        'dragon' => Dragon::class,
        'parrot' => Parrot::class,
    ];

    /** @return string[] */
    public static function types(): array
    {
        return array_keys(self::TYPES);
    }

    // Brand-new pet: PetFactory::make('dragon', 'Ember') gives a Dragon.
    public static function make(string $type, string $name): Pet
    {
        $class = self::TYPES[$type]
            ?? throw new \InvalidArgumentException("Unknown pet type: {$type}");

        return new $class($name);
    }

    // Rebuild a saved pet from stored data.
    public static function fromArray(array $data): Pet
    {
        $class = self::TYPES[$data['type']]
            ?? throw new \InvalidArgumentException("Unknown pet type: {$data['type']}");

        return $class::fromArray($data);
    }
}
