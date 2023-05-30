<?php

declare(strict_types=1);

namespace App\Components\HttpKernel\Debug\Web\ValueObject;

class ExceptionTrace
{
    /**
     * @param FileLine[] $fileLines
     */
    public function __construct(
        public string $filePath,
        public int $line,
        public string $function,
        public string $type,
        public array $fileLines
    ) {}

    public static function fromTraceData(array $trace): self
    {
        if (
            !array_key_exists('file', $trace) ||
            !array_key_exists('line', $trace) ||
            !array_key_exists('function', $trace) ||
            !array_key_exists('type', $trace) ||
            !is_string($trace['file']) ||
            !is_int($trace['line']) ||
            !is_string($trace['function']) ||
            !is_string($trace['type'])
        ) {
            throw new \InvalidArgumentException('Unable to create ExceptionTrace object, invalid array data');
        }

        $traceObj = new \SplFileObject($trace['file']);
        $startLine = $trace['line'] - 6;

        if ($startLine < 0) {
            $startLine = 0;
        }

        $traceObj->seek($startLine);
        $fileLines = [];

        for($i = 1; $i < 11; $i++) {
            if ($traceObj->eof()) {
                break;
            }

            $currentLine = $startLine + $i;

            $fileLines[] = new FileLine(
                $currentLine,
                htmlspecialchars($traceObj->fgets() ?: ''),
                $trace['line'] == $currentLine
            );
        }

        $traceObj = null;

        return new self(
            $trace['file'],
            $trace['line'],
            $trace['function'],
            $trace['type'],
            $fileLines
        );
    }
}