<?php

namespace Hito\Core\Database\Repositories;

use Hito\Core\Database\Enums\SeederType;
use Illuminate\Support\Collection;

interface SeederRepository
{
    public function getAll(?SeederType $type = null): Collection;

    public function register(string $seederClass, SeederType $type): void;
}
