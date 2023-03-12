<?php

namespace App\Components\HttpKernel\HttpFoundation\Constant;

enum RequestMethod: string
{
    case POST = 'POST';
    case GET = 'GET';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
}
