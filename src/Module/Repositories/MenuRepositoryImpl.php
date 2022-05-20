<?php

namespace Hito\Core\Module\Repositories;

use Hito\Core\Module\DTO\MenuDTO;
use Illuminate\Support\Collection;

class MenuRepositoryImpl implements MenuRepository
{
    private static Collection $menus;

    public function __construct()
    {
        self::$menus = collect([]);
    }

    public function getAll(?string $group = null): Collection
    {
        return self::$menus->filter(fn(MenuDTO $menu) => $menu->group === $group)
            ->sortBy(fn(MenuDTO $menu) => $menu->order);
    }

    public function getById(string $id, ?string $group = null): ?MenuDTO
    {
        return self::$menus->filter(fn(MenuDTO $menu) => $menu->id === $id && $menu->group === $group)->first();
    }

    public function add(MenuDTO $menu): void
    {
        if ($existing = $this->getById($menu->id, $menu->group)) {
            $existing->items = $existing->items->merge($menu->items);
        } else {
            self::$menus->push($menu);
        }
    }
}
