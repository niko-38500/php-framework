<?php

declare(strict_types=1);

namespace App\Components\HttpKernel;

use App\Components\Container\Container;
use App\Components\Container\ParameterBag\ParameterBag;
use App\Components\Finder\Finder;
use App\Components\HttpKernel\Debug\Web\ExceptionDebuggerHandler;
use App\Components\HttpKernel\HttpFoundation\Request;
use App\Components\HttpKernel\HttpFoundation\Response;
use App\Components\HttpKernel\HttpFoundation\ResponseCode;

class HttpKernel
{
    public function init(?callable $exceptionHandler = null, ?callable $errorHandler = null): void
    {
        $this->initErrorHandling($errorHandler);
        $this->initExceptionHandling($exceptionHandler);
        $dotEnvParameters = $this->getDotEnvParameters();
        $config = $this->getConfigFilesParameters($dotEnvParameters);
        Container::init(new ParameterBag());
    }

    private function buildContainer(): void
    {

    }

    private function getConfigFilesParameters(array $dotEnvParameters): array
    {
        $finder = new Finder();
        $iterator = $finder
            ->fileName('*.yaml')
            ->in('../config')
            ->getIterator()
        ;

        foreach ($iterator as $file) {
            $fileObject = yaml_parse_file($file->getPathname());
            var_dump($fileObject['services']['\App']);
        }

        return [];
    }

    private function getDotEnvParameters(): array
    {
        $envFile = new \SplFileObject('./../.env');
        $parameters = [];

        while (!$envFile->eof()) {
            $line = $envFile->fgets();
            $keyVal = explode('=', $line);
            $parameters[$keyVal[0]] = $keyVal[1];
        }
        $envFile = null;

        return $parameters;
    }

    public function initErrorHandling(?callable $handler): void
    {
        $handler ??= $this->defaultErrorHandler(...);
        set_error_handler($handler);
    }

    public function defaultErrorHandler(int $code, string $message, string $file, int $line/*, array $context*/): bool
    {
        var_dump($code);
        var_dump($message);
        var_dump($file);
        var_dump($line);
        var_dump($context);

        return true;
    }

    private function initExceptionHandling(?callable $handler): void
    {
        $handler ??= $this->defaultExceptionHandler(...);
        set_exception_handler(ExceptionDebuggerHandler::handle(...));
    }

    public function defaultExceptionHandler(\Exception $e): bool
    {
        $fileObject = new \SplFileObject($e->getFile());
        $fileObject->seek($e->getLine() - 6);

        $lastTraces = array_reverse($e->getTrace());

        echo "<pre>";
        echo "<p>{$fileObject->fgets()}</p>";
        echo "<p>{$fileObject->fgets()}</p>";
        echo "<p>{$fileObject->fgets()}</p>";
        echo "<p>{$fileObject->fgets()}</p>";
        echo "<p>{$fileObject->fgets()}</p>";
        echo "<p style='background: rgba(255, 0, 0, .6)'>{$fileObject->fgets()}</p>";
        echo "<p>{$fileObject->fgets()}</p>";
        echo "<p>{$fileObject->fgets()}</p>";
        echo "<p>{$fileObject->fgets()}</p>";
        echo "<p>{$fileObject->fgets()}</p>";
        echo "<p>{$fileObject->fgets()}</p>";

        return true;
    }

    public function handle(Request $request): Response
    {
        try {
            $container = Container::getContainer();
//            $container->set();
//            var_dump($container);
//            var_dump($request->getParameters()->get('a'));
        } catch (\Throwable) {
//            Container::getContainer();
            $content = '<h1>fesfsefsefsefesfsefsef</h1>';
        }

        return new Response('', ResponseCode::HTTP_OK);
    }
}