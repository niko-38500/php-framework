<?php

declare(strict_types=1);

namespace App\Components\Container\Resolver;

use App\Components\Container\Container;
use App\Components\Container\Exception\AutowireException;
use App\Components\Container\Exception\Container\NotFoundException;
use App\Components\Container\Exception\Resolver\CircularReferenceException;
use App\Components\Container\Exception\Resolver\UndefinedClassException;
use ReflectionMethod;
use ReflectionNamedType;

class Resolver
{
    /**
     * @var string[]
     */
    private array $deps = [];

    public function __construct(private readonly Container $container) {}

    /**
     * Resolve all the dependencies
     *
     * @template T of object
     * @param class-string<T> $className The FQCN of the class you want to resolve
     * @return T
     *
     * @throws UndefinedClassException|NotFoundException
     * @throws AutowireException
     * @throws CircularReferenceException
     */
    public function resolve(string $className): object
    {
        if (!class_exists($className) && !interface_exists($className)) {
            throw new UndefinedClassException(sprintf('The class or interface %s does not exists', $className));
        }

        if (array_key_exists($className, $this->deps)) {
            throw new CircularReferenceException(sprintf(
                'A circular reference has been detected into the class %s for the dependency %s',
                $className,
                $this->deps[$className]
            ));
        }

        if ($this->container->has($className)) {
            return $this->container->get($className);
        }

        $reflectionClass = new \ReflectionClass($className);

        $classConstructor = $reflectionClass->getConstructor();

        if (!$classConstructor) {
            return new $className();
        }

        $constructorParameters = $this->resolveMethodParams($classConstructor, $className);

        $this->deps = [];
        return new $className(...$constructorParameters);
    }

    /**
     * @param class-string $className
     * @return array<int, object|string|int|float|array|bool>
     *
     * @throws AutowireException
     * @throws CircularReferenceException
     * @throws NotFoundException
     * @throws UndefinedClassException
     */
    private function resolveMethodParams(ReflectionMethod $classConstructor, string $className): array
    {
        $methodParameters = [];

        foreach ($classConstructor->getParameters() as $parameter) {
            if (!$parameter->hasType()) {
                throw new AutowireException(sprintf( // TODO rename exception class
                    'Can not autowire the parameter $%s for the class %s because it has no type',
                    $parameter->getName(),
                    $className
                ));
            }

            /** @var ReflectionNamedType $parameterType */
            $parameterType = $parameter->getType();

            if (!$parameterType->isBuiltin()) {
                $parameterTypeName = $parameterType->getName();
                
                if (!class_exists($parameterTypeName) && !interface_exists($parameterTypeName)) {
                    throw new UndefinedClassException(sprintf(
                        'The class or interface %s does not exists',
                        $className
                    ));
                }

                if ($this->container->has($parameterTypeName)) {
                    $methodParameters[] = $this->container->get($parameterTypeName);
                    continue;
                }

                $this->deps[$className] = $parameterTypeName;
                $methodParameters[] = $this->resolve($parameterTypeName);
                continue;
            }

            if (!$this->container->hasParameter($parameter->getName())) {
                throw new AutowireException(sprintf(
                    'Can not autowire the parameter $%s for the class %s',
                    $parameter->getName(),
                    $className
                ));
            }

            $methodParameters[] = $this->container->getParameter($parameter->getName());
        }
        return $methodParameters;
    }
}