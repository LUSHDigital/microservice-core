<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Microservice Base Routes
|--------------------------------------------------------------------------
|
| These are the default routes that every microservice needs to expose.
| Don't edit these unless you have a very good reason.
| No really, don't edit them!
*/
Route::get('/', 'MicroServiceController@info');
Route::get('/healthz', 'MicroServiceController@health');