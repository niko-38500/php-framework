<?php

declare(strict_types=1);

namespace App\Components\Finder\Iterator;

use App\Components\Finder\Utils\RegexHelper;
use Iterator;

/**
 * @template-covariant TKey
 * @template-covariant TValue
 *
 * @extends \FilterIterator<TKey, TValue>
 */
abstract class FilterIterator extends \FilterIterator
{
    /**
     * @param Iterator<TKey, TValue> $iterator The iterator to filter
     * @param string[] $matchRegexps Array of patterns, at least one of them that should match
     * @param string[] $noMatchRegexps Array of patterns, none of them should match
     */
    public function __construct(
        Iterator $iterator,
        protected array $matchRegexps,
        protected array $noMatchRegexps = []
    ) {
        parent::__construct($iterator);
    }

    protected function isAccepted(string $name): bool
    {
        foreach ($this->noMatchRegexps as $noMatchPattern) {
            if (!RegexHelper::isRegex($noMatchPattern)) {
                $noMatchPattern = RegexHelper::globToRegex($noMatchPattern);
            }

            if (preg_match($noMatchPattern, $name)) {
                return false;
            }
        }

        if ($this->matchRegexps) {
            foreach ($this->matchRegexps as $matchPattern) {
                if (!RegexHelper::isRegex($matchPattern)) {
                    $matchPattern = RegexHelper::globToRegex($matchPattern);
                }

                if (preg_match($matchPattern, $name)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @return TValue
     */
    public function currentValue()
    {
        return $this->current();
    }
}