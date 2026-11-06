<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Virtual Pet Zoo</title>
    <style>
        :root {
            --bg: #14161c;
            --card: #1e2230;
            --text: #eceff4;
            --muted: #8b93a7;
            --accent: #7c9cff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        main {
            max-width: 960px;
            margin: 0 auto;
            padding: 2rem 1rem 4rem;
        }

        h1 {
            margin: 0 0 1rem;
        }

        .flash {
            background: #26304d;
            border-left: 4px solid var(--accent);
            padding: .75rem 1rem;
            border-radius: 6px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .empty {
            grid-column: 1 / -1;
            color: var(--muted);
        }

        .card {
            background: var(--card);
            border-radius: 12px;
            padding: 1.25rem;
        }

        .icon {
            font-size: 3rem;
            line-height: 1;
        }

        .card h2 {
            margin: .5rem 0 0;
            font-size: 1.2rem;
        }

        .meta {
            color: var(--muted);
            font-size: .85rem;
            margin: .15rem 0 .75rem;
        }

        .says {
            font-style: italic;
            margin: 0 0 .9rem;
        }

        .badge {
            background: #2f3b66;
            color: #b9c8ff;
            font-size: .7rem;
            padding: .15rem .5rem;
            border-radius: 99px;
        }

        .stat {
            font-size: .75rem;
            color: var(--muted);
            margin-top: .5rem;
        }

        .bar {
            background: #2b3040;
            border-radius: 99px;
            height: 8px;
            overflow: hidden;
        }

        .bar>span {
            display: block;
            height: 100%;
        }

        .hunger>span {
            background: #ff8a65;
        }

        .energy>span {
            background: #66d9a0;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            margin-top: 1rem;
        }

        button,
        select,
        input {
            font: inherit;
            border-radius: 8px;
            border: 1px solid #343a4f;
            background: #262b3b;
            color: var(--text);
            padding: .45rem .8rem;
        }

        button {
            cursor: pointer;
        }

        button:hover {
            background: var(--accent);
            color: #0d1020;
            border-color: var(--accent);
        }

        .release {
            color: #ff9b9b;
        }

        .adopt {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            align-items: center;
        }

        .error {
            color: #ff8a8a;
            font-size: .85rem;
        }

        .reset {
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <main>
        <h1>🐾 Virtual Pet Zoo</h1>

        @if (session('message'))
        <p class="flash">{{ session('message') }}</p>
        @endif

        @php
        $icons = ['Dog' => '🐶', 'Cat' => '🐱', 'Dragon' => '🐉', 'Parrot' => '🦜'];
        @endphp

        <div class="grid">
            @forelse ($pets as $pet)
            <article class="card">
                <div class="icon">{{ $icons[$pet->getType()] ?? '🐾' }}</div>
                <h2>
                    {{ $pet->getName() }}
                    @if ($pet instanceof \App\Zoo\CanFly)
                    <span class="badge">can fly</span>
                    @endif
                </h2>
                <p class="meta">
                    {{ $pet->getType() }} · {{ $pet->getMood()->emoji() }} {{ $pet->getMood()->name }}
                    · loves {{ $pet->favoriteFood() }}
                </p>
                <p class="says">“{{ $pet->speak() }}”</p>

                <div class="stat">Hunger {{ $pet->getHunger() }}</div>
                <div class="bar hunger"><span style="width: {{ $pet->getHunger() }}%"></span></div>
                <div class="stat">Energy {{ $pet->getEnergy() }}</div>
                <div class="bar energy"><span style="width: {{ $pet->getEnergy() }}%"></span></div>

                <div class="actions">
                    @foreach (['feed' => 'Feed 🍖', 'play' => 'Play 🎾', 'sleep' => 'Sleep 💤'] as $action => $label)
                    <form method="post" action="{{ route('zoo.act', [$pet->getId(), $action]) }}">
                        @csrf
                        <button type="submit">{{ $label }}</button>
                    </form>
                    @endforeach

                    @if ($pet instanceof \App\Zoo\CanFly)
                    <form method="post" action="{{ route('zoo.act', [$pet->getId(), 'fly']) }}">
                        @csrf
                        <button type="submit">Fly 🪽</button>
                    </form>
                    @endif

                    <form method="post" action="{{ route('zoo.release', $pet->getId()) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="release">Release 👋</button>
                    </form>
                </div>
            </article>
            @empty
            <p class="empty">The zoo is empty. Adopt a pet below, or press “Reset zoo” to bring back the starters.</p>
            @endforelse
        </div>

        <h2>Adopt a new pet</h2>
        <form method="post" action="{{ route('zoo.adopt') }}" class="adopt">
            @csrf
            <input type="text" name="name" placeholder="Name" maxlength="20" value="{{ old('name') }}" required>
            <select name="type">
                <option value="dog">Dog</option>
                <option value="cat">Cat</option>
                <option value="dragon">Dragon</option>
                <option value="parrot">Parrot</option>
            </select>
            <button type="submit">Adopt</button>
        </form>
        @error('name') <p class="error">{{ $message }}</p> @enderror
        @error('type') <p class="error">{{ $message }}</p> @enderror

        <form method="post" action="{{ route('zoo.reset') }}" class="reset">
            @csrf
            <button type="submit">Reset zoo</button>
        </form>
    </main>
</body>

</html>