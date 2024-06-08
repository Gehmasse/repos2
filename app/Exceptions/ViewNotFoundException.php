<?php

namespace App\Exceptions;

use Exception;

class ViewNotFoundException extends Exception
{
    public function __construct(string $path) {
        parent::__construct('view ' . $path . ' not found');
    }
}