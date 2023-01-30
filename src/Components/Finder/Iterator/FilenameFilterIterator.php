<?php

declare(strict_types=1);

namespace App\Components\Finder\Iterator;

use App\Components\Finder\Utils\SplFileInfo;

/**
 * @extends FilterIterator<string, SplFileInfo>
 */
class FilenameFilterIterator extends FilterIterator
{
    public function accept(): bool
    {
        return $this->isAccepted($this->currentValue()->getFilename());
    }
}