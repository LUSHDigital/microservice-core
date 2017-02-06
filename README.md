# Lush Digital - Micro Service Core
A set of core functionality and convenience classes for a Lumen microservice.

## Description
This package is intended to provide a quick and easy bootstrap of functionality that a micro service is expected
to provide. This includes an information route that could be used by a service registry, it also includes a health
check route to verify your microservice is working.

The package also contains some convenience classes to help develop microservices.

## Package Contents
* Core micro service controller
* Micro service helper class
* Base enumeration class. 
* Exception handling trait
* JSON Response trait
* String handling trait

## Installation
Just register the service provider with Lumen in the `bootstrap/app.php` file:
```php
$app->register(LushDigital\MicroServiceCore\MicroServiceServiceProvider::class);
```