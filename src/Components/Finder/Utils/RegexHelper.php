<?php

declare(strict_types=1);

namespace App\Components\Finder\Utils;

class RegexHelper
{
    /**
     * Checks whether the string is a regex.
     */
    public static function isRegex(string $str): bool
    {
        $availableModifiers = 'imsxuADU';

        if (\PHP_VERSION_ID >= 80200) {
            $availableModifiers .= 'n';
        }

        if (preg_match('/^(.{3,}?)['.$availableModifiers.']*$/', $str, $m)) {
            $start = substr($m[1], 0, 1);
            $end = substr($m[1], -1);

            if ($start === $end) {
                return !preg_match('/[*?[:alnum:] \\\\]/', $start);
            }

            foreach ([['{', '}'], ['(', ')'], ['[', ']'], ['<', '>']] as $delimiters) {
                if ($start === $delimiters[0] && $end === $delimiters[1]) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Transform a glob pattern to a regex
     *
     * ex :
     *
     * <code>
     * *.php ===> #^[^/]*\.php$#
     * **\/*.php ===> #^[^/]*[^/]*\/[^/]*\.php$#
     * src/{ici,la,un_autre_file,ou_la}*.php ===> #^src/(ici|la|un_autre_file|ou_la)[^/]*\.php$#
     * </code>
     */
    public static function globToRegex(string $glob): string
    {
        $delimiter = '#';
        $reg = '';
        $inCurlies = 0;
        $escaping = false;

        $patternLength = strlen($glob);

        for ($i = 0; $i < $patternLength; $i++) {
            $character = $glob[$i];

            if (
                $delimiter === $character ||
                '.' === $character ||
                '(' === $character ||
                ')' === $character ||
                '|' === $character ||
                '+' === $character ||
                '^' === $character ||
                '$' === $character
            ) {
                $reg .= '\\' . $character;
            } elseif ($character === '{') {
                $reg .= $escaping ? $character : '(?:';
                ++$inCurlies;
            } elseif ($character === '}' && $inCurlies) {
                $reg .= $escaping ? $character : ')';
                --$inCurlies;
            } elseif ($character === ',' && $inCurlies) {
                $reg .= $escaping ? $character : '|';
            } elseif ($character === '*') {
                $reg .= $escaping ? '\\' . $character : '[^/]*';
            } elseif ($character === '\\') {
                if (!$escaping) {
                    $escaping = true;
                    continue;
                }

                $reg .= '\\\\';
            } else {
                $reg .= $character;
            }

            $escaping = false;
        }

        return $delimiter . '^' . $reg . '$' . $delimiter;
    }
}