<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main', [
        'offline' => \App\OfflineRepo::all(),
        'online' => \App\OnlineRepo::all(),
    ]);
});
