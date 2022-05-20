<?php

namespace Hito\Core\Module\Repositories;

class HookRepositoryImpl implements HookRepository
{
    private array $list = [];

    public function add(string $name, callable $callback): void
    {
        $this->list[$name][] = $callback;
    }

    public function get(string $name): ?string
    {
        if (empty($this->list[$name])) {
            return null;
        }

        $response = [];

        foreach($this->list[$name] as $callback) {
            $response[] = $callback();
        }

        return implode(PHP_EOL, $response);
    }
}
