<?php

declare(strict_types=1);

namespace App\Components\HttpKernel;

use App\Components\Container\Container;
use App\Components\HttpKernel\HttpFoundation\Request;
use App\Components\HttpKernel\HttpFoundation\Response;
use App\Components\HttpKernel\HttpFoundation\ResponseCode;

class HttpKernel
{
    public function init(?callable $exceptionHandler = null, ?callable $errorHandler = null): void
    {
        $this->initErrorHandling($errorHandler);
        $this->initExceptionHandling($exceptionHandler);
    }

    public function initErrorHandling(?callable $handler): void
    {
        $handler ??= $this->defaultErrorHandler(...);
        set_error_handler($handler);
    }

    public function defaultErrorHandler(int $code, string $message, string $file, int $line, array $context): bool
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
        set_exception_handler($handler);
    }

    public function defaultExceptionHandler(\Exception $e): bool
    {
        $fileObject = new \SplFileObject($e->getFile());
        $fileObject->seek($e->getLine() - 6);

        $lastTraces = array_reverse($e->getTrace());
        echo <<<EOL
<style>
* {
    padding: 0;
    margin: 0;
}

body {
    padding: 1rem;
}

.code-folding {
    background: grey;
    height: 3rem;
    display: flex;
    align-items: center;
    gap: 2rem;
}

.code-folding::before {
    content: "+";
    width: 2rem;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 1.6rem;
    transform: rotate(45deg);
    transition: .5s;
}

.code-container {
    position:relative;
    width: 100%;
    color: white;
    transition: .5s;
    transform-origin: top;
}

.code-container__folded {
    transform: scaleY(0);
}

.code-wrapper {
    background: #363432;
    display: grid;
    grid-template-columns: 2.5fr 58fr;
    width: 100%;
    border-radius: .5rem;
    /*height: 100%;*/
}

.code-line {
    display: flex;
    align-items: center;
    height: 1rem;
    padding: .43rem 0;
}

.code-line:nth-last-child(1), .code-line:nth-last-child(2) {
    padding-bottom: 1rem;
}

.code-line:nth-child(1), .code-line:nth-child(2) {
    padding-top: 1rem;
}

.code-line__number {
    justify-content: end;
    border-right: #b03319 1px solid;
    padding-right: .6rem;
}

.code-line__text {
    padding-left: .5rem;
}

.code-line__error {
    background: rgba(208,35,35,0.87);
}
</style>
EOL;


        echo <<<EOL
<h1>Error {$e->getMessage()}</h1>
EOL;
        foreach ($lastTraces as $lastTrace) {
            $traceObj = new \SplFileObject($lastTrace['file']);
            $startLine = $lastTrace['line'] - 6;
            $traceObj->seek($startLine);

            echo <<<EOL
<div>
    <div class="code-folding">In file {$traceObj->getPath()}</div>
    <pre class="code-container">
EOL;
            echo "<div class='code-wrapper'>";
            for($i = 1; $i < 11; $i++) {
                if ($traceObj->eof()) {
                    break;
                }

                $currentLine = $startLine + $i;
                echo "<span class='code-line code-line__number" . ($lastTrace['line'] == $currentLine ? ' code-line__error' : '') . "'>{$currentLine}</span>";
                $safeLine = htmlspecialchars($traceObj->fgets());
                echo "<span class='code-line code-line__text" . ($lastTrace['line'] == $currentLine ? ' code-line__error' : '') . "'>{$safeLine}</span>";

            }
            echo "</div>";
            echo <<<EOL
    </pre>
</div>
EOL;
        }

        echo <<<EOL
<script type="application/javascript">
    const foldingCodeBlockElements = document.querySelectorAll('.code-folding');
    
    foldingCodeBlockElements.forEach(element => {
        element.addEventListener('click', e => {
            e.target.nextElementSibling.classList.toggle('code-container__folded')
        })
    })
</script>
EOL;


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

    public function test(\Exception $exception): bool
    {
        // Récupération des informations d'erreur
        $errorMessage = $exception->getMessage();
        $errorFile = $exception->getFile();
        $errorLine = $exception->getLine();

        // Lecture du fichier source
        $sourceCode = file_get_contents($errorFile);

        // Identification de la ligne d'erreur
        $lines = explode("\n", $sourceCode);
        $errorSourceLine = $lines[$errorLine - 1];

        // Extraction du code source avec indentation
        $errorSourceCode = '<pre>';
        foreach ($lines as $lineNumber => $line) {
            if ($lineNumber === 0) continue;
            $errorSourceCode .= "<span class='code' " . ((int) $lineNumber === ($errorLine - 1) ? 'style="background: red;"' : '') . ">" . $line . "</span>" . PHP_EOL;
            if ($lineNumber + 1 === $errorLine) {
                break;
            }
        }
        $errorSourceCode .= '</pre>';

        // Affichage de l'erreur avec l'indentation
        echo "Erreur : $errorMessage" . PHP_EOL;
        echo "Fichier : $errorFile" . PHP_EOL;
        echo "Ligne : $errorLine" . PHP_EOL;
        echo "Code source :" . PHP_EOL;
        echo $errorSourceCode;

        return true;
    }

    public function handle(Request $request): Response
    {
        try {
            Container::getContainer();

        } catch (\Throwable) {
            Container::getContainer();
            $content = '<h1>fesfsefsefsefesfsefsef</h1>';
        }

        return new Response($content, ResponseCode::HTTP_OK);
    }
}