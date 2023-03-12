<?php

declare(strict_types=1);

namespace App\Components\HttpKernel;

use App\Components\HttpKernel\HttpFoundation\Request;
use App\Components\HttpKernel\HttpFoundation\Response;
use App\Components\HttpKernel\HttpFoundation\ResponseCode;

class HttpKernel
{
    public function handle(Request $request): Response
    {
        try {
            $content = require_once 'aa.html.php';
//            $gfsef->a();
        } catch (\Throwable|\Error) {
            echo '<h1>fesfsefsefsefesfsefsef</h1>';
        }

        return new Response($content, ResponseCode::HTTP_OK);
    }
}