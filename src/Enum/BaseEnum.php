<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Enum\BaseEnum.
 */

namespace LushDigital\MicroServiceCore\Enum;

use ReflectionClass;

/**
 * An abstract base class to use for all enum classes.
 *
 * @package LushDigital\MicroServiceCore\Enum
 */
abstract class BaseEnum
{
    /**
     * Get a list of allowed values.
     *
     * @return array
     */
    public static function getAllowedValues()
    {
        $class = new ReflectionClass(get_called_class());
        return $class->getConstants();
    }

    /**
     * Get a list of all keys defined in this class.
     *
     * @return array
     */
    public static function getKeys()
    {
        $class = new ReflectionClass(get_called_class());
        return array_keys($class->getConstants());
    }
}