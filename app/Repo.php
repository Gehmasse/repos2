<?php

namespace App;

use Illuminate\Support\Collection;

abstract class Repo {
    public static function all(): Collection {
        return collect([...OfflineRepo::all()])
            ->sortBy(fn(self $repo) => $repo->string())->dd();
    }

    abstract protected function string(): string;
}
