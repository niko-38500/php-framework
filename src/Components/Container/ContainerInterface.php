<?php

declare(strict_types=1);

namespace App\Components\Container;

use App\Components\Container\Exception\Container\DuplicateException;
use App\Components\Container\Exception\Container\NotFoundException;

interface ContainerInterface
{

    /**
     * Get a service from the container
     *
     * @template T of object
     * @param class-string<T> $service
     * @return T & object
     *
     * @throws NotFoundException
     */
    public function get(string $service): object;


    /**
     * Define a service into the container
     *
     * @template T of object
     * @param class-string<T> $id
     * @param object & T $service
     *
     * @throws DuplicateException
     */
    public function set(string $id, object $service): void;

    /**
     * check if container has specified service
     */
    public function has(string $service): bool;

    /**
     * Get a parameter from the parameter bag
     *
     * @throws NotFoundException
     */
    public function getParameter(string $parameter): string|int|float|array|bool;

    /**
     * Set a parameter into the parameter bag if you defined a parameter twice it will be overwritten
     *
     * @throws DuplicateException
     */
    public function setParameter(string $key, string|int|float|array|bool $value): void;

    /**
     * Check if parameter bag has parameter
     */
    public function hasParameter(string $parameter): bool;

    /**
     * Reset the container and the parameter bag
     */
    public function reset(): void;
}