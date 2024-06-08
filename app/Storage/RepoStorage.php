<?php

namespace App\Storage;

use Illuminate\Support\Collection;
use Override;

class RepoStorage extends Storage
{
    public function __construct()
    {
        parent::__construct('repos.json');
    }

    #[Override]
    function get(bool $archived = true): Collection
    {
        return parent::get()
            ->map(fn(array $repo) => Repo::fromJson($repo))
            ->filter(fn(Repo $repo) => $archived || !$repo->archived);
    }

    function getSorted(): array
    {
        return Collection::make($this->get())
            ->sortBy(fn(Repo $repo) => $repo->info()->getPath()->getPath() . '/' . $repo->name)
            ->toArray();
    }
}
