<?php

declare(strict_types=1);

namespace App\Components\Finder;

use App\Components\Finder\Exception\DirectoryNotFoundException;

class Finder
{
    /** @var string[] */
    private array $dirs = [];
    /** @var string[] */
    private array $excludedDirs = [];

    /**
     * Searches files and directories which match defined rules.
     *
     * @param string|string[] $paths
     *
     * @throws DirectoryNotFoundException
     */
    public function in(string|array $paths): self
    {
        $resolvedDirs = [];

        foreach ((array) $paths as $path) {
            if (is_dir($path)) {
                $resolvedDirs[] = [$path];
                continue;
            } elseif ($glob = glob(
                $path,
                (\defined('GLOB_BRACE') ? \GLOB_BRACE : 0) | \GLOB_ONLYDIR | \GLOB_NOSORT
            )) {
                $resolvedDirs[] = $glob;
                continue;
            }

            throw new DirectoryNotFoundException(sprintf('directory %s not found', $path));
        }

        $this->dirs = array_merge($this->dirs, ...$resolvedDirs);

        return $this;
    }

    public function exclude(string|array $dirs): self
    {
        $this->excludedDirs = array_merge($this->excludedDirs, (array) $dirs);

        return $this;
    }


    /**
     * @return string[]
     */
    public function findFilesFromPartialPath(string $partialPath, ?string $extension = null): array
    {
        $a = new \RecursiveDirectoryIterator('./');
        $files = [];

        $formattedPath = $this->normalizePath($partialPath);

        $basePath = realpath('');
        $realPath = realpath($basePath . DIRECTORY_SEPARATOR . $formattedPath);

        $reg = '/(?!\/)[a-zA-Z0-9_\-]+' . ($extension ? ('\\' . $extension . '$/') : '\.[a-z]{1,10}/');
        $regexIterator = $this->getDirectoryIterator($realPath, $reg);

        /** @var \SplFileInfo $file */
        foreach ($regexIterator as $file) {
            if (!preg_match('/vendor/', $file->getPath())) {
                $files[] = current(glob($file->getPath() . '/' . $file->getFilename()));
            }
        }

        return $files;
    }

    private function getDirectoryIterator(
        string $path,
        ?string $pattern = null
    ): \RecursiveIteratorIterator|\RegexIterator {
        $directoryIterator = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directoryIterator);

        if ($pattern) {
            return new \RegexIterator($iterator, $pattern);
        }

        return $iterator;
    }

    public function normalizePath(string $basePath): string
    {
        $formattedPath = str_replace('/', DIRECTORY_SEPARATOR, $basePath);
        $formattedPath = preg_replace([
                '/\/\*?$/',
                '/^\.\//'
            ],
            '',
            $formattedPath
        );

        return $formattedPath;
    }
}