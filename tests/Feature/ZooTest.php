<?php

namespace Tests\Feature;

use App\Repositories\PetRepository;
use App\Zoo\Pet;
use App\Zoo\PetFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZooTest extends TestCase
{
    use RefreshDatabase;

    private function givenPet(string $type, string $name): Pet
    {
        return app(PetRepository::class)->save(PetFactory::make($type, $name));
    }

    public function test_the_zoo_page_lists_saved_pets(): void
    {
        app(PetRepository::class)->createStarterPets();

        $this->get('/')
            ->assertOk()
            ->assertSee('Buddy')
            ->assertSee('Ember');
    }

    public function test_an_empty_zoo_shows_a_friendly_message(): void
    {
        $this->get('/')->assertOk()->assertSee('The zoo is empty');
    }

    public function test_adopting_a_pet_saves_it_to_the_database(): void
    {
        $this->post('/adopt', ['name' => 'Rex', 'type' => 'dog'])->assertRedirect(route('zoo'));

        $this->assertDatabaseHas('pets', ['name' => 'Rex', 'type' => 'dog']);
    }

    public function test_an_unknown_pet_type_is_rejected(): void
    {
        $this->post('/adopt', ['name' => 'Rex', 'type' => 'unicorn'])->assertSessionHasErrors('type');

        $this->assertDatabaseCount('pets', 0);
    }

    public function test_feeding_updates_the_stored_hunger(): void
    {
        $dog = $this->givenPet('dog', 'Rex'); // starts at hunger 50

        $this->post("/pets/{$dog->getId()}/feed")->assertRedirect(route('zoo'));

        $this->assertDatabaseHas('pets', ['id' => $dog->getId(), 'hunger' => 20, 'mood' => 'happy']);
    }

    public function test_a_cat_refuses_food_when_not_hungry(): void
    {
        $cat = $this->givenPet('cat', 'Luna');

        $this->post("/pets/{$cat->getId()}/feed")->assertRedirect(route('zoo'));

        $this->assertDatabaseHas('pets', ['id' => $cat->getId(), 'hunger' => 50]);
    }

    public function test_only_pets_that_can_fly_may_fly(): void
    {
        $dog = $this->givenPet('dog', 'Rex');
        $dragon = $this->givenPet('dragon', 'Ember');

        $this->post("/pets/{$dog->getId()}/fly")->assertNotFound();

        $this->post("/pets/{$dragon->getId()}/fly")->assertRedirect(route('zoo'));
        $this->assertDatabaseHas('pets', ['id' => $dragon->getId(), 'energy' => 40]);
    }

    public function test_releasing_a_pet_deletes_it(): void
    {
        $dog = $this->givenPet('dog', 'Rex');

        $this->delete("/pets/{$dog->getId()}")->assertRedirect(route('zoo'));

        $this->assertDatabaseMissing('pets', ['id' => $dog->getId()]);
    }

    public function test_reset_brings_back_the_four_starter_pets(): void
    {
        $this->givenPet('dog', 'Rex');

        $this->post('/reset')->assertRedirect(route('zoo'));

        $this->assertDatabaseCount('pets', 4);
        $this->assertDatabaseMissing('pets', ['name' => 'Rex']);
    }
}
