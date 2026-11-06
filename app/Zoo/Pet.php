<?php

declare(strict_types=1);

namespace App\Zoo;

// An abstract class: a blueprint that can't be created directly.
abstract class Pet
{
    use Hungry;

    protected Mood $mood = Mood::Happy;

    public function __construct(protected string $name) {}

    // Every child MUST implement these. This is where polymorphism comes from.
    abstract public function speak(): string;

    abstract public function favoriteFood(): string;

    public function feed(): string
    {
        $this->eat(30);
        $this->refreshMood();

        return "{$this->name} munches on {$this->favoriteFood()}. {$this->mood->emoji()}";
    }

    public function play(): string
    {
        if ($this->isTired()) {
            return "{$this->name} is too tired to play... 💤";
        }

        $this->burnEnergy(25);
        $this->refreshMood();

        return "{$this->name} plays happily! {$this->mood->emoji()}";
    }

    public function sleep(): string
    {
        $this->rest(40);
        $this->refreshMood();

        return "{$this->name} takes a nap. {$this->mood->emoji()}";
    }

    protected function refreshMood(): void
    {
        $this->mood = match (true) {
            $this->isHungry() => Mood::Grumpy,
            $this->isTired() => Mood::Sleepy,
            default => Mood::Happy,
        };
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMood(): Mood
    {
        return $this->mood;
    }

    public function getType(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    // NEW: turn a pet into plain data so Laravel can keep it in the session...
    public function toArray(): array
    {
        return [
            'type' => strtolower($this->getType()),
            'name' => $this->name,
            'hunger' => $this->hunger,
            'energy' => $this->energy,
            'mood' => $this->mood->value,
        ];
    }

    // NEW: ...and rebuild it later. `new static` creates whichever child class
    // was called: Dog::fromArray() gives a Dog, Cat::fromArray() gives a Cat.
    public static function fromArray(array $data): static
    {
        $pet = new static($data['name']);
        $pet->hunger = (int) $data['hunger'];
        $pet->energy = (int) $data['energy'];
        $pet->mood = Mood::from($data['mood']);

        return $pet;
    }

    public function status(): string
    {
        return sprintf(
            '%s the %s %s | hunger %d | energy %d',
            $this->name,
            $this->getType(),
            $this->mood->emoji(),
            $this->hunger,
            $this->energy
        );
    }
}
