<?php

declare(strict_types=1);

namespace App\Components\HttpKernel\HttpFoundation;

use App\Components\HttpKernel\HttpFoundation\Bag\InputBag;
use App\Components\HttpKernel\HttpFoundation\Bag\ParameterBag;
use App\Components\HttpKernel\HttpFoundation\Constant\RequestMethod;

class Request
{
    private string $uri;
    private RequestMethod $method;

    private function __construct(
        private readonly ParameterBag $server,
        private readonly ParameterBag $getParameter,
        private readonly ParameterBag $postParameter
    ) {}

    public static function createFromGlobals(): self
    {
        return new self(new ParameterBag($_SERVER), new ParameterBag($_GET), new ParameterBag($_POST));
    }

    public static function create(
        string $uri,
        RequestMethod $method,
        array $postParameters = [],
        array $getParameters = [],
        array $server = []
    ): self {
        $server = array_replace([
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => 80,
            'HTTP_HOST' => 'localhost',
            'HTTP_USER_AGENT' => 'App',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
            'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'REMOTE_ADDR' => '127.0.0.1',
            'SCRIPT_NAME' => '',
            'SCRIPT_FILENAME' => '',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'REQUEST_TIME' => time(),
            'REQUEST_TIME_FLOAT' => microtime(true),
        ], $server);

        $request = new self(new ParameterBag($server), new ParameterBag($getParameters), new InputBag($postParameters));

        $request->setUri($uri);
        $request->setMethod($method);

        return $request;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(RequestMethod $method): void
    {
        $this->method = $method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
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