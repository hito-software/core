<?php

namespace Hito\Core\Module\Repositories;

use Hito\Core\Module\DTO\MenuDTO;
use Illuminate\Support\Collection;

interface MenuRepository
{
    public function getAll(?string $group = null): Collection;

    public function getById(string $id, ?string $group = null): ?MenuDTO;

    public function add(MenuDTO $menu): void;
}
