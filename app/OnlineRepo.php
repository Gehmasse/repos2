<?php

namespace App;
use Illuminate\Support\Collection;

class OnlineRepo extends Repo {
    private function __construct(private \App\Storage\Repo $repo) {}

    public static function new(\App\Storage\Repo $repo): self {
        return new self($repo);
    }

    public static function all(): Collection {
        return (new \App\Request)
            ->repos()
            ->map(self::neW(...))
            ->sortBy(fn(self $repo) => $repo->string());
    }
    
    public function string(): string {
        return $this->repo->name;
    }

    public function type(): string {
        return 'Online';
    }

    public function cloneLink(): string {
        return '<a href="/clone"></a>';
    }
}