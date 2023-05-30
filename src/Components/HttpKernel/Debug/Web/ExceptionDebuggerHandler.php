<?php

declare(strict_types=1);

namespace App\Components\HttpKernel\Debug\Web;

use App\Components\HttpKernel\Debug\Web\ViewModel\WebDebugPageViewModel;

class ExceptionDebuggerHandler
{
    public static function handle(\Throwable $exception): bool
    {
        $viewModel = new WebDebugPageViewModel($exception);

        ob_start();
        require_once 'Template/web-debug-page.html.php';
        $template = ob_get_clean();
        echo $template;
        return true;
    }
}