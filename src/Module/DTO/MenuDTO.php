<?php

namespace Hito\Core\Module\DTO;

use Illuminate\Support\Collection;

class MenuDTO
{
    public Collection $items;

    public function __construct(public string $id,
                                public string $name,
                                public string $icon,
                                public ?string $route = null,
                                public ?int $order = null,
                                public ?string $permission = null,
                                public ?string $group = null)
    {
        $this->items = collect([]);
    }

    public function addItem(MenuItemDTO $item): void
    {
        $this->items->push($item);
    }
}
