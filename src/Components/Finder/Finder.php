<?php

declare(strict_types=1);

namespace App\Components\Finder;

use App\Components\Finder\Exception\DirectoryNotFoundException;
use App\Components\Finder\Iterator\FilenameFilterIterator;

class Finder
{
    /** @var string[] */
    private array $dirs = [];
    /** @var string[] */
    private $filesNames = [];
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

    /**
     * Add rules that files must match, it can be either a glob patter or a regex
     *
     * example:
     *
     * $finder->fileName(['*.php', 'src/**\/\{abc, def}*.php']);
     * $finder->fileName(['/*\.php$/', '*.php']);
     * $finder->fileName('file.php');
     *
     * @param string|string[] $filesNames
     */
    public function fileName(string|array $filesNames): self
    {
        $this->filesNames = (array) $filesNames;

        return $this;
    }

    public function exclude(string|array $dirs): self
    {
        $this->excludedDirs = array_merge($this->excludedDirs, (array) $dirs);

        return $this;
    }


    /**
     * Search recursively into all the directories provided with "in()" method
     *
     * @return \Iterator<int, \SplFileInfo> Return an iterator with all found directories as SplFileInfo object
     */
    private function searchInDirectory(string $dir): \Iterator
    {

        $iterator = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($iterator);

        if (!empty($this->filesNames)) {
            $iterator = new FilenameFilterIterator($iterator, $this->filesNames);
        }

        return $iterator;
    }

    public function getIterator(): \Iterator
    {
        if (count($this->dirs) === 0) {
            throw new \LogicException('You must call one of in() method before iterating over a Finder.');
        }

        $iterator = new \AppendIterator();

        foreach ($this->dirs as $dir) {
            $iterator->append($this->searchInDirectory($dir));
        }

        return $iterator;
    }
}