<?php

declare(strict_types=1);

namespace App\Components\HttpFoundation;

use App\Components\HttpFoundation\Bag\InputBag;
use App\Components\HttpFoundation\Bag\ParameterBag;

class Request
{
    private function __construct(
        private readonly ParameterBag $server,
        private readonly ParameterBag $getParameter,
        private readonly ParameterBag $postParameter
    ) {}

    public static function createFromGlobals(): self
    {
        return new self(new ParameterBag($_SERVER), new ParameterBag($_GET), new ParameterBag($_POST));
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->getParameter->has($key)) {
            return $this->getParameter->get($key);
        }

        if ($this->postParameter->has($key)) {
            return $this->postParameter->get($key);
        }

        return $default;
    }

    public function getParameters(): ParameterBag
    {
        return $this->getParameter;
    }

    public function postParameter(): ParameterBag
    {
        return $this->postParameter;
    }

    public function server(): ParameterBag
    {
        return $this->server;
    }

    public function headers(): ParameterBag
    {
        return $this->server;
    }
}