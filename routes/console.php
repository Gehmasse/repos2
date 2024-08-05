<?php

use App\Request;
use App\Storage\Info;
use App\Storage\Repo;
use App\Storage\RepoStorage;
use Illuminate\Support\Facades\Artisan;

Artisan::command('repos:load', function () {
    (new RepoStorage)->set((new Request)->repos());
    $this->info('loaded');
})->describe('load repos from GitHub');

Artisan::command('repos:list {--archived}', function (bool $archived) {
    Repo::all($archived)
        ->each(fn(Repo $repo) => $repo->print($this, $repo->fullPath()));
})->describe('show list of repos');

Artisan::command('repos:tree {--archived}', function (bool $archived) {
    $pathBefore = '';

    Repo::all($archived)
        ->each(function (Repo $repo) use (&$pathBefore) {
            $path = $repo->info()->getPath();

            if ($path->getPath() !== $pathBefore) {
                $this->line('');
                $this->question($path->getPath());
            }

            $pathBefore = $path->getPath();

            $repo->print(
                command: $this,
                content: $repo->indentation() . $repo->name,
            );
        });
})->describe('show tree of repos');

Artisan::command('repos:url', function () {
    $name = $this->anticipate('select repo', Repo::allNames());

    $repo = Repo::findByName($name);

    if ($repo === null) {
        $this->fail('not found');
    }

    $this->line($repo->url);
})->describe('show url for a repo');

Artisan::command('repos:sort {--all}', function (bool $all) {
    Repo::all()->each(function (Repo $repo) use ($all) {
        if ($all || !$repo->hasInfo()) {
            $path = $this->anticipate(
                'enter path for ' . $repo->name,
                Info::allPaths(),
                $repo->info()->getPath()->getPath(),
            );

            $repo->info()->setPath($path)->persist($repo->id);
        }
    });
})->describe('sort unsorted repos in folders. with --all option also sorted ones');

Artisan::command('repos:rename', function () {
    $old = $this->choice('select path', Info::allPaths());
    $new = $this->ask('enter new name for ' . $old);

    Repo::all(archived: true)->each(function (Repo $repo) use ($old, $new) {
        $info = $repo->info();

        if (str_starts_with($info->getPath()->getPath(), $old)) {
            $info->setPath(str_replace($old, $new, $info->getPath()->getPath()))->persist($repo->id);
        }
    });
})->describe('rename a folder');

Artisan::command('repos:move', function () {
    $name = $this->anticipate('select repo', Repo::allNames());

    $repo = Repo::findByName($name);

    $path = $this->anticipate('enter path for ' . $repo->name, Info::allPaths());

    $repo->info()->setPath($path)->persist($repo->id);
})->describe('move a repo to a new path');

Artisan::command('x', function () {
    \App\Repo::all()->each(fn(\App\Repo $repo) => dump($repo->string()));
});
