<?php

declare(strict_types=1);

namespace App\Components\Finder\Iterator;

use App\Components\Finder\Utils\SplFileInfo;

class RecursiveDirectoryIterator extends \RecursiveDirectoryIterator
{
    public function current(): SplFileInfo
    {
        return new SplFileInfo($this->getPathname(), $this->getSubPath());
    }
}