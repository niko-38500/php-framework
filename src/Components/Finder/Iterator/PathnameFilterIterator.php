<?php

declare(strict_types=1);

namespace App\Components\Finder\Iterator;

use App\Components\Finder\Utils\SplFileInfo;

/**
 * @extends FilterIterator<string, SplFileInfo>
 */
class PathnameFilterIterator extends FilterIterator
{
    public function accept(): bool
    {
        $filename = $this->currentValue()->getRelativePath();

        if ('\\' === \DIRECTORY_SEPARATOR) {
            $filename = str_replace('\\', '/', $filename);
        }

        return $this->isAccepted($filename);
    }
}