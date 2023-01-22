<?php

declare(strict_types=1);

namespace App\Components\HttpFoundation;

class Response
{
    public function __construct(private string $content = '', private ResponseCode $code = ResponseCode::HTTP_OK) {}

    public function getContent(): string
    {
        return $this->content;
    }

    public function getResponseCode(): ResponseCode
    {
        return $this->code;
    }
}