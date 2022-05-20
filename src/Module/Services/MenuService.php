<?php

namespace Hito\Core\Module\Services;

use Hito\Core\Module\DTO\MenuDTO;
use Hito\Platform\Models\User;
use Illuminate\Support\Collection;

interface MenuService
{
    public function getAll(?string $group = null): Collection;

    public function getAllByUser(User $user, ?string $group = null): Collection;

    public function getById(string $id, ?string $group = null): ?MenuDTO;

    public function add(MenuDTO $menu): void;
}
