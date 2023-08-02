<?php

namespace App\Components\Config\Tests\Loader;

use App\Components\Config\Exception\LoaderException;
use App\Components\Config\Loader\YamlLoader;
use PHPUnit\Framework\TestCase;

class YamlLoaderTest extends TestCase
{
    public function testLoadConfig(): void
    {
        $yamlLoader = new YamlLoader();
        $path = sprintf('%1$s%2$s..%2$sFixtures%2$sconfig%2$syaml%2$svalid.yaml', __DIR__, DIRECTORY_SEPARATOR);
        $actualConfigFile = $yamlLoader->load($path);

        $expectedData = [
            'services' => [
                '_default' => [
                    'autowire' => true,
                ]
            ]
        ];

        self::assertEquals($path, $actualConfigFile->getFilename());
        self::assertEquals($expectedData, $actualConfigFile->getConfig());
    }

    public function testLoadNonArrayConfig(): void
    {
        $yamlLoader = new YamlLoader();
        $path = sprintf(
            '%1$s%2$s..%2$sFixtures%2$sconfig%2$syaml%2$snon_array_file.yaml',
            __DIR__,
            DIRECTORY_SEPARATOR
        );

        self::expectException(LoaderException::class);
        self::expectExceptionMessage(sprintf(
            'Your config file "%s" must contains at least 1 "key: value"',
            $path
        ));

        $yamlLoader->load($path);
    }
}
