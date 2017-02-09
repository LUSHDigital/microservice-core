<?php

/*
|--------------------------------------------------------------------------
| Microservice Base Routes
|--------------------------------------------------------------------------
|
| These are the default routes that every microservice needs to expose.
| Don't edit these unless you have a very good reason.
| No really, don't edit them!
*/
$this->app->get('/', 'LushDigital\MicroServiceCore\Http\Controllers\MicroServiceController@info');
$this->app->get('/healthz', 'LushDigital\MicroServiceCore\Http\Controllers\MicroServiceController@health');