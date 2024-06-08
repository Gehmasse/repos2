<?php

namespace App\Storage;

use Illuminate\Support\Collection;

abstract class Storage
{
    public function __construct(
        private readonly string $path,
    )
    {
        $this->ensure();
    }

    function path(): string {
        return storage_path($this->path);
    }

    function ensure(): void
    {
        if(!file_exists('data')) {
            mkdir('data', recursive: true);
        }

        if (!file_exists($this->path())) {
            file_put_contents($this->path(), '{}');
        }
    }

    function get(): Collection
    {
        return Collection::make(json_decode(file_get_contents($this->path()), associative: true));
    }

    function set(Collection $storage): void
    {
        file_put_contents($this->path(), json_encode($storage->toArray()));
    }
}
