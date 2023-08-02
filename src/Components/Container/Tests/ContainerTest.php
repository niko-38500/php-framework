<?php

namespace App\Components\Container\Tests;

use App\Components\Container\Container;
use App\Components\Container\ContainerInterface;
use App\Components\Container\Exception\Container\DuplicateException;
use App\Components\Container\Exception\Container\NotFoundException;
use App\Components\Container\ParameterBag\ParameterBag;
use App\Components\Container\Resolver\Resolver;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithDependencies;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithInterface;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithInterfaceDependency;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithNoDependencies;
use App\Components\Container\Tests\Tools\DataFixtures\DummyInterface\IOCInterface;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        Container::init(new ParameterBag());

        $this->container = Container::getContainer();
        $this->container->reset();
    }

    public function testGet(): void
    {
        $this->container->set(ClassWithNoDependencies::class, new ClassWithNoDependencies());

        self::assertInstanceOf(
            ClassWithNoDependencies::class,
            $this->container->get(ClassWithNoDependencies::class)
        );
    }

    public function testSingleton(): void
    {
        self::assertSame($this->container, Container::getContainer());
    }

    public function testGetWithError(): void
    {
        $this->expectException(NotFoundException::class);
        $this->container->get('bad class name');
    }

    public function testSetWithDuplicate(): void
    {
        $this->container->set(ClassWithNoDependencies::class, new ClassWithNoDependencies());
        $this->expectException(DuplicateException::class);
        $this->expectErrorMessage(sprintf(
            'The service %s is already registered',
            ClassWithNoDependencies::class
        ));
        $this->container->set(ClassWithNoDependencies::class, new ClassWithNoDependencies());
    }

    public function testSetWithSubDependencies(): void
    {
        $expectedClass = new ClassWithDependencies(new ClassWithNoDependencies());
        $this->container->set(ClassWithDependencies::class, new ClassWithDependencies(new ClassWithNoDependencies()));

        self::assertInstanceOf(ClassWithDependencies::class, $expectedClass);
        self::assertInstanceOf(ClassWithNoDependencies::class, $expectedClass->classWithNoDependencies);
    }

    public function testHas(): void
    {
        $this->container->set(ClassWithNoDependencies::class, new ClassWithNoDependencies());
        self::assertTrue($this->container->has(ClassWithNoDependencies::class));
    }

    public function testHasWithNoMatching(): void
    {
        self::assertFalse($this->container->has(ClassWithNoDependencies::class));
    }

    public function testSetParameter(): void
    {
        $this->container->setParameter('key', 'value');
        self::assertSame('value', $this->container->getParameter('key'));
    }

    public function testSetParameterTwice(): void
    {
        $this->container->setParameter('key', 'value');
        $this->container->setParameter('key', 'value2');

        self::assertEquals('value2', $this->container->getParameter('key'));
    }

    public function testGetParameterWithBadKey(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectErrorMessage('Parameter bad_key does not exists or is not registered');
        $this->container->getParameter('bad_key');
    }

    public function testHasParameter(): void
    {
        $this->container->setParameter('key', 'value');
        self::assertTrue($this->container->hasParameter('key'));
    }

    public function testHasParameterWithNoMatching(): void
    {
        self::assertFalse($this->container->hasParameter('key'));
    }

    public function testGetServiceFromInterface(): void
    {
        $resolver = new Resolver($this->container);
        $class = $resolver->resolve(ClassWithInterface::class);
        $this->container->set(IOCInterface::class, $class);
        $this->container->set(ClassWithInterface::class, $class);

        $classWithInterface = $this->container->get(IOCInterface::class);

        self::assertSame($resolver->resolve(ClassWithInterface::class), $classWithInterface);
    }

    public function testGetServiceFromInterfaceAsDependency(): void
    {
        $resolver = new Resolver($this->container);
        $this->container->set(IOCInterface::class, $resolver->resolve(ClassWithInterface::class));
        $this->container->set(
            ClassWithInterfaceDependency::class,
            $resolver->resolve(ClassWithInterfaceDependency::class)
        );

        $classWithInterface = $this->container->get(ClassWithInterfaceDependency::class);

        self::assertInstanceOf(ClassWithInterfaceDependency::class, $classWithInterface);
        self::assertInstanceOf(ClassWithInterface::class, $classWithInterface->ioc);
    }

    public function testGetServiceSameInstance(): void
    {
        $newStringValue = 'new value';
        $this->container->set(ClassWithNoDependencies::class, new ClassWithNoDependencies());
        $service = $this->container->get(ClassWithNoDependencies::class);
        $service->a = $newStringValue;
        $service2 = $this->container->get(ClassWithNoDependencies::class);

        self::assertSame($service, $service2);
        self::assertEquals($newStringValue, $service2->a);
    }
}
