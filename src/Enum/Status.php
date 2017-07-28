<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Enum\Status.
 */

namespace LushDigital\MicroServiceCore\Enum;

/**
 * Enum class for possible response statuses.
 *
 * @package LushDigital\MicroServiceCore\Enum
 */
final class Status extends BaseEnum
{
    /**
     * OK status.
     *
     * @var string
     */
    const OK = 'ok';

    /**
     * Something somewhere broken.
     *
     * @var string
     */
    const FAIL = 'fail';
}