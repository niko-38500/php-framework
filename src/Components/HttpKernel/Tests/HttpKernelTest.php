<?php

declare(strict_types=1);

namespace App\Components\HttpKernel\Tests;

use App\Components\HttpKernel\HttpFoundation\Constant\RequestMethod;
use App\Components\HttpKernel\HttpFoundation\Request;
use App\Components\HttpKernel\HttpFoundation\Response;
use App\Components\HttpKernel\HttpKernel;
use PHPUnit\Framework\TestCase;

class HttpKernelTest extends TestCase
{
    public function testHandleReturnResponse(): void
    {
        $request = Request::create('/test', RequestMethod::GET);
        $httpKernel = new HttpKernel();

        self::assertInstanceOf(Response::class, $httpKernel->handle($request));
    }

    public function testHandleWithExceptionThrown(): void
    {
        $request = Request::createFromGlobals();
        $httpKernel = new HttpKernel();

        $httpKernel->handle($request);
    }
}