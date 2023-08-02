<?php

namespace App\Components\Config\Schema\Node;

use App\Components\Config\Exception\InvalidNodeTypeException;

interface NodeInterface
{
    /**
     * @throws InvalidNodeTypeException
     */
    public function validateType(mixed $value): void;
}