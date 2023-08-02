<?php

declare(strict_types=1);

namespace App\Components\Config\Definition;

class FileConfigDefinition
{
    public function __construct(
        private readonly string $filename,
        private readonly array $configData = [],
    ) {}

    public function getConfig(): array
    {
        return $this->configData;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}