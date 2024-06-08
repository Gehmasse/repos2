<?php

namespace App\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    public function __construct(string $method, string $route) {
        parent::__construct('route ' . $route . ' for ' . $method . ' not found');
    }
}