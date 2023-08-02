<?php

declare(strict_types=1);

namespace App\Components\Config\Parser;

use App\Components\Config\Exception\InvalidPathException;
use App\Components\Config\Exception\ParseConfigException;
use App\Components\Config\Schema\ConfigSchemaInterface;

class YamlParser implements ConfigParserInterface
{
    /**
     * @throws InvalidPathException
     */
    public function parseFromFile(string $path, ?ConfigSchemaInterface $schema = null): array
    {   // todo juste load yaml and separate config schema to use a builder pattern to build the validation schema in this way:
        // $builder->children()->scalarNode()->
        try {
            $yaml = yaml_parse_file($path);
        } catch (\Exception) {
            throw new InvalidPathException(sprintf('"%s" is not a valid path', $path));
        }
        
        if ($schema) {
            $this->validateSchema($yaml, $schema);
        }

        return $yaml;
    }

    private function validateSchema(array $yaml, ConfigSchemaInterface $schema): void
    {
        /*
        $yaml = [
            'services' => [
                '_default' => [
                    'autowire' => true
                ]
            ]
        ];
         */
        $schemaArray = $schema->schema();
        /* $schemaArray = [
            'services' => [
                '_default' => [
                    'autowire' => 'boolean'
                ]
            ]
        ];*/

        foreach ($yaml as $key => $value) {
            if (is_array($value)) {
                $this->validateSchema($value, $schema);
            }
        }
    }
}