<?php

declare(strict_types=1);

namespace App\Components\Config\Tests\Parser;

use App\Components\Config\Exception\InvalidPathException;
use App\Components\Config\Exception\SchemaValidationException;
use App\Components\Config\Tests\Fixtures\Schema\InvalidSchema;
use App\Components\Config\Tests\Fixtures\Schema\Schema;
use App\Components\Config\Parser\YamlParser;
use PHPUnit\Framework\TestCase;

class YamlParserTest extends TestCase
{
    public function testParseYaml(): void
    {
        $yamlParser = new YamlParser();

        $expectedArray = [
            'services' => [
                '_default' => [
                    'autowire' => true
                ]
            ]
        ];

        $actual = $yamlParser->parseFromFile(__DIR__ . '/../Fixtures/config/valid.yaml');

        self::assertEquals($expectedArray, $actual);
    }

    public function testWithInvalidPath(): void
    {
        $yamlParser = new YamlParser();

        self::expectException(InvalidPathException::class);
        self::expectExceptionMessage('"invalid/path.yaml" is not a valid path');

        $yamlParser->parseFromFile('invalid/path.yaml');
    }

    public function testWithValidSchema(): void
    {
        $schema = new Schema();

        $yamlParser = new YamlParser();

        $expectedArray = [
            'services' => [
                '_default' => [
                    'autowire' => true
                ]
            ]
        ];

        $actual = $yamlParser->parseFromFile(__DIR__ . '/../Fixtures/config/valid.yaml', $schema);

        self::assertEquals($expectedArray, $actual);
    }

    public function testWithInvalidSchema(): void
    {
        $schema = new InvalidSchema();

        $yamlParser = new YamlParser();

        self::expectException(SchemaValidationException::class);
        self::expectExceptionMessage(sprintf(
            'The file %s/../Fixtures/config/valid.yaml does not satisfies the validation schema. Key "services" is not valid',
            __DIR__
        ));

        $yamlParser->parseFromFile(__DIR__ . '/../Fixtures/config/valid.yaml', $schema);
    }
}