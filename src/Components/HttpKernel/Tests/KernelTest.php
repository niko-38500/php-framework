<?php

namespace Finder\Tests;

use App\Components\Container\Container;
use App\Components\HttpKernel\Kernel;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    public function testExceptionOnInvalidBasePath(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Can not initialize %s base path is not valid',
            Kernel::class
        ));
        new Kernel('invalid path');
    }

    public function testConstructKernelWithBasePath(): void
    {
        self::assertArrayNotHasKey('project-root-dir', $_ENV);

        new Kernel(__DIR__);
        self::assertArrayHasKey('project-root-dir', $_ENV);
    }

    public function testConstructKernelWithoutBasePathButWithEnv(): void
    {
        $this->expectNotToPerformAssertions();
        
        $_ENV['project-root-dir'] = __DIR__;
        new Kernel();
    }

    public function testBootDotEnv(): void
    {
        new Kernel(__DIR__ . '/Fixtures/Boilerplate');
        $container = Container::getContainer();

        self::assertEquals('123=', $container->getParameter('VERY_SECRET_KEY'));
        self::assertEquals('test', $container->getParameter('TEST'));
        self::assertEquals('123=', $_ENV['VERY_SECRET_KEY']);
        self::assertEquals('test', $_ENV['TEST']);
    }

    public function testBootConfig(): void
    {
        new Kernel(__DIR__ . '/Fixtures/Boilerplate');
        $container = Container::getContainer();

        self::assertTrue($container->hasParameter('test'));
        self::assertEquals('test', $container->getParameter('test'));
    }
}
