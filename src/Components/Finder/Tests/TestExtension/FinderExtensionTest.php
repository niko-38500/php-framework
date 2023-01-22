<?php

declare(strict_types=1);

namespace App\Components\Finder\Tests\TestExtension;

use App\Components\Finder\Tests\FinderTest;

class FinderExtensionTest extends FinderTest
{
    public function testWithPath(): void
    {
        $a = $this->finder->findFilesFromPartialPath('../DataFixtures/TestScanDirectoryWithEmptyFiles/*');
        $b = __DIR__;
    }
}