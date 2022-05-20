<?php

namespace Hito\Core\Module\Services;

use Hito\Core\Module\Repositories\HookRepository;

class HookServiceImpl implements HookService
{
    public function __construct(private HookRepository $hookRepository)
    {
    }

    public function add(string $name, callable $callback): void
    {
        $this->hookRepository->add($name, $callback);
    }

    public function get(string $name): ?string
    {
        return $this->hookRepository->get($name);
    }
}
