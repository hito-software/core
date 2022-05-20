<?php

namespace Hito\Core\Module\Services;

use Hito\Core\Module\DTO\MenuDTO;
use Hito\Platform\Models\User;
use Hito\Core\Module\Repositories\MenuRepository;
use Illuminate\Support\Collection;
use Route;

class MenuServiceImpl implements MenuService
{
    public function __construct(private MenuRepository $menuRepository)
    {
    }

    public function getAll(?string $group = null): Collection
    {
        return $this->menuRepository->getAll($group);
    }

    public function getAllByUser(User $user, ?string $group = null): Collection
    {
        return $this->getAll($group)->filter(function ($parent) use ($user) {
            $parent->items = $parent->items->filter(function ($child) use ($user) {
                if (empty($child->permission)) {
                    return true;
                }

                if (is_array($child->permission) && count($child->permission) === 2) {
                    return $user->can($child->permission[0], $child->permission[1]);
                }

                return $user->can($child->permission);
            })->map(function ($child) {
                $child->isActive = Route::is($child->route);

                return $child;
            });

            if (empty($parent->route) && !$parent->items->count()) {
                return false;
            }

            if (empty($parent->permission)) {
                return true;
            }

            return $user->can($parent->permission);
        });
    }

    public function getById(string $id, ?string $group = null): ?MenuDTO
    {
        return $this->menuRepository->getById($id, $group);
    }

    public function add(MenuDTO $menu): void
    {
        $this->menuRepository->add($menu);
    }
}
