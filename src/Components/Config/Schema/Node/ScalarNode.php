<?php

declare(strict_types=1);

namespace App\Components\Config\Schema\Node;

use App\Components\Config\Exception\InvalidNodeTypeException;

class ScalarNode implements NodeInterface
{
    public const TYPES = [
        'integer',
        'string',
        'float',
        'boolean',
        'NULL'
    ];

    /**
     * @throws InvalidNodeTypeException
     */
    public function validateType(mixed $value): void
    {
        if (!in_array(gettype($value), self::TYPES)) {
            throw new InvalidNodeTypeException(sprintf(
                'Invalid type for the value %s, expected to be scalar %s provided',
                json_encode($value),
                gettype($value)
            ));
        }
    }
}