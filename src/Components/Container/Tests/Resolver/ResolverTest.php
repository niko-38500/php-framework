<?php

namespace App\Components\Container\Tests\Resolver;

use App\Components\Container\Container;
use App\Components\Container\Exception\AutowireException;
use App\Components\Container\Exception\Resolver\CircularReferenceException;
use App\Components\Container\Exception\Resolver\UndefinedClassException;
use App\Components\Container\ParameterBag\ParameterBag;
use App\Components\Container\Resolver\Resolver;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithCircularReference;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithDeepDependencies;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithDependencies;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithDependencyAndParameter;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithIndirectCircularReference;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithInterface;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithInterfaceDependency;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithNoDependencies;
use App\Components\Container\Tests\Tools\DataFixtures\DummyClass\OtherClassWithCircularReference;
use App\Components\Container\Tests\Tools\DataFixtures\DummyInterface\IOCInterface;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{
    private Resolver $resolver;
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        Container::init(new ParameterBag());
        $this->container = Container::getContainer();

        $this->resolver = new Resolver($this->container);
        $this->container->reset();
    }

    public function testResolveByFQCNWithNotExisting(): void
    {
        $this->expectException(UndefinedClassException::class);
        $this->resolver->resolve('expect to lead to an exception');
    }

    public function testResolveClass(): void
    {
        $resolvedClass = $this->resolver->resolve(ClassWithNoDependencies::class);
        self::assertIsObject($resolvedClass);
        self::assertInstanceOf(ClassWithNoDependencies::class, $resolvedClass);
    }

    public function testResolveClassWithDependencies(): void
    {
        $resolvedClass = $this->resolver->resolve(ClassWithDependencies::class);
        self::assertInstanceOf(ClassWithDependencies::class, $resolvedClass);
        self::assertInstanceOf(ClassWithNoDependencies::class, $resolvedClass->classWithNoDependencies);
    }

    public function testResolveClassWithDeepDependencies(): void
    {
        $resolvedClass = $this->resolver->resolve(ClassWithDeepDependencies::class);
        self::assertInstanceOf(ClassWithDeepDependencies::class, $resolvedClass);
    }

    public function testResolveClassWithParameterAndDeepDependencies(): void
    {
        $container = Container::getContainer();
        $value = 'value';
        $container->setParameter('boundParameter', $value);
        $resolvedClass = $this->resolver->resolve(ClassWithDependencyAndParameter::class);
        self::assertInstanceOf(ClassWithDependencyAndParameter::class, $resolvedClass);
        self::assertInstanceOf(ClassWithDependencies::class, $resolvedClass->classWithDependencies);
        self::assertInstanceOf(
            ClassWithNoDependencies::class,
            $resolvedClass->classWithDependencies->classWithNoDependencies
        );
        self::assertEquals($value, $resolvedClass->boundParameter);
    }

    public function testResolveClassWithUnregisteredParameter(): void
    {
        $this->expectException(AutowireException::class);
        $this->expectExceptionMessage(
            'Can not autowire the parameter $boundParameter for the class ' .
            'App\Components\Container\Tests\Tools\DataFixtures\DummyClass\ClassWithDependencyAndParameter'
        );
        $this->resolver->resolve(ClassWithDependencyAndParameter::class);
    }

    public function testResolveWithCircularReference(): void
    {
        self::expectException(CircularReferenceException::class);
        self::expectExceptionMessage(sprintf(
            'A circular reference has been detected into the class %s for the dependency %s',
            ClassWithCircularReference::class,
            OtherClassWithCircularReference::class
        ));

        $this->resolver->resolve(ClassWithCircularReference::class);
    }

    public function testResolveWithIndirectCircularReference(): void
    {
        self::expectException(CircularReferenceException::class);
        self::expectExceptionMessage(sprintf(
            'A circular reference has been detected into the class %s for the dependency %s',
            ClassWithCircularReference::class,
            OtherClassWithCircularReference::class
        ));

        $this->resolver->resolve(ClassWithIndirectCircularReference::class);
    }

    public function testResolveInterfaceAsDependency(): void
    {
        $this->container->set(IOCInterface::class, $this->resolver->resolve(ClassWithInterface::class));
        $classWithInterfaceAsDependency = $this->resolver->resolve(ClassWithInterfaceDependency::class);

        self::assertInstanceOf(ClassWithInterfaceDependency::class, $classWithInterfaceAsDependency);
        self::assertInstanceOf(ClassWithInterface::class, $classWithInterfaceAsDependency->ioc);
    }
}
