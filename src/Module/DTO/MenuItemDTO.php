<?php

namespace Hito\Core\Module\DTO;

class MenuItemDTO
{
    public function __construct(public string            $name,
                                public string            $route,
                                public string            $icon,
                                public string|array|null $permission = null,
                                public bool              $isActive = false)
    {
    }
}
