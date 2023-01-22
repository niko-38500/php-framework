<?php
namespace App\Components\Container;

use App\Components\Container\Exception\Container\ContainerNotInitException;
use App\Components\Container\Exception\Container\DuplicateException;
use App\Components\Container\Exception\Container\NotFoundException;
use App\Components\Container\ParameterBag\ParameterBagInterface;

class Container implements ContainerInterface
{
    private static ?self $instance = null;

    /** @var array<string, object> */
    private array $services = [];

    private function __construct(private readonly ParameterBagInterface $parameterBag) {}

    /**
     * Initialize the container with the needed dependencies
     */
    public static function init(ParameterBagInterface $parameterBag): void
    {
        if (self::$instance) {
            return;
        }

        self::$instance = new self($parameterBag);
    }

    /**
     * @throws ContainerNotInitException
     */
    public static function getContainer(): self
    {
        if (null === self::$instance) {
            throw new ContainerNotInitException('Can not get container without init it');
        }

        return self::$instance;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $service): object
    {
        if (!$this->has($service)) {
            throw new NotFoundException(sprintf('Service %s does not exist or is not registered', $service));
        }

        return $this->services[$service];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $id, object $service): void
    {
        if ($this->has($id)) {
            throw new DuplicateException(sprintf('The service %s is already registered', $id));
        }

        $this->services[$id] = $service;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $service): bool
    {
        return array_key_exists($service, $this->services);
    }

    /**
     * {@inheritDoc}
     */
    public function getParameter(string $parameter): string|int|float|array|bool
    {
        return $this->parameterBag->get($parameter);
    }

    /**
     * {@inheritDoc}
     */
    public function setParameter(string $key, string|int|float|array|bool $value): void
    {
        $this->parameterBag->set($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function hasParameter(string $parameter): bool
    {
        return $this->parameterBag->has($parameter);
    }

    /**
     * {@inheritDoc}
     */
    public function reset(): void
    {
        $this->services = [];
        $this->parameterBag->reset();
    }
}