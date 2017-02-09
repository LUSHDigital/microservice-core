<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\MicroServiceServiceProvider;
 */

namespace LushDigital\MicroServiceCore;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider for core Micro Service functionality.
 *
 * @package LushDigital\MicroServiceCore
 */
class MicroServiceServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Add our package routes.
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }
}