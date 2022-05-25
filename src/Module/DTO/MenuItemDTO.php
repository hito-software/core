<?php

namespace Hito\Core\Module\DTO;

class MenuItemDTO
{
    public string $route;
    public mixed $routeParams = [];

    public function __construct(public string            $name,
                                string|array      $route,
                                public string            $icon,
                                public string|array|null $permission = null,
                                public bool              $isActive = false)
    {
        if (is_array($route) && count($route) === 2) {
            $this->route = $route[0];
            $this->routeParams = $route[1];
        } elseif(is_string($route)) {
            $this->route = $route;
        }
    }
}
