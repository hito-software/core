<?php

namespace Hito\Core\Database\Services;

use Hito\Core\Database\Enums\SeederType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

interface SeederService
{
    public function getAll(?SeederType $type = null): Collection;

    public function register(string $seederClass, SeederType $type): void;
}
