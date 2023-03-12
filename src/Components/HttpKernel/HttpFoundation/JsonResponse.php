<?php

declare(strict_types=1);

namespace App\Components\HttpKernel\HttpFoundation;

class JsonResponse extends Response
{
    public function __construct(array $content = [], ResponseCode $code = ResponseCode::HTTP_OK)
    {
        $this->content = $content;
        $this->code = $code;
    }
}