<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Traits\MicroServiceStringTrait.
 */

namespace LushDigital\MicroServiceCore\Traits;

/**
 * A trait for string handling for microservices.
 *
 * @package LushDigital\MicroServiceCore\Traits
 */
trait MicroServiceStringTrait
{
    /**
     * Trim a string to the specified length. Padding if necessary.
     *
     * @param string $input
     *   The input string.
     * @param string $pad
     *   The padding character.
     * @param int $length
     *   The length of string we want.
     * @param int $mode
     *   The string padding mode.
     *
     * @return string
     */
    protected function padTrim($input, $pad = '0', $length = 3, $mode = STR_PAD_LEFT)
    {
        // Make sure we have a string.
        if (!is_string($input)) {
            $input = (string) $input;
        }

        return substr(str_pad($input, $length, $pad, $mode), 0, $length);
    }
}