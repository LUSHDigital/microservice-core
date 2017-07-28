<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Traits\MicroServiceJsonResponseTrait.
 */

namespace LushDigital\MicroServiceCore\Traits;

use LushDigital\MicroServiceCore\Enum\Status;
use LushDigital\MicroServiceCore\Helpers\MicroServiceHelper;

/**
 * A trait for creating a microservice JSON response.
 *
 * @package LushDigital\MicroServiceCore\Traits
 */
trait MicroServiceJsonResponseTrait
{
    /**
     * Generate a response object in the microservices expected format.
     *
     * @param string $type
     *   The type of data being returned. Will be used to name the collection.
     * @param object|array|NULL $data
     *   The data to return. Will always be parsed into a collection.
     * @param int $code
     *   HTTP status code for the response.
     * @param string $status
     *   A short status message. Examples: 'OK', 'Bad Request', 'Not Found'.
     * @param string $message
     *   A more detailed status message.
     *
     * @return \Illuminate\Http\Response
     */
    protected function generateResponse($type, $data, $code = 200, $status = Status::OK, $message = '')
    {
        $returnData = MicroServiceHelper::jsonResponseFormatter($type, $data, $code, $status, $message);
        return response()->json($returnData, $code);
    }
}