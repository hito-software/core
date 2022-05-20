<?php

namespace Hito\Core\Module\Services;

interface HookService
{
    public function add(string $name, callable $callback): void;

    public function get(string $name): ?string;
}
