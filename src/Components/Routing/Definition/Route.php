<?php

declare(strict_types=1);

namespace App\Components\Routing\Definition;

class Route
{
    /**
     * @param array<string, string|int|bool> $parameters
     */
    public function __construct(
        private readonly string $path,
        private readonly string $name,
        private array $parameters = []
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string[]
     */
    public function getPathParameters(): array
    {
        $regex = '/\{[A-Za-z0-9]+\}/';

        $hasMatches = preg_match_all($regex, $this->path, $matches);

        if (!$hasMatches) return [];

        return array_values($matches[0]);
    }

    public function hasPathParameter(): bool
    {
        return str_contains($this->path, '{');
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, string|int|bool>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array<string, string|int|bool> $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * Add a parameter to the route replace if already exists
     *
     * @param array<string, string|int|bool> $parameters
     */
    public function addParams(array $parameters): void
    {
        $this->parameters = array_replace($this->parameters, $parameters);
    }
}