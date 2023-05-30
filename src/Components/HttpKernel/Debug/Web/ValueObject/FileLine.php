<?php

declare(strict_types=1);

namespace App\Components\HttpKernel\Debug\Web\ValueObject;

class FileLine
{
    public function __construct(
        public int $line,
        public string $text,
        public bool $isErrorLine,
    ) {}
}