<?php

namespace App\Storage;

class Path
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = '/' . trim($path, '/');
    }

    public function getPath(): string
    {
        return '/' . trim($this->path, '/');
    }

    public function equals(Path $other): bool
    {
        return $this->path === $other->path;
    }

    public function toHtml(): string
    {
        return '<div class="path" data-path="' . $this->getPath() . '">
            <label class="move-in"><i class="bi bi-box-arrow-down"></i></label>
            <a href="?path=' . $this->path . '">' . $this->trimmed() . '</a>
        </div>';
    }

    public function trimmed(bool $prependRoot = true): string
    {
        return $this->path === '/' && $prependRoot ? '/' : trim($this->path, '/');
    }
}
