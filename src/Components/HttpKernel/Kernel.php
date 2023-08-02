<?php

declare(strict_types=1);

namespace App\Components\HttpKernel;

use App\Components\Container\Container;
use App\Components\Container\ParameterBag\ParameterBag;
use App\Components\HttpKernel\Constant\EnvironmentType;

class Kernel
{
    public const VERSION = '0.0.1';

    private string $environment;
    private Container $container;
    public string $projectDir;

    public function __construct(?string $basePath = null)
    {
        $basePath ??= $_ENV['project-root-dir'] ?? '.';
        $basePath = realpath($basePath);

        if (!$basePath) {
            throw new \InvalidArgumentException(sprintf(
                'Can not initialize %s base path is not valid',
                self::class
            ));
        }

        $_ENV['project-root-dir'] = $basePath;
        $this->projectDir = $basePath;

        $this->preBootContainer();
    }

    private function preBootContainer(): void
    {
        $container = Container::init($this->bootDotEnv());
    }

    /**
     * Return a ParameterBag with the .env file data and save the vars into $_ENV
     *
     * You must have either a .env or a .env.local file or both in the root of the project
     *
     * .env.local override .env vars
     *
     * @throws \RuntimeException
     */
    private function bootDotEnv(): ParameterBag
    {
        $bag = new ParameterBag();
        $hasADotEnvFile = true;

        try {
            $envFile = new \SplFileObject(sprintf(
                '%s%s.env',
                $_ENV['project-root-dir'],
                DIRECTORY_SEPARATOR
            ));

            $this->processDotEnvExtractVars($envFile, $bag);
        } catch (\RuntimeException) {
            $hasADotEnvFile = false;
        }

        try {
            $envFile = new \SplFileObject(sprintf(
                '%s%s.env.local',
                $_ENV['project-root-dir'],
                DIRECTORY_SEPARATOR
            ));
        } catch (\RuntimeException) {
            if (!$hasADotEnvFile) {
                throw new \RuntimeException(
                    'You must have either a .env or a .env.local file or both in the root of the project'
                );
            }

            return $bag;
        }

        $this->processDotEnvExtractVars($envFile, $bag);

        if (!$bag->has('ENV')) {
            throw new \RuntimeException(
                'Environment is not set please defined a "ENV" var either into your .env or your .env.local file'
            );
        } elseif (!in_array(
            $bag->get('ENV'),
            $availableEnv = array_map(fn(EnvironmentType $v) => $v->value, EnvironmentType::cases())
        )) {
            throw new \RuntimeException(sprintf(
                '"ENV" var into .env must be one of the following : %s',
                join(', ', $availableEnv)
            ));
        }

        return $bag;
    }

    private function processDotEnvExtractVars(\SplFileObject $file, ParameterBag $bag): void
    {
        while (!$file->eof()) {
            $line = rtrim($file->fgets() ?: '');
            $keyVal = explode('=', $line);
            $key = $keyVal[0];
            unset($keyVal[0]);
            $value = join('=', $keyVal);
            $_ENV[$key] = $value;
            $bag->set($key, $value);
        }
    }

    public function init(): void
    {

    }

    private function loadConfig(): array
    {
        return [];
    }
}