<?php

namespace Hito\Core\Database\Repositories;

use Hito\Core\Database\Enums\SeederType;
use Hito\Core\Database\Exceptions\InvalidDatabaseSeederException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class SeederRepositoryImpl implements SeederRepository
{
    private static Collection $seeders;

    public function __construct()
    {
        if (!isset(self::$seeders)) {
            self::$seeders = collect(); // @phpstan-ignore-line
        }
    }

    public function getAll(?SeederType $type = null): Collection
    {
        $seeders = self::$seeders;

        if (!is_null($type)) {
            $seeders = $seeders->filter(fn($item) => $item['type'] === $type);
        }

        return $seeders->map(fn($data) => $data['seeder']);
    }

    public function register(string $seederClass, SeederType $type): void
    {
        if (!is_subclass_of($seederClass, Seeder::class)) {
            $parentClass = Seeder::class;
            throw new InvalidDatabaseSeederException("Class `{$seederClass}` should `{$parentClass}`");
        }

        $item = [
            'seeder' => $seederClass,
            'type' => $type
        ];

        if (self::$seeders->contains($item)) {
            return;
        }

        self::$seeders->push($item);
    }
}
