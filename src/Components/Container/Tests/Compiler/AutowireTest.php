<?php

declare(strict_types=1);

namespace App\Components\Container\Tests\Compiler;

use App\Components\Container\Container;
use App\Components\Container\ParameterBag\ParameterBag;
use App\Components\Container\Resolver\Resolver;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;

class AutowireTest extends TestCase
{
    private array $baseConfig = [
        'parameters' => [
            'key' => 'value value for parameter'
        ],
        'services' => [
            '_default' => [
                'autowire' => true,
                'bind' => [
                    'string $boundParameter' => 'value for bind'
                ]
            ],
            'App\\' => [
                'ressource' => '../src/*',
                'exclude' => [],
                'arguments' => [
                    'name' => 'value'
                ]
            ],
            'App\\Test' => [
                'arguments' => [
                    'string $argument' => 'value'
                ]
            ],
        ],
    ];

    private Container $container;
    private Resolver $resolver;

    protected function setUp(): void
    {
        Container::init(new ParameterBag());
        $this->container = Container::getContainer();
        
        $this->container->reset();
        $this->resolver = new Resolver($this->container);
    }

    public function test(): void
    {
        $rdi = new RecursiveDirectoryIterator('../Tools/DataFixtures/DummyClass');
        $rii = new \RecursiveIteratorIterator($rdi);
        $r = new \RegexIterator($rii, '/[a-zA-Z0-9\-_]+\.php$/');

        $b = [];
        $c = [];
        foreach ($r as $aaa) {
            $b[] = $aaa->getFilename();
        }
        foreach ($rii as $aba) {
            $c[] = $aba->getFilename();
        }

        die();
//        $file = '../../index2.php';
//        $interface = 'App\\Test\\';
//        $classes = [];
//        $fileSource = file_get_contents($file);
//        $tokens = token_get_all($fileSource);
//        $currentNamespace = '';
//        $currentClass = '';
//        $inNamespace = false;
//        $inClass = false;
//        foreach ($tokens as $token) {
//            if (!is_array($token)) {
//                continue;
//            }
//
//
//            switch ($token[0]) {
//                case T_NAMESPACE:
//                    $inNamespace = true;
//                    break;
//                case T_CLASS:
//                case T_INTERFACE:
//                    $inClass = true;
//                    break;
//                case T_STRING:
//                case T_NAME_QUALIFIED:
//                    if ($inNamespace) {
//                        $currentNamespace .= $token[1] . '\\';
//                        $inNamespace = false;
//                    } elseif ($inClass) {
//                        $currentClass = $token[1];
//                    }
//                    break;
//                case T_IMPLEMENTS:
//                    $inClass = false;
//                    if ($currentClass && $token[1] == $interface) {
//                        $classes[] = $currentNamespace . $currentClass;
//                        $currentClass = '';
//                    }
//                    break;
//                case T_NS_SEPARATOR:
//                case T_WHITESPACE:
//                    if ($inNamespace) {
//                        $currentNamespace .= $token[1];
//                    }
//                    break;
//                default:
//                    if ($inNamespace) {
//                        $currentNamespace = '';
//                        $inNamespace = false;
//                    }
//                    if ($inClass) {
//                        $currentClass = '';
//                        $inClass = false;
//                    }
//                    break;
//            }
//        }
//
//        $classes;
    }
}