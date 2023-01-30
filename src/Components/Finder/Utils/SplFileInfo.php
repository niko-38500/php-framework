<?php

declare(strict_types=1);

namespace App\Components\Finder\Utils;

class SplFileInfo extends \SplFileInfo
{
    public function __construct(string $filename, private readonly string $relativePath)
    {
        parent::__construct($filename);
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }
}