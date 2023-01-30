<?php

namespace App\Components\Finder\Tests;

use App\Components\Finder\Exception\DirectoryNotFoundException;
use App\Components\Finder\Finder;
use App\Components\Finder\Utils\RegexHelper;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{
    public function testGetIteratorWhenEmptyDirs(): void
    {
        self::expectException(\LogicException::class);
        $this->expectExceptionMessage('You must call one of in() method before iterating over a Finder.');
        $finder = new Finder();
        $finder->getIterator();
    }

    public function testGetIterator(): void
    {
        $finder = new Finder();

        self::assertInstanceOf(
            \Iterator::class,
            $finder->in(__DIR__ . DIRECTORY_SEPARATOR . 'DataFixtures/TestScanDirectory')->getIterator()
        );
    }

    public function testHasExceptionOnDirectoryNotFound(): void
    {
        $finder = new Finder();
        $path = 'unexisting/directory';
        $this->expectException(DirectoryNotFoundException::class);
        $this->expectExceptionMessage(sprintf('directory %s not found', $path));
        $finder->in($path);
    }

    protected function pathToFilesProvider(): \Iterator
    {
        yield 'with non existing file name' => ['DataFixtures/TestScanDirectoryWithEmptyFiles', '*.noxist', []];
        yield 'with non existing file name#2' => ['DataFixtures/TestScanDirectoryWithEmptyFiles', 'this_file.notxist', []];
        yield 'with json extension' => [
            'DataFixtures/TestScanDirectoryWithEmptyFiles',
            '*.json',
            [new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/d.json')]
        ];

        yield 'with yaml extension' => [
            'DataFixtures/TestScanDirectoryWithEmptyFiles',
            '*.yaml',
            [new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/e.yaml')]
        ];

        yield 'with neon extension' => [
            'DataFixtures/TestScanDirectoryWithEmptyFiles',
            '*.neon',
            [new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/f.neon')]
        ];

        yield 'with php extension' => [
            'DataFixtures/TestScanDirectory',
            '*.php',
            [
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/ClassFile.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/Interface.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/file-w1th_numb3r.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/kebab-case-file.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/sneak_case_file.php'),
            ],
        ];

        yield 'with php extension #2' => [
            'DataFixtures/TestScanDirectoryWithEmptyFiles',
            '*.php',
            [
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/a.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/b.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/c.php'),
            ],
        ];

        yield 'without file name' => [
            'DataFixtures/TestScanDirectory',
            '',
            [
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/A.json'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/ClassFile.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/Interface.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/file-w1th_numb3r.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/kebab-case-file.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectory/sneak_case_file.php'),
            ],
        ];

        yield 'without file name #2' => [
            'DataFixtures/TestScanDirectoryWithEmptyFiles',
            '',
            [
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/a.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/b.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/c.php'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/f.neon'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/e.yaml'),
                new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/d.json'),
            ]
        ];
    }

    /**
     * @param \Iterator<\SplFileInfo> $iterator
     * @param \SplFileInfo[] $expected
     */
    private function assertIterator(\Iterator $iterator, array $expected): void
    {
        $expectedResults = array_map(
            fn(\SplFileInfo $fileInfo) => str_replace('/', DIRECTORY_SEPARATOR, $fileInfo->getPathname()),
            $expected
        );

        $actualValues = array_map(
            fn(\SplFileInfo $fileInfo) => str_replace('/', DIRECTORY_SEPARATOR, $fileInfo->getPathname()),
            iterator_to_array($iterator)
        );

        sort($expectedResults);
        sort($actualValues);

        self::assertSame($expectedResults, $actualValues);
    }

    /**
     * @dataProvider pathToFilesProvider
     *
     * @param string|string[] $path
     * @param string|string[] $fileName
     * @param \SplFileInfo[] $expected
     */
    public function testIteratorHasFiles(string|array $path, string|array $fileName, array $expected): void
    {
        $finder = new Finder();
        $finder->in(__DIR__ . DIRECTORY_SEPARATOR . $path);

        if (!empty($fileName)) {
            $finder->fileName($fileName);
        }

        $this->assertIterator($finder->getIterator(), $expected);
    }

    public function testIteratorWithExcludedFiles(): void
    {
        $finder = new Finder();
        $iterator = $finder
            ->in(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles')
            ->notFilename('*.php')
            ->getIterator()
        ;

        $this->assertIterator($iterator, [
            new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/d.json'),
            new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/e.yaml'),
            new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/f.neon'),
        ]);
    }

    public function testIteratorWithExcludedPaths(): void
    {
        $finder = new Finder();
        $iterator = $finder
            ->in(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles')
            ->exclude('b/c')
            ->fileName('/.*\.php/')
            ->getIterator()
        ;

        $this->assertIterator($iterator, [
            new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/a.php'),
            new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/b.php'),
        ]);
    }

    public function testIteratorWithMandatoryPath(): void
    {
        $finder = new Finder();
        $iterator = $finder
            ->in(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles')
            ->mandatoryPath('b/c')
            ->getIterator()
        ;

        $this->assertIterator($iterator, [
            new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/c.php'),
            new \SplFileInfo(__DIR__ . '/DataFixtures/TestScanDirectoryWithEmptyFiles/b/c/f.neon'),
        ]);
    }

    public function globToRegexProvider(): \Iterator
    {
        yield 'with curly braces' => [
            'src/{ici,la,un_autre_file,ou_la}*.php',
            '#^src/(?:ici|la|un_autre_file|ou_la)[^/]*\.php$#'
        ];

        yield 'with wildcard ' => ['**/*.php', '#^[^/]*[^/]*/[^/]*\.php$#'];

        yield 'with escaped character ' => ['**/*\*.php', '#^[^/]*[^/]*/[^/]*\*\.php$#'];

        yield 'with file extension' => ['*.php', '#^[^/]*\.php$#'];
    }

    /**
     * @dataProvider globToRegexProvider
     */
    public function testGlobToRegex(string $glob, string $expected): void
    {
        $reg = RegexHelper::globToRegex($glob);

        self::assertSame($expected, $reg);
    }
}
