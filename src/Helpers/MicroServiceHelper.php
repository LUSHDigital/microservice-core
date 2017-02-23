<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Helpers.
 */

namespace LushDigital\MicroServiceCore\Helpers;

use Exception;
use stdClass;

/**
 * A helper class to provide useful functionality common to all microservices.
 *
 * @package LushDigital\MicroServiceCore\Helpers
 */
class MicroServiceHelper
{
    /**
     * Get information about this microservice based on envvars.
     *
     * @return array
     */
    public static function getServiceInfo()
    {
        return [
            'service_name' => env('SERVICE_NAME'),
            'service_type' => env('SERVICE_TYPE'),
            'service_scope' => env('SERVICE_SCOPE'),
            'service_version' => env('SERVICE_VERSION'),
        ];
    }

    /**
     * Prepare a response an endpoint.
     *
     * This ensures that all API endpoints return data in a standardised format:
     *
     * {
     *     "status": "ok", - Can contain any string. Usually 'ok', 'error' etc.
     *     "code": 200, - A HTTP status code.
     *     "message": "", - A message string elaborating on the status.
     *     "data": {[ - A collection of return data. Can be omitted in the event
     *     ]}           an error occurred.
     * }
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
     * @return object
     *   The formatted response object.
     *
     * @throws Exception
     */
    public static function jsonResponseFormatter($type, $data, $code = 200, $status = 'ok', $message = '')
    {
        // Validate the arguments.
        if (!empty($data) && empty($type)) {
            throw new Exception('Cannot prepare response. No type specified');
        }
        else {
            $type = preg_replace('/[^A-Za-z]/si', '_', strtolower($type));
        }

        // Ensure the data is in an array.
        if (is_object($data)) {
            $data = [(array) $data];
        }

        // Prepare the response object.
        $response = new stdClass();
        $response->status = $status;
        $response->code = $code;
        $response->message = $message;

        // Only add the data if supplied.
        if (!empty($data)) {
            $response->data = new stdClass();
            $response->data->{$type} = $data;
        }

        return $response;
    }
}
