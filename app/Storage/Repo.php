<?php

namespace App\Storage;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

readonly class Repo
{
    public function __construct(
        public int    $id,
        public string $name,
        public bool   $private,
        public bool   $archived,
        public string $url,
    )
    {
    }

    public static function fromRequest(array $request): self
    {
        return new self($request['id'], $request['name'], $request['private'], $request['archived'], $request['html_url']);
    }

    public static function fromJson(array $response): self
    {
        return new self(
            id: $response['id'],
            name: $response['name'],
            private: $response['private'],
            archived: $response['archived'],
            url: $response['url'],
        );
    }

    public static function allNames(bool $archived = false): array
    {
        return self::all($archived)
            ->map(fn(Repo $repo) => $repo->name)
            ->toArray();
    }

    public static function findByName(string $name): ?Repo
    {
        return self::all()
            ->first(fn(Repo $repo) => $repo->name === $name);
    }

    public static function all(bool $archived = false): Collection
    {
        return (new RepoStorage)->get($archived)->sortBy(fn(Repo $repo) => $repo->fullPath());
    }

    public function info(): Info
    {
        return (new InfoStorage)->find($this->id);
    }

    public function toHtml(): string
    {
        return '<div id="' . $this->id . '" class="repo ' . ($this->archived ? 'archived' : '') . ' ' . ($this->private ? 'private' : '') . '">
            <label class="move-out"><i class="bi bi-box-arrow-up"></i></label>
            <a href="' . $this->url . '" target="_blank">' . $this->name . '</a>
        </div>';
    }

    public function level(): int
    {
        return count(explode('/', $this->fullPath())) - 2;
    }

    public function hasInfo(): bool
    {
        return (new InfoStorage)->exists($this->id);
    }

    public function fullPath(): string
    {
        return '/' . trim($this->info()->getPath()->getPath() . '/' . $this->name, '/');
    }

    public function print(Command $command, string $content): void
    {
        match (true) {
            $this->archived => $command->warn($content),
            $this->private => $command->line($content),
            default => $command->info($content),
        };
    }

    public function indentation(): string
    {
        return str_repeat('    ', $this->level());
    }
}
