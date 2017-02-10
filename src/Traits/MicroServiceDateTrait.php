<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Traits\MicroServiceDateTrait.
 */

namespace LushDigital\MicroServiceCore\Traits;

/**
 * A trait for date handling for microservices.
 *
 * @package LushDigital\MicroServiceCore\Traits
 */
trait MicroServiceDateTrait
{
    /**
     * Check if the given date is in the given format.
     *
     * @param $format
     * @param $date
     * @return bool
     */
    protected function validateDate($format, $date)
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}