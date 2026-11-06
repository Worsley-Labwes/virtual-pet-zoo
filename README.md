# 🐾 Virtual Pet Zoo

A small Laravel app for practising object-oriented PHP. Adopt pets, feed them, play with them and watch their moods change.

## OOP concepts in this project

| Concept                    | Where                                          |
| -------------------------- | ---------------------------------------------- |
| Abstract class             | `app/Zoo/Pet.php`                              |
| Inheritance and overriding | `Dog` (plays longer), `Cat` (picky eater)      |
| Polymorphism               | every pet answers `speak()` differently        |
| Interface                  | `CanFly`, implemented by `Dragon` and `Parrot` |
| Trait                      | `Hungry` (hunger and energy logic)             |
| Enum                       | `Mood` (Happy, Sleepy, Grumpy)                 |

## Run it locally

Requires PHP 8.3+ and Composer.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Then open http://127.0.0.1:8000.

## Roadmap

- [x] Milestone 1: OOP classes and a Laravel UI (pets kept in the session)
- [ ] Milestone 2: save pets in a database with Eloquent
