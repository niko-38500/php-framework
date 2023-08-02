<?php

declare(strict_types=1);

namespace App\Components\HttpKernel\Constant;

/**
 * The available environment values
 */
enum EnvironmentType: string
{
    case DEVELOPMENT = 'dev';
    case TEST = 'test';
    case PRODUCTION = 'prod';
}