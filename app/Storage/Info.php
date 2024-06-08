<?php

namespace App\Storage;

use Illuminate\Support\Collection;

class Info
{
    public function __construct(
        private string $path = '/',
    )
    {
    }

    public static function fromJson(array $info): self
    {
        return new self(path: $info['path'] ?? '/');
    }

    public static function allPaths(): array
    {
        return self::all()
            ->map(fn(Info $info) => $info->getPath()->getPath())
            ->unique()
            ->toArray();
    }

    public static function all(): Collection
    {
        return (new InfoStorage)->get();
    }

    public function toJson(): array
    {
        return ['path' => $this->path];
    }

    public function getPath(): Path
    {
        return new Path($this->path);
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function persist(int $id): void
    {
        (new InfoStorage)->update($id, $this);
    }
}
