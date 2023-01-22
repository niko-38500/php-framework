<?php

namespace App\Components\Finder\Tests;

use App\Components\Finder\Finder;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{
    protected Finder $finder;

    protected function setUp(): void
    {
        $this->finder = new Finder();
    }

    protected function pathToFilesProvider(): array
    {
        return [
            [
                './DataFixtures/TestScanDirectory/*',
                '.php',
                [
                    __DIR__ . '/DataFixtures/TestScanDirectory/ClassFile.php',
                    __DIR__ . '/DataFixtures/TestScanDirectory/Interface.php',
                    __DIR__ . '/DataFixtures/TestScanDirectory/file-w1th_numb3r.php',
                    __DIR__ . '/DataFixtures/TestScanDirectory/kebab-case-file.php',
                    __DIR__ . '/DataFixtures/TestScanDirectory/sneak_case_file.php'
                ],
            ],
            [
                './DataFixtures/TestScanDirectory/*',
                '',
                [
                    __DIR__ . '/DataFixtures/TestScanDirectory/A.json',
                    __DIR__ . '/DataFixtures/TestScanDirectory/ClassFile.php',
                    __DIR__ . '/DataFixtures/TestScanDirectory/Interface.php',
                    __DIR__ . '/DataFixtures/TestScanDirectory/file-w1th_numb3r.php',
                    __DIR__ . '/DataFixtures/TestScanDirectory/kebab-case-file.php',
                    __DIR__ . '/DataFixtures/TestScanDirectory/sneak_case_file.php'
                ],
            ],
            [
                './DataFixtures/TestScanDirectoryWithEmptyFiles/*',
                '.php',
                [
                    __DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/a.php',
                    __DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/b.php',
                    __DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/c.php',
                ],
            ],
            ['./DataFixtures/TestScanDirectoryWithEmptyFiles/*', '.noxist', []],
            ['./DataFixtures/TestScanDirectoryWithEmptyFiles/*', '.json', [__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/d.json']],
            ['./DataFixtures/TestScanDirectoryWithEmptyFiles/*', '.yaml', [__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/e.yaml']],
            ['./DataFixtures/TestScanDirectoryWithEmptyFiles/*', '.neon', [__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/f.neon']],
            [
                './DataFixtures/TestScanDirectoryWithEmptyFiles/*',
                '',
                [
                    __DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/a.php',
                    __DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/b.php',
                    __DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/c.php',
                    __DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/f.neon',
                    __DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/e.yaml',
                    __DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/d.json',
                ]
            ],
            [
                './DataFixtures',
                '',
                [
                    realpath(__DIR__ . '../DataFixturesForTest/a.js'),
                    realpath(__DIR__ . '../DataFixturesForTest/b/b.py'),
                    realpath(__DIR__ . '/DataFixturesForTest/b/c/c.php'),
                ]
            ],
        ];
    }

    /**
     * @dataProvider pathToFilesProvider
     */
    public function testInDirs(string $path, string $extension, array $expectedFiles): void
    {
        $finder = new Finder();
        $dir = [__DIR__ . DIRECTORY_SEPARATOR . 'DataFixtures'];

        $finder->in($dir);

        $a = new \ReflectionClass($finder);

        self::assertSame($dir, $a->getProperty('dirs')->getValue($finder));
    }

    /**
     * @dataProvider pathToFilesProvider
     */
    public function testFindResourceRecursively(string $path, string $extension, array $expectedFiles): void
    {
        $actualFiles = $this->finder->findFilesFromPartialPath($path, $extension);
        sort($actualFiles);

        self::assertSame($expectedFiles, $actualFiles);
    }

    protected function pathProvider(): array
    {
        return [
            ['../src/*', '../src'],
            ['../src/', '../src'],
            ['./src', 'src'],
            ['./src/', 'src'],
        ];
    }

    /**
     * @dataProvider pathProvider
     */
    public function testFormatPath(string $basePath, string $expectedPath): void
    {
        self::assertSame($expectedPath, $this->finder->normalizePath($basePath));
    }
}
