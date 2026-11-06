<?php

namespace App\Repositories;

use App\Models\PetRecord;
use App\Zoo\Pet;
use App\Zoo\PetFactory;

// The Repository pattern: the rest of the app asks for Pets and never
// needs to know that a database (or Eloquent) is involved.
class PetRepository
{
    /** @return Pet[] */
    public function all(): array
    {
        return PetRecord::orderBy('id')
            ->get()
            ->map(fn(PetRecord $record) => $this->toPet($record))
            ->all();
    }

    public function find(int $id): ?Pet
    {
        $record = PetRecord::find($id);

        return $record ? $this->toPet($record) : null;
    }

    // Inserts a new pet, or updates it if it already has an id.
    public function save(Pet $pet): Pet
    {
        $data = $pet->toArray();

        $record = $pet->getId() ? PetRecord::findOrFail($pet->getId()) : new PetRecord();
        $record->type = $data['type'];
        $record->name = $data['name'];
        $record->hunger = $data['hunger'];
        $record->energy = $data['energy'];
        $record->mood = $data['mood'];
        $record->save();

        $pet->setId((int) $record->id);

        return $pet;
    }

    public function delete(Pet $pet): void
    {
        if ($pet->getId()) {
            PetRecord::whereKey($pet->getId())->delete();
        }
    }

    public function deleteAll(): void
    {
        PetRecord::query()->delete();
    }

    public function createStarterPets(): void
    {
        $starters = [
            ['dog', 'Buddy'],
            ['cat', 'Luna'],
            ['dragon', 'Ember'],
            ['parrot', 'Kiwi'],
        ];

        foreach ($starters as [$type, $name]) {
            $this->save(PetFactory::make($type, $name));
        }
    }

    private function toPet(PetRecord $record): Pet
    {
        return PetFactory::fromArray([
            'id' => $record->id,
            'type' => $record->type,
            'name' => $record->name,
            'hunger' => $record->hunger,
            'energy' => $record->energy,
            'mood' => $record->mood,
        ]);
    }
}
