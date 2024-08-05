<?php

namespace App;

use Illuminate\Support\Collection;

abstract class Repo {
    abstract public function string(): string;
    
    abstract public function type(): string;
}
