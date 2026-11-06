<?php

namespace Database\Seeders;

use App\Repositories\PetRepository;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    // Laravel hands us a PetRepository automatically (dependency injection).
    public function run(PetRepository $pets): void
    {
        $pets->createStarterPets();
    }
}
