<?php

namespace Hito\Core\Module\Repositories;

interface HookRepository
{
    public function add(string $name, callable $callback): void;

    public function get(string $name): ?string;
}
