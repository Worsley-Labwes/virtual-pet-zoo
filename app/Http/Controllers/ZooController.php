<?php

namespace App\Http\Controllers;

use App\Repositories\PetRepository;
use App\Zoo\CanFly;
use App\Zoo\PetFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ZooController extends Controller
{
    private const ACTIONS = ['feed', 'play', 'sleep', 'fly'];

    // Laravel sees the type-hint and passes in a PetRepository for us.
    public function __construct(private PetRepository $pets) {}

    public function index(): View
    {
        return view('zoo', ['pets' => $this->pets->all()]);
    }

    public function adopt(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'type' => ['required', Rule::in(PetFactory::types())],
        ]);

        $this->pets->save(PetFactory::make($data['type'], $data['name']));

        return redirect()->route('zoo')->with('message', "{$data['name']} joined the zoo! 🎉");
    }

    public function act(int $id, string $action): RedirectResponse
    {
        abort_unless(in_array($action, self::ACTIONS, true), 404);

        $pet = $this->pets->find($id);
        abort_if($pet === null, 404);

        // The interface at work: only pets that CanFly are allowed to fly.
        abort_if($action === 'fly' && ! ($pet instanceof CanFly), 404);

        $message = $pet->{$action}(); // calls feed(), play(), sleep() or fly()

        $this->pets->save($pet);

        return redirect()->route('zoo')->with('message', $message);
    }

    public function release(int $id): RedirectResponse
    {
        $pet = $this->pets->find($id);
        abort_if($pet === null, 404);

        $this->pets->delete($pet);

        return redirect()->route('zoo')->with('message', "{$pet->getName()} was released into the wild. 👋");
    }

    public function reset(): RedirectResponse
    {
        $this->pets->deleteAll();
        $this->pets->createStarterPets();

        return redirect()->route('zoo')->with('message', 'The zoo has been reset.');
    }
}
