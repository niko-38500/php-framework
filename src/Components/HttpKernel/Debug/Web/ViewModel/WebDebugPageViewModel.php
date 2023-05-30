<?php

declare(strict_types=1);

namespace App\Components\HttpKernel\Debug\Web\ViewModel;

use App\Components\HttpKernel\Debug\Web\ValueObject\ExceptionTrace;

class WebDebugPageViewModel
{
    /** @var ExceptionTrace[] $trace */
    public array $trace;

    public function __construct(
        public \Throwable $exception,
    ) {
        $this->trace = array_map(
            fn(array $currentTrace) => ExceptionTrace::fromTraceData($currentTrace),
            $this->exception->getTrace()
        );
    }
}