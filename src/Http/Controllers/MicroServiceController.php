<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Http\Controllers\MicroServiceController.
 */

namespace LushDigital\MicroServiceCore\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use LushDigital\MicroServiceCore\Enum\Status;
use LushDigital\MicroServiceCore\Helpers\MicroServiceHelper;
use LushDigital\MicroServiceCore\Traits\MicroServiceJsonResponseTrait;

/**
 * A base controller for use in microservices.
 *
 * Provides info and health check endpoints expected by the service registry.
 *
 * @package LushDigital\MicroServiceCore\Http\Controllers
 */
class MicroServiceController extends BaseController
{
    use MicroServiceJsonResponseTrait;

    /**
     * Retrieve information about this microservice.
     *
     * Required by the service registry.
     *
     * @return Response
     */
    public function info()
    {
        // Build the response object.
        $serviceInfo = (object) MicroServiceHelper::getServiceInfo();
        $serviceInfo->endpoints = [];

        // Add the endpoints.
        foreach (app()->getRoutes() as $appRoute) {
            $endpoint = new \stdClass();
            $endpoint->uri = $appRoute['uri'];
            $endpoint->method = $appRoute['method'];

            $serviceInfo->endpoints[] = $endpoint;
        }

        return response()->json($serviceInfo);
    }

    /**
     * Retrieve a health check for this microservice.
     *
     * Required by the service gateway and load balancer.
     *
     * @return Response
     */
    public function health()
    {
        try {
            // Logic for validating health (e.g. connections to external
            // services) should go here.

            // If a cache driver is defined, check it is working.
            if (!empty(env('CACHE_DRIVER', null))) {
                Cache::put('health_check', 'test', 1);
                Cache::get('health_check');
            }

            // If a DB connection is defined, check it is working.
            if (!empty(env('DB_HOST', null))) {
                DB::connection()->getPdo();
            }

            // All of our service dependencies are working so build a valid
            // response object.
            return $this->generateResponse('health', null);
        } catch (\Exception $e) {
            return $this->generateResponse('', null, 500, Status::FAIL, $e->getMessage());
        }
    }
}
