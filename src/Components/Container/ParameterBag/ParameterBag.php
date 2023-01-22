<?php

declare(strict_types=1);

namespace App\Components\Container\ParameterBag;

use App\Components\Container\Exception\Container\DuplicateException;
use App\Components\Container\Exception\Container\NotFoundException;

class ParameterBag implements ParameterBagInterface
{
    /** @var (bool|string|int|float|array)[] */
    private array $parameters;

    public function get(string $id): string|int|float|array|bool
    {
        if (!$this->has($id)) {
            throw new NotFoundException(sprintf('Parameter %s does not exists or is not registered', $id));
        }

        return $this->parameters[$id];
    }

    public function set(string $id, float|int|array|string|bool $value): void
    {
        if ($this->has($id)) {
            throw new DuplicateException(sprintf('Parameter %s is already registered', $id));
        }

        $this->parameters[$id] = $value;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->parameters);
    }

    public function reset(): void
    {
        $this->parameters = [];
    }
}