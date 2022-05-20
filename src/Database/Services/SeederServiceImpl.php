<?php

namespace Hito\Core\Database\Services;

use Hito\Core\Database\Enums\SeederType;
use Hito\Core\Database\Repositories\SeederRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class SeederServiceImpl implements SeederService
{
    public function __construct(private SeederRepository $seederRepository)
    {
    }

    public function getAll(?SeederType $type = null): Collection
    {
        return $this->seederRepository->getAll($type);
    }

    public function register(string $seederClass, SeederType $type): void
    {
        $this->seederRepository->register($seederClass, $type);
    }
}
