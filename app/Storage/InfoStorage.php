<?php

namespace App\Storage;

use Illuminate\Support\Collection;
use Override;

class InfoStorage extends Storage
{
    public function __construct()
    {
        parent::__construct('infos.json');
    }

    #[Override]
    function get(): Collection
    {
        return parent::get()
            ->map(fn(array $repo) => Info::fromJson($repo));
    }

    function exists(int $id): bool
    {
        return $this->get()->has($id);
    }

    function find(int $id): Info
    {
        return $this->get()->get($id, new Info());
    }

    public function update(int $id, Info $info): void
    {
        $data = $this->get()->map(fn(Info $info) => $info->toJson());

        $data[$id] = $info->toJson();

        $this->set($data);
    }

    function folders(int $level = 0, string $root = '/'): Collection
    {
        return Collection::make($this->get())
            ->map(fn(Info $info) => $info->getPath())
//          ->unique()
//          ->filter(fn(Path $path) => $path->hasRoot($root))
//          ->map(fn(Path $path) => $path->nthPart($level))
            ->unique();
    }
}
