<?php

namespace App;

class Git {
    private function __construct(private string $dir) {}

    public static function new(string $dir): self {
        return new self($dir);
    }

    public function isRepo(): bool {
        return !str_contains($this->status(), 'not a git repository');
    }

    private function exec(string $command): string {
        return shell_exec('cd ' . $this->dir . ' && ' . $command) ?: null ?? '';
    }

    public function status(): string {
        return $this->exec('git status');
    }

    public function hasOrigin(): bool {
        return str_contains($this->exec('git remote'), 'origin');
    }

    public function needsPush(): bool {
        return str_contains($this->status(), 'Your branch is ahead');
    }

    public function needsPull(): bool {
        return str_contains($this->status(), 'Your branch is behind');
    }

    public function fetch(): void {
        $this->exec('git fetch');
    }

    public function pull(): void {
        $this->exec('git pull');
    }

    public function push(): void {
        $this->exec('git pull');
    }

    public function clone(string $src): void {
        $this->exec('git clone ' . $src);
    }
}
