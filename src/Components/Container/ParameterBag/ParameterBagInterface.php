<?php

namespace App\Components\Container\ParameterBag;

use App\Components\Container\Exception\Container\DuplicateException;
use App\Components\Container\Exception\Container\NotFoundException;

interface ParameterBagInterface
{
    /**
     * @throws NotFoundException
     */
    public function get(string $id): string|int|float|array|bool;

    /**
     * @throws DuplicateException
     */
    public function set(string $id, string|int|float|array|bool $value): void;

    public function has(string $id): bool;

    public function reset(): void;
}