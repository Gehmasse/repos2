<?php

namespace App;
use Illuminate\Support\Collection;

class OnlineRepo extends Repo {
    private function __construct(private string $dir) {}

    public static function new(string $dir): self {
        return new self($dir);
    }

    private static function base(): string {
        return env('REPO_LOCATION');
    }

    public static function all(): Collection {
        return collect(scandir(self::base()))
            ->filter(fn(string $elem) => !str_starts_with($elem, '.') && is_dir(self::base() . '/' . $elem))
            ->map(OfflineRepo::new(...));
    }
    
    public function dir(): string {
        return $this->dir;
    }

    public function path(): string {
        return self::base() . '/' . $this->dir;
    }

    protected function string(): string {
        return $this->dir;
    }
}