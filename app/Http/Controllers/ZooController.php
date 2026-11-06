<?php

namespace App\Http\Controllers;

use App\Zoo\CanFly;
use App\Zoo\Cat;
use App\Zoo\Dog;
use App\Zoo\Dragon;
use App\Zoo\Parrot;
use App\Zoo\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZooController extends Controller
{
    // Maps the text from the form ("dragon") to the class that does the work.
    private const TYPES = [
        'dog' => Dog::class,
        'cat' => Cat::class,
        'dragon' => Dragon::class,
        'parrot' => Parrot::class,
    ];

    private const ACTIONS = ['feed', 'play', 'sleep', 'fly'];

    public function index(): View
    {
        return view('zoo', ['pets' => $this->pets()]);
    }

    public function adopt(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'type' => ['required', 'in:' . implode(',', array_keys(self::TYPES))],
        ]);

        $class = self::TYPES[$data['type']];

        $pets = $this->pets();
        $pets[] = new $class($data['name']);
        $this->save($pets);

        return redirect()->route('zoo')->with('message', "{$data['name']} joined the zoo! 🎉");
    }

    public function act(int $index, string $action): RedirectResponse
    {
        abort_unless(in_array($action, self::ACTIONS, true), 404);

        $pets = $this->pets();
        abort_unless(isset($pets[$index]), 404);

        $pet = $pets[$index];

        // The interface at work: only pets that CanFly are allowed to fly.
        abort_if($action === 'fly' && !($pet instanceof CanFly), 404);

        $message = $pet->{$action}(); // calls feed(), play(), sleep() or fly()

        $this->save($pets);

        return redirect()->route('zoo')->with('message', $message);
    }

    public function reset(): RedirectResponse
    {
        session()->forget('pets');

        return redirect()->route('zoo')->with('message', 'The zoo has been reset.');
    }

    /** @return Pet[] */
    private function pets(): array
    {
        $saved = session('pets');

        if ($saved === null) {
            $pets = [
                new Dog('Buddy'),
                new Cat('Luna'),
                new Dragon('Ember'),
                new Parrot('Kiwi'),
            ];
            $this->save($pets);

            return $pets;
        }

        return array_map(function (array $data): Pet {
            $class = self::TYPES[$data['type']];

            return $class::fromArray($data);
        }, $saved);
    }

    /** @param Pet[] $pets */
    private function save(array $pets): void
    {
        session(['pets' => array_map(fn(Pet $pet) => $pet->toArray(), $pets)]);
    }
}
